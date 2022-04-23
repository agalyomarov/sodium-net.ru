$(document).ready(function() {
	var timerId;
	var socket = io.connect(':8080');
	socket.on('dice', function (data) {
		if(data.win == 0) {
			var status = 'lose';
			var win_sum = data.win_sum;
		} else {
			var status = 'win';
			var win_sum = '+'+data.win_sum;
		}

		var html = '';
		html += '<tr class="show games-table__body-tr">';
		html += ' <td class="games-table__body-td games-table__body-td_low_padding"><div class="flex-align games-table__dice_history__name"> <span>'+ data.username +'</span> </div></td>';
		html += '<td class="games-table__body-td games-table__money"> <span class="myicon-coins"></span>'+ data.sum +'</td>';
		html += '<td class="games-table__body-td">'+ data.num +'</td>';
		html += '<td class="games-table__body-td">'+ data.vip +'x</td>';
		html += '<td class="games-table__body-td games-table__money games-table__money_'+status+'">'+ win_sum +'<span class="myicon-coins"></span></td>';
		html += ' <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/'+data.hash+'" class="tooltip tooltipstered" target="_blank"><span class="myicon-security"></span></a></td>';
		html += '</tr>';
		$('#lastGame').prepend(html);
		$('#betsCount').text(data.betsCount);
		$('#betsSum').text(data.betsSum);
		if($('.show').length >= 20) $('.show:nth-child(21)').remove();
	}); 

	$('#play').click(function(e) {
		if($('#play').attr('disabled') == 'true') return;
		e.preventDefault();
		clearTimeout(timerId);
		$('#play').html('КИДАЕМ КОСТИ..');
		$('#play').prop('disabled', true);
		$.ajax({
			url: "/dice/play",
			type: 'post',
			data: {
				sum: $('#stavka').val(),
				perc: $('#r1').val()
			}, 
			success: function(data) {
				if (data.status == 'success') {
					$('.index__home__indicator__inner').show();
					$('.index__home__indicator__inner__number').animate({
						'left': $('#r1').width() / 100 * data.chislo + 'px'
					}, 100);
					$('#money_mob').text(data.balance);
					$('#money').val(data.balance);
					$('.index__home__indicator__inner__number__roll>span').html(data.chislo);
					if($('#soundController').val() == 'on') audio('/sounds/click.mp3', 0.2);
					if(data.win == 1) {
						$('.index__home__indicator__inner__number__roll').removeClass('is-negative');
						$('.index__home__indicator__inner__number__roll').addClass('is-positive')
					} else {
						$('.index__home__indicator__inner__number__roll').addClass('is-negative');
						$('.index__home__indicator__inner__number__roll').removeClass('is-positive')
					}
					timerId = setTimeout(function() {
						$('.index__home__indicator__inner').fadeOut('fast')
					}, 10000)
				}
				$('#hash').text(data.hash);
				$('#play').html('Сделать ставку');
				$('#play').prop('disabled', false)
			}
		})
	});
	$('.dice-loop .multipler .action').on('click', function (event) {
        let value = parseFloat($('#stavka').val()) || 0,
            all = $('#money').val(),
            thisMethod = $(this).attr('data-method'),
            thisValue = parseFloat($(this).attr('data-value'));

        switch(thisMethod) {
            case 'plus' :
                value += thisValue;
                break;
            case 'divide' :
                value = parseInt((value/thisValue).toFixed(0));
                break;
            case 'clear' :
                value = '';
                break;
            case 'last' :
                value = localStorage.getItem('last');
                break;
            case 'all' :
                value = all;
                break;
            case 'multiply' :
                value *= thisValue;
                break;
        }

        $('#stavka').val(value);
    });
});
function calc() {
    val = $('.range').val();
    $('.range').css({
        'background': '-webkit-linear-gradient(left ,#F10260 0%,#F10260 ' + val + '%,#08E547 ' + val + '%, #08E547 100%)'
    });
    var chance = (100 - $('#r1').val()).toFixed(2);
    var viplata = 99 / chance;
    $('#one').html(chance);
    $('#winner').html(viplata.toFixed(2));
    var summ = $("#stavka").val();
    var win1 = $('#winner').html();
    var summa = summ * win1;
    $("#win").text(Math.floor(summa))
}