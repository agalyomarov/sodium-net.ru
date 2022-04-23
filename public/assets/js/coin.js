var spinArray = ['animation2160'];
var sounds = $.cookie('sounds');
$(document).ready(function() {	
    var socket = io.connect(':8443');
    
	socket.on('new.flip', function (data) {
		var html = '';
		html += '<li class="flip_'+ data.id +'">';
		html += '<div class="game">';
		html += '<div class="user">';
		html += '<div class="avatar"><img src="'+ data.avatar +'" alt=""></div>';
		html += '<div class="username">'+ data.username +'</div>';
		html += '</div>';
		html += '<div class="bet">Ставка: <span>'+ data.price +' <i class="fas fa-coins"></i></span></div>';
		html += '<div class="button"><a class="joinGame" onclick="joinRoom('+ data.id +')">Играть</a></div>';
		html += '</div>';
		html += '</li>';
		$('.games').append(html);
		if($('#soundController').val() == 'on') audio('/sounds/bet.mp3', 0.3);
	});
    
	socket.on('end.flip', function (data) {
		$('.flip_'+ data.game.id).remove();
		var html = '';
			html += '<li class="flip-block_'+ data.game.id +'">';
			html += '<div class="title">Игра #'+ data.game.id +' <div class="bank">Банк: <span>'+ data.game.price +' <i class="fas fa-coins"></i></span></div></div>';
			html += '<div class="gameBlock">';
			html += '<div class="vs">VS</div>';
			html += '<div class="left">';
			html += '<div class="avatar"><img src="'+ data.user1.avatar +'" alt=""></div>';
			html += '<div class="username">'+ data.user1.username +'</div>';
			html += '<div class="tickets"><i class="fas fa-ticket-alt"></i> #'+ data.user1.from +'-'+ data.user1.to +'</div>';
			html += '</div>';
			html += '<div class="right">';
			html += '<div class="username">'+ data.user2.username +'</div>';
			html += '<div class="tickets"><i class="fas fa-ticket-alt"></i> #'+ data.user2.from +'-'+ data.user2.to +'</div>';
			html += '<div class="avatar"><img src="'+ data.user2.avatar +'" alt=""></div>';
			html += '</div>';
			html += '<div class="center">';
			html += '<div id="timer_'+ data.game.id +'"><div class="time"><span id="count_num_'+ data.game.id +'">5</span></div></div>';
			html += '<div id="coin-flip-cont_'+ data.game.id +'" style="display: none;">';
			html += '<div id="coin_'+ data.game.id +'">';
			html += '<div class="front"><img src="'+ data.winner.avatar +'"></div>';
			html += '<div class="back"><img src="'+ data.loser.avatar +'"></div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
			html += '<div class="check-random">';
			html += '<a href="/fair/'+ data.game.hash +'" class="btn btn-white btn-sm btn-right" style="display: none;">Проверить</a>';
			html += '</div>';
			html += '<div class="bottom">';
			html += '<div class="win">Счастливый билет: <span>...</span></div>';
			html += '</div>';
			html += '</li>';
		$('.last').prepend(html);
		if($('#soundController').val() == 'on') audio('/sounds/jackpot-new-game.wav', 0.2);
		setTimeout(function() {
			handleTimer(data.game.id);
		}, 2000);
		setTimeout(function() {
			$('.flip-block_'+ data.game.id +' .win span').html('<i class="fas fa-ticket-alt"></i> ' + data.winner.ticket);
			$('.flip-block_'+ data.game.id +' .center .front').addClass('winner_a');
			$('.flip-block_'+ data.game.id +' .check-random .btn').show();
		}, 13000);
		$('.last li:nth-child(6)').remove(); 
	});
});

function createRoom() {
	var value = parseFloat($('#amount').val());
	if(isNaN(value)) {
        $.notify({
            position : 'top-right',
            type: 'error',
            message: 'Вы забыли указать сумму!'
        });
		return;
	} 
	$.ajax({
		url : '/flip/newGame',
		type : 'post',
		data : {
			value : value

		},
		success : function(data) {
			$('#amount').val('');
			$.notify({
				position : 'top-right',
				type: data.type,
				message: data.msg
			});
		},
		error : function(data) {
			console.log(data.responseText);
            $.notify({
                position : 'top-right',
                type: 'error',
                message: 'Ошибка!'
            });
		}
	});
}

function joinRoom(id) {
	$.ajax({
		url: '/flip/joinRoom',
		data: {
			id: id
		},
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			$.notify({
				position : 'top-right',
				type: data.type,
				message: data.msg
			});
		}

	});
}

function getSpin() {
    var spin = spinArray[Math.floor(Math.random() * spinArray.length)];
	if($('#soundController').val() == 'on') audio('/sounds/flip.mp3', 0.3);
    return spin;
}

function handleTimer(id) {
    var countdownElement = document.getElementById('count_num_'+id),
        seconds = 5,
        second = 0,
        interval;

    interval = setInterval(function() {
        countdownElement.firstChild.data = (seconds - second);
        if (second >= seconds) {
			$('#timer_'+id).hide();
			$('#coin-flip-cont_'+id).show();
            $('#coin_'+id).addClass(getSpin());
            clearInterval(interval);
			setInterval(function() {
				$('.flip-block_'+id+' .front').addClass('winner_a');
			}, 4000);
        }
        second++;
    }, 1000);
}