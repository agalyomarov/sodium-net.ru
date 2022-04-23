var _is_running = false;

$(document).ready(function(e) {
    // initSlider('.modal-jackpot');
	// var fairSlider = initFairSlider('.fair-slider');
    $('.room-selector .' + ROOM).addClass('active');

    if( $('.history-card-wrapper').length > 0 ) {
        $('.jackpot-preloader ').hide();
    } else {
        $('.jackpot-preloader ').show();
    }

    var gameBank = 0;
    $('.betSum').each((index, el) => {
        console.log(el);
        gameBank += parseInt($(el).text());
    });
    $('.gameBank').text(gameBank);

	$('.makeBet').click(function() {
		// if (!validateField('#amount')) {
		// 	return;
		// }
		$('.makeBet').prop('disabled', true);
		var value = parseInt($('#amount').val());
		var room = ROOM;
		$.post('/newBet', {sum: value, room: room})
		.done(function(data){
            // $.notify({
            //     position : 'bottom-right',
			// 	type: data.status,
			// 	message: data.msg
			// });
			if(data.status == 'success'){
                updateBalanceAnimated('#money_mob', data.balance);
			}
			if (!_is_running) {
				$('.makeBet').prop('disabled', false);
            }
		})
		.fail(function(){
			if (!_is_running)
				$('.makeBet').prop('disabled', false);
		});
	});

	var socket = io.connect(':8443');
	
	socket.on('jackpot.newBet', function(data) {
		$('.jackpot-preloader__' + data.room).hide();
		$('.history-cards__' + data.room).show();
        var bet = '';
        
        data.bets.forEach(function (info) {
			bet += '<div class="history-card-wrapper">\
				<div class="history-card">\
					<div class="history-card__top-side">\
						<div class="history-param_ticket">\
							<span class="history-param__txt"> #' + info.from + ' - #' + info.to + '</span>\
						</div>\
						<img src="' + info.avatar + '" class="history-card__top-side_image" style="border-color: #' + info.color + ' alt=""> \
						<div class="history-card__right">\
							<div class="history-card__username">' + info.username + '</div>\
						</div>\
					</div>\
					<div class="history-card__bottom-side">\
						<div class="history-param history-param_money">\
							<span class="history-param__icon myicon-coins"></span>\
							<span class="history-param__txt">' + info.sum + '</span>\
						</div>\
						<div class="history-param history-param_chance">\
							<span class="history-param__txt">' + info.chance.toFixed(2) + ' </span><span class="history-param__icon">%</span>\
						</div>\
					</div>\
				</div>\
			</div>';
        });
		
		var chances = '';
        for(var i = 0; i < data.chances.length; i++) {
			chances += '<li class="tooltip" title="' + data.chances[i].username + '">\
						<img src="' + data.chances[i].avatar + '" class="avatar" alt="">\
						<span class="chances-field chances-field__money">' + data.chances[i].sum + ' <span class="myicon-coins"></span></span>\
						<span class="chances-field">' + data.chances[i].chance.toFixed(2) + '%</span>\
						<color style="background: #' + data.chances[i].color + ';"></color>\
					</li>';
        }
		$('#roombank_' + data.room).text(data.game.price);
		$('#gamebank_' + data.room).text(data.game.price);
        $('#players_' + data.room).text(data.chances.length);
		// if($('#soundController').val() == 'on' && ROOM == data.room) audio('/sounds/bet.mp3', 0.3);
		$('#bets_' + data.room).html(bet);
		$('#chances_' + data.room).html(chances);
		$('.tooltip').tooltipster({
			side: 'bottom',
			theme: 'tooltipster-borderless'
		});
    });
	
	socket.on('jackpot.timer', function(data) {
        var sec = data.sec,
            min = data.min,
            time = data.time,
            timer = data.timer;
        if(sec < 10) sec = '0' + sec;
        if(min < 10) min = '0' + min;
		if($('#soundController').val() == 'on' && ROOM == data.room) if(sec <= 5) audio('/sounds/timer-tick-quiet.mp3', 0.2);
		$('#timeline_'+data.room).css({width: (time/timer)*100+'%'})
		$('#time_'+data.room).text(min+':'+sec);
		if (sec <= 3){
			_is_running = true;
			$('.makeBet__' + data.room).prop('disabled', true);
			$('.makeBet__' + data.room).text('Ставки закрыты');
		}
    });
	
	socket.on('jackpot.ngTimer', function(data) {
		if(data.ngtime < 10) data.ngtime = '0' + data.ngtime;
		$('#time_'+data.room).text('00:'+data.ngtime);
		$('#timeline_'+data.room).css({width: (data.ngtime/15)*100+'%'});
    });
	
	socket.on('jackpot.slider', function(data) {
		$('#chouser_'+data.room).slideDown();
        var members = '';
        for(var i = 0; i < data.members.length; i++) members += '<p><img src="' + data.members[i].avatar + '" alt=""><color style="background: #' + data.members[i].color + ';"></color></p>';
        $('#carousel_'+data.room).html(members);
		$('#carousel_'+data.room).css({
			transform: 'translate3d(-' + data.ml + 'px, 0px, 0px)',
			transition: 7000 + 'ms cubic-bezier(0.32, 0.64, 0.45, 1)'
		});
		setTimeout(function () {
			if($('#soundController').val() == 'on' && ROOM == data.room) audio('/sounds/set_winner.wav', 0.3);
			$('.outcome-window_winner-name__'+data.room).text(data.winner.username);
			$('.outcome-window_winner__sum__'+data.room).text(data.winner.sum);
			$('.outcome-window_winner__percent__'+data.room).text(data.winner.chance.toFixed(2)+'%');
			$('.outcome-window_winner-ticket-number__'+data.room).text(data.winner.ticket);
			// $('.jackpot-fair-check__'+data.room).attr('href', '/fairness?' + generateQueryString({'mode': 'jackpot', 'server_seed': data.server_seed, 'client_seed': data.client_seed, 'salt': data.salt, 'jackpot_tickets': data.jackpot_tickets}));
			$('.outcome-window_winner__'+data.room).fadeIn(200);
			
		}, 7200);

		setTimeout(function() {
			$('#prev_server_seed__' + data.room).val(data.server_seed);
			$('#prev_client_seed__' + data.room).val(data.client_seed);
			$('#prev_salt__' + data.room).val(data.salt);
			$('#current_server_seed__' + data.room).val(data.current_server_seed);
			$('#current_client_seed__' + data.room).val(data.current_client_seed);
			$('#current_salt__' + data.room).val('hidden');
			if ($('#current_salt__' + data.room).length) {
				fairSlider.flickity('selectCell', 1);
			}
			$('.fair-slider-prev__' + data.room).prop('disabled', false);
			$('#jackpot_tickets__' + data.room).val(data.jackpot_tickets);
		}, 7200);

    });
	
	socket.on('jackpot.newGame', function(data) {
		$('.jackpot-preloader__' + data.room).show();
		$('.history-cards__' + data.room).hide();
		_is_running = false;
		$('.makeBet__' + data.room).text('Играть');
		$('.makeBet__' + data.room).prop('disabled', false);
		// if($('#soundController').val() == 'on' && ROOM == data.room) audio('/sounds/jackpot-new-game.wav', 0.2);
        $('#carousel_' + data.room).removeAttr('style');
        $('#carousel_' + data.room).html('');
        $('#chouser_' + data.room).slideUp();
		$('.outcome-window_winner__'+data.room).fadeOut(200);
        $('#time_' + data.room).text(data.time[0]+':'+data.time[1]);
        $('#roombank_' + data.room).text('0');
        $('#gamebank_' + data.room).text('0');
        $('#bets_' + data.room).html('');
        $('#chances_' + data.room).html('');
        $('#roundId_' + data.room).html('#'+data.game.id);
		$('#timeline_' + data.room).css({width: '100%'});
    });
});