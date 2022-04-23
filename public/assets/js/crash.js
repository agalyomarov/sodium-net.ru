document.render = [];
$(document).ready(function() {
    // initSlider('.modal_crash');
    // var fairCarousel = initFairSlider('.fair-slider');
    var button_enabled = true;
    new SimpleBar(document.getElementById('hline'), {
    });

    this.chart = null;
    this.socket = io.connect(domain + ':' + mainPort);

    this.counter = 0;
    this.interpolate_coefs = [
        1.37536023,
        1.90518962,
        2.66248257,
        3.78019802,
        5.48563218,
        8.22844828,
        13.0753425,
        23.2804878,
        54.5428571
    ];

    this.hide_crash = $.cookie('hide-crash') !== undefined;

    $('#crash-switch').prop('checked', !this.hide_crash);

    setTimeout(() => {
        $('.crash-switch').removeClass('hide');
    }, 10);
    
    this.hidePlot = () => {
        this.hide_crash = true;
        if (this.chart != null){
            this.chart.data.datasets[0].data.length = 0;
            this.chart.update();
        }
    }

    this.revealPlot = () => {
        this.hide_crash = false;
    }
    
    // this.resetPlot = () => {
    //     if (this.chart != null){
    //         this.chart.destroy();
    //     }
    //     var ctx = document.getElementById('chart').getContext('2d');
    //     this.chart = new Chart(ctx, {
    //         type: 'line',
    //         data: {
    //             labels: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    //             datasets: [{
    //                 backgroundColor: 'rgba(255, 194, 0, 0.7)',
    //                 borderColor: '#fff',
    //                 borderWidth: 5,
    //                 data: [],
    //             }]
    //         },
    //         options: {
    //             tooltips: {
    //                 enabled: false
    //             }, 
    //             responsive: true,
    //             maintainAspectRatio: false,
    //             legend: {
    //                 display: false,
    //             },
    //             animation: {
    //                 duration: 500,
    //             },
    //             scales: {
    //                 yAxes: [{
    //                     gridLines: {
    //                         color: "#313131",
    //                         drawTicks: false,
    //                         borderDash: [4, 2]
    //                     },
    //                     ticks: {
    //                         padding: 10,
    //                         fontColor: "#fff",
    //                         fontSize: 12,
    //                         min: 1,
    //                         max: 2,
    //                         maxTicksLimit: 7,
    //                         callback: function(tick, index, ticks) {
    //                             return tick.toFixed(1);
    //                         }
    //                     }
    //                 }],
    //                 xAxes: [{
    //                     display: false
    //                 }]
    //             },
    //             elements: {
    //                 point:{
    //                     radius: 0
    //                 }
    //             }
    //         }
    //     });
        
    // }

    this.resetPlot = () => {
        $.plot($('#chart'), [[0,1]], {
            // xaxis : {
            //     max : Math.max(1, 5000/2000),
            //     min : 1,
            //     color: "rgba(122, 108, 243, 0.45)",
            //     ticks : {
            //         show : false
            //     }
            // },
            // yaxis : {
            //     max : Math.max(1.003004504503377*1, 2),
            //     min : 1,
            //     color: "rgba(122, 108, 243, 0.45)"
            // },
            series: {
                lines: { fill: true},
            },
            grid: {
                borderColor: "#FFCC00",
                borderWidth: {
                    top: 0,
                    right: 0,
                    left: 2,
                    bottom: 2
                }
            },
            colors : ['#fff']
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
        $('.chart-info').text(res.value + ' c.');
        if (game_active){
            $('.crash-play').prop('disabled', true);
        }
        else{
            if (button_enabled)
                $('.crash-play').prop('disabled', false);
        }
    }

    this.publishBet = (res) => {
        let html = '';
        for(var i in res.bets)
        {
            let bet = res.bets[i];
            html += '<div class="crash-bet">\
                        <div class="crash-bet__user-wrapper">\
                            <div class="crash-bet__image-wrapper">\
                                <img src="' + bet.user.avatar + '" class="crash-bet__image" style="border-color: #333" alt="">\
                            </div>\
                            <div class="crash-bet__username-wrapper">\
                                <div class="crash-bet__username">' + bet.user.username + '</div>\
                            </div>\
                        </div>\
                        <div class="crash-bet__values">\
                            <div class="crash-bet__value crash-bet__sum"><span class="myicon-coins"></span> ' + bet.price + '</div>';
            

            if (bet.status == 1) {
                html += '<div class="crash-bet__value crash-bet__coeff crash-bet__value crash-bet__coeff_won">' + bet.withdraw + 'x</div>\
                        <div class="crash-bet__value crash-bet__win"><span class="myicon-coins"></span> ' + bet.won + '</div>';
            }
            if (bet.status == 0){
                html += '<div class="crash-bet__value crash-bet__coeff">В игре</div>\
                        <div class="crash-bet__value crash-bet__win crash-bet__win_hidden"><span class="myicon-coins"></span></div>';
            }   
            html += '</div></div>';
        }
        $('.crash-bets').html(html);
        $('.tooltip').tooltipster({
			side: 'bottom',
			theme: 'tooltipster-borderless'
		});
    }

    this.reset = (res) => {
        $('.crash-bets').html('');
        $('.chart-info').css('color', '#fff').text('Загрузка');
        $('.crash-play').prop('disabled', false).text('Играть');
        game_active = false;
        isCashout = undefined;
        withdraw = undefined;
        bet = undefined;
        hide_win_window();
        unlockControls();
        this.resetPlot();
        // displayFairDataOnModal(res.server_seed, res.client_seed, res.salt, res.current_server_seed, res.current_client_seed, fairCarousel);
        let html = '';
        for(var i in res.history) html += '<a target="_blank" href="/fair/' + res.hash + '"><div class="crash-item" style="background-color: '+res.history[i].color+'; border-color: '+res.history[i].color+';">'+res.history[i].multiplier.toFixed(2)+'x</div></a>';
        $('.double-history-line').html(html);
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
            
            if (game_active){
                $('.crash-play').prop('disabled', true).text('Забрать');
            }
        } else {
            if (game_active && bet) {
                console.log(bet + ' 0')
                var to_withdraw = parseInt(parseInt(bet) * parseFloat(res.float.toFixed(2)));
                $('.crash-play').text('Забрать ' + to_withdraw);
        
                if (res.float < 1.02) {
                    $('.crash-play').prop('disabled', true);
                }
                else{
                    if (button_enabled)
                        $('.crash-play').prop('disabled', false);
                }
            }
            else{
                $('.crash-play').prop('disabled', true);
            }

            if(game_active && res.float >= withdraw) 
            {
                game_active = false;
                isCashout = true;
                cashout();
            }
        }
    }
    
    $('.crash-graph-switch').click(() => {
        if(!this.hide_crash) {
            this.hide_crash = true;
            $('.crash-graph-switch span').removeClass().addClass('myicon-invisible');
            $.cookie('hide-crash', 'true', {
                expires: 7,
                path: '/'
            });
            this.hidePlot();
        }   
        else{
            this.hide_crash = false;
            $('.crash-graph-switch span').removeClass().addClass('myicon-visible');
            $.removeCookie('hide-crash', {
                path: '/'
            });
            this.revealPlot();
        }
    });

    function createBet(){
        $('.crash-play').prop('disabled', true);
        button_enabled = false;
        // if (!validateField('#amount') || !validateFieldFloat('#crash-auto')) {
        //     $('.crash-play').prop('disabled', false);
        //     return;
        // }
        var wt      = parseFloat($('#crash-auto').val());
        var amount  = parseInt($('#amount').val());
        $.ajax({
            url : '/crash/addBet',
            type : 'post',
            data : {
                bet : amount,
                withdraw : wt
            },
            success : function(res) {
                $.notify({
                    position : 'bottom-right',
                    type: res.success ? 'success' : 'error',
                    message: res.msg
                });
                if(res.success == true) 
                {
                    updateBalanceAnimated('#money_mob', res.balance);
                    lockControls();

                    bet = res.bet;
                    withdraw = wt;
                    isCashout = false;
                    game_active = true;
                    $('.crash-play').text('Забрать ' + bet);
                }
                $('.crash-play').prop('disabled', false);
                button_enabled = true;
            }, 
            error : function() {
                $.notify({
                    position : 'bottom-right',
                    type: 'error',
                    message: 'Произошла ошибка при отправке данных'
                });
                $('.crash-play').prop('disabled', false);
                button_enabled = true;
            }
        });
    }

    function cashout(){
        $('.crash-play').prop('disabled', true);
        button_enabled = false;
        $.ajax({
            url : '/crash/cashout',
            type : 'post',
            success : function(res) {
                if(res.success == true) 
                {
                    $('.crash-play').text('Играть');
                    updateBalanceAnimated('#money_mob', res.balance);
                    show_win_window(res.float.toFixed(2), res.won_sum);
                    game_active = false;
                    isCashout = true;
                    unlockControls();
                }
                else{
                    $.notify({
                        position : 'bottom-right',
                        type: res.success ? 'success' : 'error',
                        message: res.msg
                    });
                }
                $('.crash-play').prop('disabled', false);
                button_enabled = true;
            },
            error : function(res) {
                $.notify({
                    position : 'bottom-right',
                    type: 'error',
                    message: 'Произошла ошибка при отправке данных'
                });
                $('.crash-play').prop('disabled', false);
                button_enabled = true;
            }
        })
    }

    function unlockControls(){
        $('.game-sidebar__input-helper-action').prop('disabled', false);
        $('.game-sidebar__set-action').prop('disabled', false); 
        $('#crash-auto').prop('disabled', false);
        $('#amount').prop('disabled', false);
        $('.game-area_dice-input-sfxtxt').removeClass('opacity8');
    }

    function lockControls(){
        $('.game-sidebar__input-helper-action').prop('disabled', true);
        $('.game-sidebar__set-action').prop('disabled', true); 
        $('#crash-auto').prop('disabled', true);
        $('#amount').prop('disabled', true);
        $('.game-area_dice-input-sfxtxt').addClass('opacity8');
    }

    function show_win_window(coeff, won_sum){
        $('.outcome-window__coeff').text('x' + coeff);
        $('.outcome-window_won__sum').text(won_sum);
        $('.outcome-window').fadeIn(200);
    }
    
    function hide_win_window(){
        $('.outcome-window').fadeOut(200);
    }

    $('.crash-play').on('click', function() {
        if (!game_active){
            createBet();
        }
        else{
            cashout();
        }
    });

});