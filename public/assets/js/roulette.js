$(document).ready(() => { 
    window.socket = io.connect(':8443');
    // initSlider('.modal-double');
    // var fairCarousel = initFairSlider('.fair-slider');
    new SimpleBar(document.getElementById('hline'), {
    });
    window.double = {};

    socket.on('roulette', (res) => {
        if(res.type == 'timer') {
            $('#rez-numbr').text((res.time > 9) ? res.time : '0'+res.time); 
            $('.double-rel').hide();
            if (res.time < 1) {
                $('.betButton').prop('disabled', true);
            }
        }
        if(res.type == 'slider')
        {
            $('.betButton').prop('disabled', true);
            $('.double-timer').hide();
            $('#reletteact').css({
                'transition' : 'transform ' + res.slider.time + 'ms ease',
                'transform' : 'rotate(' + res.slider.rotate + 'deg)'
            });
            setTimeout(() => {
                if(res.slider.color == 'red') {
					$('.double-rel').show().css({background: '#ffc200'}).text(res.slider.num);
                } else if(res.slider.color == 'green') {
					$('.double-rel').show().css({background: '#01c236'}).text(res.slider.num);
                } else {
					$('.double-rel').show().css({background: '#242323'}).text(res.slider.num);
                }
            }, res.slider.time);
        }
        if(res.type == 'newGame') 
        {
            $('.betButton').prop('disabled', false);
            // displayFairDataOnModal(res.server_seed, res.client_seed, res.salt, res.current_server_seed, res.current_client_seed, fairCarousel);
            $('#roundId').text(res.id);
            $('.double-history-line').prepend('<div class="double-circle double-circle_' + res.history.color + '"><span class="double-circle-inner"><a href="/fair/' + res.hash + '" target="_blank" class="double-circle-inner__number">' + res.history.num + '</a></span></div>');
            if($('.double-circle').length >= 50) $('.double-circle:nth-child(51)').remove();

            $('.double-history__col-bets__value').text(0);
            $('.double-history__col-body').slideUp(200, () => {
                $('.double-history__col-body').html('<div class="double-no-bets">Нет ставок</div>');
                $('.double-history__col-body').slideDown(200);
            });
            $('#reletteact').css({
                'transition' : 'transform 0s linear',
                'transform' : 'rotate('+res.slider.rotate+'deg)'
            });
			$('.double-rel').hide();
			$('.double-timer').show();
            $('#rez-numbr').text(res.slider.time);
			$('.tooltip').tooltipster({
				side: 'bottom',
				theme: 'tooltipster-borderless'
            });
        }
        if(res.type == 'bets') return double.updateBets(res.bets, res.prices);
    });

    double.updateBets = function(bets, prices)
    {
        var bet_stack = {
            'red': '',
            'green': '',
            'black': ''
        };

        var user_bet_stack = {
            'red': '',
            'green': '',
            'black': ''
        };

        var prices = {
            'red': 0,
            'green': 0,
            'black': 0
        };

        for(var i in bets)
        {
            let bet = bets[i];
            prices[bet.type] += parseInt(bet.value);
            if (bet.user_id == USER_ID) {
                user_bet_stack[bet.type] += '<div class="double-history__col-header-bet__user double-history__col-header-bet__personal">\
                                                <div class="double-history__col-header-bet__photo">\
                                                    <img src="' + bet.avatar + '" class="avatar">\
                                                </div>\
                                                <div class="double-history__col-header-bet__row">\
                                                    <div class="double-history__col-header-bet__username">' + bet.username + '</div>\
                                                    <div class="double-history__col-header-bet__amount">' + bet.value + '</div>\
                                                </div>\
                                            </div>';
            }
            else{
                bet_stack[bet.type] += '<div class="double-history__col-header-bet__user">\
                                            <div class="double-history__col-header-bet__photo">\
                                                <img src="' + bet.avatar + '" class="avatar">\
                                            </div>\
                                            <div class="double-history__col-header-bet__row">\
                                                <div class="double-history__col-header-bet__username">' + bet.username + '</div>\
                                                <div class="double-history__col-header-bet__amount">' + bet.value + '</div>\
                                            </div>\
                                        </div>';
            }
        }

        for(var color in bet_stack) 
        {
            user_bet_stack[color] += bet_stack[color];
            if (user_bet_stack[color]){
                $('.double-history__col-body_'+ color).html(user_bet_stack[color]);
                $('.double-history__col-bets__value_' + color).text(prices[color]);
            }
        }
        

        $('.tooltip').tooltipster({
            side: 'bottom',
            theme: 'tooltipster-borderless'
        });
    }

    double.addBet = function() {
        // if (!validateField('#amount')){
		// 	return;
		// }
        $(this).prop('disabled', true);
        var amount = parseInt($('#amount').val());
        $.ajax({
            url : '/roulette/addBet',
            type : 'post',
            data : {
                bet  : amount,
                type    : $(this).attr('data-bet-type')
            },
            success : (res) => {
                if (!res)  {
                    $(this).prop('disabled', false);
                    return;
                }
                // if (res.success == true) updateBalanceAnimated('#money_mob', res.balance);
                $.notify({
                    position : 'bottom-right',
                    type: res.success ? 'success' : 'error',
                    message: res.msg
                });
                $(this).prop('disabled', false);
            },
            error : (err) => {
                $.notify({
                    position : 'bottom-right',
                    type: 'error',
                    message: 'Ошибка при отправке данных на сервер'
                });
                $(this).prop('disabled', false);
            }
        });
    }

    $('.betButton').click(double.addBet);

});