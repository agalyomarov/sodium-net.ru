$(document).ready(function(e) {
	build(0.5);
	var socket = io.connect(':8443');

	socket.on('battle.newBet', function(data) {
		var colors = [];
		for(var i in data.bets) {
            var bet = data.bets[i];
            if(typeof colors[bet.color] == 'undefined') colors[bet.color] = '';
			colors[bet.color] += '<div class="list-item flex" style="justify-content: left;"><div class="ava" style="background-image: url('+ bet.avatar +');"></div><div class="name">'+ bet.username +'</div><div class="sum">'+ bet.price +'</div></div>';
        }
		for(var color in colors) {
            $('#'+ color +'_list').html(colors[color]);
        }
		if($('#soundController').val() == 'on') audio('/sounds/bet.mp3', 0.3);
		$("#blue_sum").html(data.bank[1]);
		$("#red_sum").html(data.bank[0]);
		$('#red_persent').text(data.chances[0] + '%');
		$('#blue_persent').text(data.chances[1] + '%');
		$('#red_factor').text('x'+data.factor[0]);
		$('#blue_factor').text('x'+data.factor[1]);
		$('#red_tickets').text(data.tickets[0]);
		$('#blue_tickets').text(data.tickets[1]);
		$('title').text('🔴 ' + Math.round(data.chances[0]) + '%' + ' / ' + Math.round(data.chances[1]) + '%' + ' 🔵');
		build(data.chances[1] / 100);
    });
	socket.on('battle.timer', function(data) {
        var time = data.time;
        var pretime = data.pretime;
		$("#timer").css('font-size', '1em');
		$("#timer").removeClass('explode');
		$("#timer").css('color','#99aed7');
		$("#timer").html(time);
		$("#timer").css('-webkit-animation', '');
		$("#timer").css('animation', '');
		if(time <= 3) {
			$("#timer").addClass('explode');
			$("#timer").css('color','#D1345B');
			setTimeout(function(){
				$("#timer").removeClass('explode');
			}, 3000);
		}
		if(pretime <= 3) {
			if($('#soundController').val() == 'on') audio('/sounds/timer-tick-quiet.mp3', 0.2);
			$("#timer").css('font-size', '0.3em');
			$("#timer").css('color','#99aed7');
			$("#timer").removeClass('explode');
			$("#timer").html('Старт через ' + pretime);
			$("#timer").css('-webkit-animation', '');
			$("#timer").css('animation', '');
		}
    });
	socket.on('battle.slider', function(data) {
		if($('#soundController').val() == 'on') audio('/sounds/double-start.wav', 0.3);
		$("#circle").css('transition', 'transform 4s cubic-bezier(0.15, 0.15, 0, 1)');
		$("#circle").css('transform', 'rotate(' + (3600 + data.ticket * 0.36) + 'deg)');
		$("#timer").css('font-size', '1em');
		$("#timer").html('<i class="fas fa-play"></i>');
    	$("#timer").css('color','#99aed7');
		$('title').text('▷ Игра....');
		setTimeout(function() {
			if($('#soundController').val() == 'on') audio('/sounds/double-end.wav', 0.3);
			$("#history").prepend('<a href="/fair/'+ data.game.hash +'" class="battle-last-i '+ data.game.winner_team +'"></a>');
			$('#history').children().slice(16).remove();
		}, 4000);
    });
	socket.on('battle.newGame', function(data) {
		if($('#soundController').val() == 'on') audio('/sounds/jackpot-new-game.wav', 0.2);
		$("#timer").html('<i class="fas fa-hourglass-start"></i>');
		$("#timer").css('-webkit-animation', 'blink 2s linear infinite');
		$("#timer").css('animation', 'blink 2s linear infinite');
		$("#red_list").html('');
		$("#blue_list").html('');
		$("#circle").css('transition', '');
		$("#circle").css('transform', 'rotate(0deg)');
		$("#red_persent").html('50%');
		$("#blue_persent").html('50%');
		$("#red_tickets").html(500);
		$("#blue_tickets").html(501);
		$("#red_factor").html('x2');
		$("#blue_factor").html('x2');
		$("#blue_sum").html('0');
		$("#red_sum").html('0');
		$("#hash").html(data.hash);
		$("#roundId").text('#'+data.id);
		$('title').text(sitename);
		build(0.5);
    });
});

function bet(type) {
	$.ajax({
		url: '/battle/addBet',
		type: 'post',
		data: {
			type: type,
			sum: $('#amount').val()
		},
		success: function(data) {
			$.notify({
				position : 'top-right',
				type: data.type,
				message: data.msg
			});
		}
	})
}

function build(blue_cur) {
  var blue = d3.arc()
      .innerRadius(155)
      .outerRadius(180)
      .startAngle(0)
      .endAngle(2 * Math.PI * blue_cur);
  $("#blue").attr('d', blue());
  var red = d3.arc()
      .innerRadius(155)
      .outerRadius(180)
      .startAngle(2 * Math.PI * blue_cur)
      .endAngle(2 * Math.PI);
  $("#red").attr('d', red());
}