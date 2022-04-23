$(document).ready(() => {
    window.socket = io.connect(':8080');
    window.double = {};

    socket.on('roulette', (res) => {
        if(res.type == 'timer') $('#rez-numbr').text((res.time > 9) ? res.time : '0'+res.time); if($('#soundController').val() == 'on') if(res.time <= 5) audio('/sounds/timer-tick-quiet.mp3', 0.2);
        if(res.type == 'slider')
        {
            $('.double-timer').hide();
            $('#reletteact').css({
                'transition' : 'transform '+res.slider.time+'ms ease',
                'transform' : 'rotate('+res.slider.rotate+'deg)'
            });
            if($('#soundController').val() == 'on') audio('/sounds/double-start.wav', 0.3);
            setTimeout(() => {
                if(res.slider.color == 'red') {
                    $('.double-rel').show().css({background: '#ffc200'}).text(res.slider.num);
                } else if(res.slider.color == 'green') {
                    $('.double-rel').show().css({background: '#01c236'}).text(res.slider.num);
                } else {
                    $('.double-rel').show().css({background: '#242323'}).text(res.slider.num);
                }
                if($('#soundController').val() == 'on') audio('/sounds/double-end.wav', 0.3);
            }, res.slider.time);
        }
        if(res.type == 'newGame')
        {
            if($('#soundController').val() == 'on') audio('/sounds/jackpot-new-game.wav', 0.2);
            $('#hash').text(res.hash);
            $('.double-last').prepend('<a href="/fair/'+ res.history.hash +'" class="double-last-i '+res.history.color+'">'+res.history.num+'</a>')
            $('.rates-top .bet').text(0);
            $('#roundId').text(res.id);
            $('.bets').slideUp(200, () => {
                $('.bets').html('');
                $('.bets').show();
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
            $('.bet-amount').text('0');
        }
        if(res.type == 'bets') return double.makeBets(res.bets, res.prices);
    });

    double.makeBets = function(bets, prices)
    {
        if($('#soundController').val() == 'on') audio('/sounds/bet.mp3', 0.3);
        var colors = [];
        for(var i in bets)
        {
            let bet = bets[i];
            if(typeof colors[bet.type] == 'undefined') colors[bet.type] = '';
            colors[bet.type] += '<div class="rates-i" data-userid="'+bet.user_id+'"><div class="rates-ava"><img class="avatar" src="'+bet.avatar+'"><img class="tooltip rank tooltipstered" title="' + bet.rank_name + '" src="/assets/images/ranks/' + bet.rank_order + '.svg" alt=""></div><div class="hidden"><div class="rates-login"><b class="ell">'+bet.username+'</b></div><div class="rates-rub">'+bet.value+'</div></div></div>'
        }

        for(var color in colors)
        {
            $('.rates-content_'+ color).html(colors[color]);
            $('#bank_' + color).text((typeof prices[color] == 'undefined') ? '0' : prices[color]);
        }
        $('.tooltip').tooltipster({
            side: 'bottom',
            theme: 'tooltipster-borderless'
        });
    }

    double.getMyBet = function(type, callback) {
        $.ajax({
            url : '/roulette/getBet',
            type : 'post',
            data : {
                type : type
            },
            success : (res) => {
                callback(res);
            },
            error : (err) => {
                console.log(err.responseText);
                callback(0);
            }
        });
    }

    double.addBet = function() {
        let value = parseInt($('#amount').val());
        if(isNaN(value)) return $.notify({
            position : 'bottom-right',
            type: 'error',
            message: 'Неверно введена сумма ставки'
        });

        $.ajax({
            url : '/roulette/addBet',
            type : 'post',
            data : {
                bet : value,
                type : $(this).attr('data-bet-type')
            },
            success : (res) => {
                $.notify({
                    position : 'bottom-right',
                    type: (res.success) ? 'success' : 'error',
                    message: res.msg
                });
                if (res.success){
                    var betTxt = $(this).children('.bet-amount').text();
                    betTxt = (parseInt(betTxt) + parseInt($('#amount').val())).toString();
                    $(this).children('.bet-amount').text(betTxt);
                    $('#amount').val('');
                }
            },
            error : (err) => {

                if (err.status == 401){
                    $.notify({
                        position : 'bottom-right',
                        type: 'error',
                        message: 'Только авторизованные пользователи могут ставить!'
                    });
                }
                else{
                    $.notify({
                        position : 'bottom-right',
                        type: 'error',
                        message: 'Ошибка при отправке данных на сервер'
                    });
                }

            }
        });
    }

    $('.betButton').click(double.addBet);

});