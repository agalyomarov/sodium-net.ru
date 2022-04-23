document.render = [];
$(document).ready(function() {

    this.socket = io.connect(':8080');


    this.resetPlot = () => {
        $.plot($('#chart'), [[0,1]], {
            xaxis : {
                max : Math.max(1, 5000/2000),
                min : 1,
                color: "rgba(122, 108, 243, 0.45)",
                ticks : {
                    show : false
                }
            },
            yaxis : {
                max : Math.max(1.003004504503377*1, 2),
                min : 1,
                color: "rgba(122, 108, 243, 0.45)"
            },
            series: {
                lines: { fill: true},
            },
            grid: {
                borderColor: "#7a6cf3",
                borderWidth: {
                    top: 0,
                    right: 0,
                    left: 2,
                    bottom: 2
                }
            },
            colors : ['#99aed7']
        });
    }

    this.resetPlot();

    this.socket.on('crash', async res => {
        if(res.type == 'bet') this.publishBet(res);
        if(res.type == 'timer') this.publishTime(res);
        if(res.type == 'slider') this.parseSlider(res);
        if(res.type == 'game') this.reset(res);
    });

    this.publishTime = (res) => {
        $('.chart-info').text('Start in ' + res.value + 's.');
    }

    this.publishBet = (res) => {
        let html = '';
        for(var i in res.bets)
        {
            let bet = res.bets[i];
           html += '<li><div class="user"><div class="ava"><img class="avatar" src="'+bet.user.avatar+'" alt=""></div><div class="info"><div class="points"><span class="text-clip">'+bet.user.username+': </span> <span class="bet-sum">'+bet.price+' <i class="myicon-coins"></i></span></div></div>'
            if(bet.status == 1) html += '<div class="detail"><div class="percent" style="color:'+bet.color+';">'+bet.withdraw+'x</div><div class="tickets">'+bet.won+' <i class="myicon-coins"></i></div></div>';
            if(bet.status == 0) html += '<div class="detail"><div class="wait"><i class="far fa-clock"></i></div></div>';
            html += '</div></li>';
        }
        $('#bets').html(html);
        $('.players_bet').text(res.bets.length);
        $('.players_sum').text(res.price);
    }

    this.reset = (res) => {
        $('#bets').html('');
        $('.players_bet').text(0);
        $('.players_sum').text(0);
        $('.chart-info').css('color', '#99aed7').text('Please wait...');
        this.resetButton(false);
        this.resetPlot();
        $('#hash_small').text(res.hash);
        let html = '';
        for(var i in res.history) html += '<a href="/fair/'+res.history[i].secret+'"><div class="item" style="color: '+res.history[i].color+'; border-color: '+res.history[i].color+';">'+res.history[i].multiplier.toFixed(2)+'x</div></a>';
        $('.history').html(html);
        $('.withdraw-button').css('opacity', 1)
    }

    this.parseSlider = (res) => {
        $.plot($('#chart'), [res.data], res.options);
        $('.chart-info').text(((res.crashed) ? 'Crashed at ' : '') + 'x' + res.float.toFixed(2));
        if(res.crashed)
        {
            $('.chart-info').css({
                'transition' : 'color 200ms ease',
                'color' : '#aa3737'
            });
            $('.withdraw-button').text('Вывести').css('opacity', 0.5);
        } else {
            if(!window.isCashout && window.withdraw > 0) $('.withdraw-button').text('Вывести ' + parseInt(window.bet*parseFloat(res.float.toFixed(2))));
            if(res.float >= window.withdraw && !window.isCashout)
            {
                window.isCashout = true;
                $('.withdraw-button').click();
            }
            $('.chart-info').css({
                'transition' : 'color 200ms ease',
                'color' : res.color
            });
        }
    }

    this.notify = (r) => {
        $.notify({
            position : 'bottom-left',
            type: (r.success) ? 'success' : 'error',
            message: r.msg
        });
    }

    this.resetButton = result => {
        if(result)
        {
            $('.bet-input .value').hide();
            $('.bet-input .autoout').hide();
            $('.bet-input .upper').hide();
            $('.bet-input .bet-button').hide();
            $('.bet-input .withdraw-button').show();
        } else {
            $('.bet-input .withdraw-button').hide();
            $('.bet-input .value').show();
            $('.bet-input .autoout').show();
            $('.bet-input .upper').show();
            $('.bet-input .bet-button').show();
        }
    }

    $('.bet-button').click(() => {
        $.ajax({
            url : '/crash/addBet',
            type : 'post',
            data : {
                bet : parseInt($('.bet-amount').val()) || 0,
                withdraw : parseFloat($('.bet-cashout').val()) || 0
            },
            success : res => {
                this.notify(res);
                if(res.success)
                {
                    $('.bet-amount').val('')
                    window.bet = res.bet;
                    $('.withdraw-button').text('Вывести ' + window.bet);
                    this.resetButton(true);
                    window.withdraw = parseFloat($('.bet-cashout').val()) || 0;
                    window.isCashout = false;
                }
            },
            error : err => {
                console.log('New error');
                console.log(err.responseText);
            }
        });
    });

    $('.withdraw-button').click(() => {
        // window.isCashout = true;
        $.ajax({
            url : '/crash/cashout',
            type : 'post',
            success : res => {
                this.notify(res);
                if(res.success)
                {
                    this.resetButton(false);
                    $('.withdraw-button').css('opacity', 0.5).text('Вывести');
                }
            },
            error : err => {
                this.notify({
                    success : false,
                    msg : 'Что-то пошло не так...'
                });
                console.log(err.responseText);
            }
        })
    });

    $('a[data-method="last"]').click(() => {
        $.ajax({
            url : '/crash/last',
            type : 'post',
            success : b => $('.bet-amount').val(b),
            error : e => $('.bet-amount').val(0)
        });
    });
});