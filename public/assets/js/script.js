$(document).ready(function(e) {
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    }

    // $('#crash-auto').on('input', (event) => {
    //     $(event.target).val(parseFloat($(event.target).val()));
    //     setTimeout(() => {
            
    //     }, 1500);
    // });
    
    function closePopup(){
        $('.modal-window, .modal-backdrop').removeClass('active');
        $('body').removeClass('modal-active');
    }
    
    var hideChatIfDontFit = () => {
        if( document.documentElement.clientWidth <= 780 ) 
        {
            if (!$('.fixed-chat').hasClass('hide'))
                $('.fixed-chat').addClass('hide');
        } else {
            if ($('.fixed-chat').hasClass('hide'))
                $('.fixed-chat').removeClass('hide');
        }
    }
    window.onresize = function( ) {
        hideChatIfDontFit();
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var close = document.querySelectorAll('[data-close="alert"]');
    for (var i = 0; i < close.length; i++) {
        close[i].onclick = function(){
            var div = this.parentElement;
            div.style.opacity = '0';
            setTimeout(function(){div.style.display = 'none';}, 400);
        }
    }
    if($('#soundController').val() == 'on') {
        $('#settings-change-sound').attr('checked', true);
    }
    if($('#themeController').val() == 'dark') {
        $('#settings-change-theme').attr('checked', true);
    }
    $('#settings-change-sound').click(function() {
        $.ajax({
            url : '/sound',
            type : 'post',
            success : function(data) {
                console.log(data);
                $('#soundController').val(data);
                if(data == 'off') $('#settings-change-sound').attr('checked', false);
                else $('#settings-change-sound').attr('checked', true);
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
    $('#settings-change-theme').click(function() {
        $.ajax({
            url : '/theme',
            type : 'post',
            success : function(data) {
                $('#themeController').val(data);
                if(data == 'dark') {
                    $('#settings-change-theme').attr('checked', true);
                    $('#css_theme').attr('href', '/assets/css/style_dark.css');
                } else {
                    $('#settings-change-theme').attr('checked', false);
                    $('#css_theme').attr('href', '/assets/css/style.css');
                }
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
     $('.tooltip').tooltipster({
        side: 'bottom',
        theme: 'tooltipster-borderless'
    });

    $('.tooltip-game').tooltipster({
        side: 'right',
        theme: 'tooltipster-yellow',
        delay: 50
    });
    $('.chances').kinetic({
        filterTarget: function(target, e){
            if (!/down|start/.test(e.type)){
                return !(/area|a|input/i.test(target.tagName));
            }
        }
    });
    $('#accordion').accordion({
        heightStyle: 'content'
    });

    $('.btn-toggle-menu').click(function(e) {
        $('.fixed-nav, .main-width').css({transition: 'all .5s ease'});
        if ($(this).is('.active')) {
            $('.main-width').removeClass('hide-menu');
            $('.fixed-nav .buttons .dep').html('Пополнить');
            $('.fixed-nav .buttons .with').html('Вывод');
            $('.fixed-nav, .btn-toggle-menu').removeClass('active');
            $.removeCookie('fixed-nav', {
                path: '/'
            });
        } else {
            $('.main-width').addClass('hide-menu');
            $('.fixed-nav .buttons .dep').html('+');
            $('.fixed-nav .buttons .with').html('-');
            $('.fixed-nav, .btn-toggle-menu').addClass('active');
            $.cookie('fixed-nav', 'true', {
                expires: 7,
                path: '/'
            });
        }
        return false;
    });
   function openChat(){
        $('body').addClass('freeze');
        $('.fixed-chat, .main-width').css({transition: 'all .5s ease'});
        $('.fixed-chat, .main-width').removeClass('hide');
        $.cookie('fixed-chat', 'true', {
            expires: 7,
            path: '/'
        });
    }

    function closeChat(){
        $('body').removeClass('freeze');
        $('.fixed-chat, .main-width').css({transition: 'all .5s ease'});
        $('.fixed-chat, .main-width').addClass('hide');
        $.removeCookie('fixed-chat', {
            path: '/'
        });
    }

    function chatOpened(){
        return !$('.fixed-chat').is('.hide');
    }

    $('.btn-toggle3').click(function(e) {
        if (!chatOpened()) {
            openChat();
        } else {
            closeChat();
        }
        return false;
    });


    $(document).click(function(e)
    {
        var container = $(".mobile-menu__submenu");
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            container.slideUp(200);
            container.removeClass('active');
        }
    });

    $('.open-submenu').click(function(){
        var sbm = '.' + $(this).data('submenu');
        $('.mobile-menu__submenu.active').not(sbm).hide();
        $('.mobile-menu__submenu.active').not(sbm).removeClass('active');
        $(sbm).stop();
        $(sbm).slideToggle(200);
        $(sbm).toggleClass('active');
        return false;
    });
    if(!isMobile.any()) {
        if($.cookie('fixed-nav')) {
            $('.main-width').addClass('hide-menu');
            $('.fixed-nav .buttons .dep').html('+');
            $('.fixed-nav .buttons .with').html('-');
            $('.fixed-nav, .btn-toggle-menu').addClass('active');
        }
        if ($.cookie('fixed-chat')) {
            $('.fixed-chat, .main-width').addClass('hide');
        }
    }
    $('.scroller').slimScroll({
        size: '5px',
        start: 'bottom',
        height: 'auto'
    });
    $(window).resize(function(e) {
        $('.scroller').slimScroll({'destroy':true});
        $('.scroller').slimScroll({
            size: '5px',
            start: 'bottom',
            height: 'auto'
        });
    });
    $(window).resize();
    $('.close').click(function(e) {
        $('.popup, .overlay, body').removeClass('active');
        return false;
    });
    $('.overlay').click(function(e) {
        var target = e.target || e.srcElement;
        if(!target.className.search('overlay')) {
            $('.overlay, .popup, body').removeClass('active');
        }
    });
    $('[rel=popup]').click(function(e) {
        showPopup($(this).attr('data-popup'));
        return false
    });
      $('.list-pay .item').click(function(e) {
        if(!$(this).is('.active')) {
            $(this).parent().find('.item').removeClass('active');
            $(this).addClass('active');
            checkSystem();
            calcSum();
        }
    });

    $('#value').on('change keydown paste input', function() {
        calcSum();
    });

    $('#value').on('change keydown paste input', function() {
        calcSum();
    });

    $('#value').focusout(function() {
        if($('.list-pay .active').data('type') == 'qiwi') {
            var min = 1050;
            var perc = 4;
            var com = 1;
            $('#com').html(perc + '% + ' + com + 'руб.');
        } else if($('.list-pay .active').data('type') == 'yandex') {
            var min = 100;
            var perc = 2;
            var com = 0;
            $('#com').html(perc + '%');
        } else if($('.list-pay .active').data('type') == 'visa') {
            var min = 10940;
            var perc = 4;
            var com = 50;
            $('#com').html(perc + '% + ' + com + 'руб.');
        }
        var val = $('#value').val();
        if (isNaN(val) || !val || val < min) {
            val = min;
            $('#value').val(val);
        }
        var comission = Math.round((val-(val/100*perc+com*10))/10);
        if(comission <= 1) comission = 0;
        $('.to_get_rubles').val(comission);
    });

    calcSum();

  $('#value').on('change keydown paste input', function() {
        calcSum();
    });
    function calcSum() {
        if($('.list-pay .active').data('type') == 'qiwi') {
            var perc = 4;
            var com = 1;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '% + ' + com + 'руб.');
        } else if($('.list-pay .active').data('type') == 'yandex') {
            var perc = 0;
            var com = 0;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '%');
        } else if($('.list-pay .active').data('type') == 'webmoney') {
            var perc = 6;
            var com = 0;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '%');
        } else if($('.list-pay .active').data('type') == 'visa') {
            var perc = 4;
            var com = 50;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
            }
            $('#com').html(perc + '% + ' + com + 'руб.');
        }
        var val = $('#value').val();
        var comission = Math.round((val-(val/100*perc+com*10))/10);
        if(!val) comission = 0;
        if(comission <= 1) comission = 0;
        $('#valwithcom').html(comission + ' руб.');
    }


    $('#chh').click(function() {
        $('#chh').attr('checked', 'checked');
        if($(this).prop('checked') == true){
            $('#withdraw').removeAttr('disabled');
        } else {
            $('#withdraw').attr('disabled', 'false');
            $('#chh').removeAttr('checked');
        }
    });


    function checkSystem() {
        if($('.list-pay .active').data('type') == 'qiwi') {
            var perc = 4;
            var com = 1;
            var val = 1000;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
                var val = 1000;
            }
            var comission = val+(val/100*perc)+(com*10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'От ' + comission + '');
            $('#value').val(comission);
            $('#wallet').attr('placeholder', '7900xxxxxxx');
            $('#com').html(perc + '% + ' + com + 'руб.');
        } else if($('.list-pay .active').data('type') == 'yandex') {
            var perc = 0;
            var com = 0;
            var val = 100;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
                var val = 100;
            }
            var comission = val+(val/100*perc)+(com*10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'От ' + comission + '');
            $('#value').val(comission);
            $('#wallet').attr('placeholder', '41001хххххххххх');
            $('#com').html(perc + '%');
        } else if($('.list-pay .active').data('type') == 'webmoney') {
            var perc = 6;
            var com = 0;
            var val = 100;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
                var val = 100;
            }
            var comission = val+(val/100*perc)+(com*10);
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'От ' + comission + '');
            $('#value').val(comission);
            $('#wallet').attr('placeholder', 'R536xxxxxxxxx');
            $('#com').html(perc + '%');
        } else if($('.list-pay .active').data('type') == 'visa') {
            var perc = 4;
            var com = 50;
            var val = 10000;
            if(youtuber == '1') {
                var perc = 0;
                var com = 0;
                var val = 10000;
            }
            var comission = 10940;
            $('#min_wid').html(comission);
            $('#value').attr('placeholder', 'От ' + comission + '');
            $('#value').val(comission);
            $('#wallet').attr('placeholder', '4700xxxxxxxxxxxx');
            $('#com').html(perc + '% + ' + com + 'руб.');
        }
    }

    $('#wallet').keydown(function(event) {
        if (event.shiftKey === true) return false;
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
           (event.keyCode == 65 && event.ctrlKey === true) ||
           (event.keyCode >= 35 && event.keyCode <= 39)) {
                 return;
        } else {
            if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) && (event.keyCode < 65 || event.keyCode > 90 )) {
                event.preventDefault();
            }
        }
    });
    $('#withdraw').click(function(){
        var system = $('.list-pay .active').attr('data-type');
        var value = $('#value').val();
        var wallet = $('#wallet').val();
        if(!$('#chh').attr('checked')) {
            $.notify({
                position : 'top-right',
                type: 'error',
                message: 'Вы не подтвердили правильность введенных даных'
            });
            return false;
        }
        $.ajax({
            url : '/withdraw',
            type : 'post',
            data : {
                
            },
            success : function(data) {
                $('.popup, .overlay, body').removeClass('active');
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                return false;
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
    $('.ref .makePromo').click(function(){
        $.ajax({
            url: '/createPromo',
            type: 'post',
            data: {
                name: $('#createpromo').val(),
                reward: $('#reward').val(),
                count: $('#count').val()
            },
            success: function(data) {
                var html = '';
                var html2 = '';
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                if(data.success) {
                    html += '<tr>';
                    html += '<td>'+ $('#createpromo').val() +'</td>';
                    html += '<td>'+ $('#count').val() +'</td>';
                    html += '<td>'+ $('#reward').val() +' <i class="fas fa-coins"></i></td>';
                    html += '</tr>';
                    html2 += '<tr data-id="'+ $('#createpromo').val() +'">';
                    html2 += '<td>'+ $('#createpromo').val() +'</td>';
                    html2 += '<td>'+ $('#count').val() +'</td>';
                    html2 += '<td>'+ $('#reward').val() +' <i class="fas fa-coins"></i></td>';
                    html2 += '<td><a class="btn" href="javascript:sharePromo(`'+ $('#createpromo').val() +'`)">Поделиться в чате</a></td>';
                    html2 += '</tr>';
                    $('#promo_list').append(html);
                    $('#promoshare').append(html2);
                }
                $('#reward').val('');
                $('#count').val('');
            }
        });
    });
    $(".btn-emoji").on("mouseenter", function(b) {
        $('.chat-smiles').fadeIn(200);
    }).on("mouseleave", function(b) {
        $('.chat-smiles').fadeOut(400);
    });
    $(".chat-smiles").on("mouseenter", function(b) {
        $('.chat-smiles').fadeIn(0);
    }).on("mouseleave", function(b) {
        $('.chat-smiles').fadeOut(400);
    });
    $('.chat-smile').click(function() {
        $('.chat-input').val($('.chat-input').val() + ' ' + $(this).text());
    });
    $('.cont-a .rooms .room:first').addClass('active');
    $('.cont-a .historyTable:first').addClass('active');
    $('.cont-a .rooms .room').click(function(e) {
        if(!$(this).is('.active')) {
            $('.cont-a .rooms .room, .historyTable').removeClass('active');
            $(this).addClass('active');
            $('.historyTable:eq('+$(this).index()+')').addClass('active');
        }
    });
    $('.cont-b .rooms .room:first').addClass('active');
    $('.cont-b .top:first').addClass('active');
    $('.cont-b .rooms .room').click(function(e) {
        if(!$(this).is('.active')) {
            $('.cont-b .rooms .room, .top').removeClass('active');
            $(this).addClass('active');
            $('.top:eq('+$(this).index()+')').addClass('active');
        }
    });
    var socket = io.connect(':8443');
    $('.scroller').scrollTop(1e7);
    socket.on('message', function(msg) {
        if(USER_ID == msg.user) {
            $.notify({
                position : 'top-right',
                type: msg.type,
                message: msg.msg
            });
        }
    });
    socket.on('giftTimer', function(data){
        $("#gifttimer").text(data);
        // console.log(data);
    });
    socket.on('updateBalance', function(data) {
        if(USER_ID == data.id) $('#money_mob').text(data.balance);
        if(USER_ID == data.id) $('#money').val(data.balance);
    });
    socket.on('updateBank', function(data) {
        $('#'+ data.game +'_bank').each(function () {
            $(this).prop('Counter', $('#'+ data.game +'_bank').text()).animate({
                Counter: data.sum
            }, {
                duration: 1000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    });
    socket.on('ban_message', function(data) {
        if(USER_ID == data.user_id) {
            if(data.ban == 1) $('.send-form').prepend('<div class="banchat"><div class="title">Чат заблокирован!</div><div class="btn"><a href="javascript:unbanMe()">Разблокировать ( -500 <i class="fas fa-coins"></i> )</a></div></div>');
            if(data.ban == 2) $('.banchat').remove();
        }
    });
    socket.on('online', function(data) {
        $('.on').text(data);
    });
    socket.on('chat', function (data) {
        msg = JSON.parse(data);
        var chat = $('.messages');
        /* ban panel */
        if(admin == 1 || moder == 1) {
            var ban = 'onclick="var u = $(this); $(\'.chat-input\').val(\'/ban \' + u.text() + \' \'); return false;"';
            var panel = '<a class="delete" onclick="chatdelet('+ msg.time2 +')"><i class="far fa-times-circle"></i></a>';
        } else {
            var ban = '';
            var panel = '';
        }
        var name = msg.username;
        if(msg.admin) {
            name = '<span style="color:#ff5722;">[Администратор]</span>';
        }
        if(msg.moder) {
            name = '<span style="color:#4caf50;">[Модератор]</span> ' + msg.username;
        }
        if(msg.youtuber) {
            name = '<span style="color:#ffc107;">[YouTube`r]</span> ' + msg.username;
        }
        var messages = msg.messages;

        chat.find('.scroller').append(
            '<div class="item" id="chatm_' + msg.time2 + '">' +
                '<div class="sub-heading clear">' +
                    '<div class="avatar"><img class="avatar-img" src="' + msg.avatar + '" alt=""></div>' +
                    '<div class="name" onclick="var u = $(this); $(\'.chat-input\').val(u.text() + \', \'); return false;">' + name + '</div>' +
                    '<div class="date"><span '+ ban +'>' + msg.user_id + '</span> ' + panel +'</div>' +
                '</div>' +
                '<div class="text">' + messages + '</div>' +
            '</div>');
        $('.scroller').scrollTop(1e7);
        if($('.fixed-chat .item').length >= 20) $('.fixed-chat .item:nth-child(1)').remove();
    });
    socket.on('chatdel', function (data) {
        info = JSON.parse(data);
        $('#chatm_' + info.time2).remove();
    });
    socket.on('clear', function (data) {
        $('.scroller').html('');
    });
    socket.on('bonus', function (data) {
        if(USER_ID == data.user_id) {
            var line = '';
            for(var i = 0; i < data.line.length; i++) line += '<li><div class="sum">' + data.line[i].sum + '</div><div class="background" style="background: #' + data.line[i].color + '"></div><div class="bottom" style="background: #' + data.line[i].color + '">'+ declOfNum(data.line[i].sum, ['монета','монеты','монет'])+'</div></li>';
            $('#bonus_carousel').html(line);
            $('#bonus_carousel').css({
                transform: 'translate3d(-' + data.ml + 'px, 0px, 0px)',
                transition: 7000 + 'ms cubic-bezier(0.32, 0.64, 0.45, 1)'
            });
            setTimeout(function () {
                $('.line .cooldown').show();
            }, 7200);
        }
    });
    $('.chat-input').bind("enterKey", function (e) {
        var input = $(this);
        var msg = input.val();
        if (msg != '') {
            $.post('/chat', {messages: msg}, function (data) {
                if (data) {
                    if (data.status == 'success') {
                        input.val('');
                    } else {
                        input.val('');
                    }
                    $.notify({
                        position : 'top-right',
                        type: data.status,
                        message: data.message
                    });
                }
                else
                    input.val('');
            });
        }
    });
    $('.chat-input').keyup(function (e) {
        if (e.keyCode == 13) {
            $(this).trigger("enterKey");
        }
    });
    $('.btn-send').on('click', function (event) {
        $('.chat-input').trigger("enterKey");
    });
    $('.game-sidebar__input-helper button').on('click', function (event) {
        let value = parseFloat($('#amount').val()) || 0,
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
                value = localStorage.getItem('last');;
                break;
            case 'all' :
                value = all;
                break;
            case 'multiply' :
                value *= thisValue;
                break;
        }

        $('#amount').val(value);
    });
    $('.promoButton').click(function() {
        var code = $('.promoCode').val();
        $.ajax({
            url : '/ref/activate',
            type : 'post',
            data : {
                code : code
            },
            success : function(data) {
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                return false;
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
    $('.game-sidebar__set-action').on('click', function (event) {
        var _val = parseFloat($(this).attr('data-value'));
    
        $('#' + $(this).attr('data-id')).val(_val.toFixed(2));
        $('#' + $(this).attr('data-id')).trigger('input');
    });
    $('.getMoney').click(function() {
        $.ajax({
            url : '/ref/getMoney',
            type : 'post',
            success : function(data) {
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                $('.getMoney').hide();
                $('.moneyRef .to-get').html('Доступно для получения: <span>0</span> <i class="fas fa-coins"></i>');
                return false;
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
       $('.popup-pay .input-sum').on('change keydown paste input', function() {
        calcEnroll();
    });
    $('.suggest-sum__item').click(function(){
        $('.popup-pay .amount-input').val($(this).data('value'));
        calcEnroll();
        return false;
    });
 $('.form-select select').change(function(){
        $('.picked-method .item').removeClass('active');
        $('.picked-method .' + $(this).val()).addClass('active');
        checkSystem();
        calcSum();
    });
    function calcEnroll(){
        var sum = $('.popup-pay .amount-input').val();
        var promo = $('.popup-pay .promo-input').val();

        if (isNaN(sum) || sum < 0){
            $('#to_enroll').text('0');
            $('#to_enroll_raffle').text('0');
            $('.popup-pay .amount-input').val(0);
        }
        else{
            $('#to_enroll_raffle').text(Math.floor(sum));
            sum = sum * 10;
            if (promo.toLowerCase() === 'money-x' && sum >= 1000){
                sum = sum + (sum * 0.2);
            }
            sum = Math.floor(sum);
            $('#to_enroll').text(sum);
        }

    }
    calcEnroll();
    $('.getBonus').click(function() {
        $.ajax({
            url : '/bonus/getBonus',
            type : 'post',
            data : {
                recapcha : $('#g-recaptcha-response').val()
            },
            success : function(data) {
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                grecaptcha.reset();
                return false;
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
    $('.checkHash').click(function() {
        var hash = $('#hash').val();
        $.ajax({
            url : '/fair/check',
            type : 'post',
            data : {
                hash : hash
            },
            success : function(data) {
                if(data.success) {
                    $('#round').val(data.round);
                    $('#number').val(data.number);
                    $('.fair .col').slideDown();
                } else {
                    $('#round').val('');
                    $('#number').val('data.number');
                    $('.fair .col').slideUp();
                }
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                return false;
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
    $('#sumToSend').on('change keydown paste input', function() {
        var sumToSend = $(this).val();
        var total = sumToSend*1.05;
        $('#minusSum').html(total);
    });


    $('.ref #reward, .ref #count').on('change keydown paste input', function() {
        var sum = $('.ref #reward').val();
        var count = $('.ref #count').val() ? $('.ref #count').val() : 1;
        var total = (sum*count)*1.10;
        $('#minusSum').html(Math.floor(total));
    });

    $('.sendButton').click(function(){
        var target = $('.targetID').val();
        var sum = $('.sum').val();
        $('.sendButton').val('Переводим.....');
        $('.sendButton').attr('disabled', 'true');
        $.ajax({
            url : '/payment/send/create',
            type : 'post',
            data : {
                target : target,
                sum : sum
            },
            success : function(data) {
                $.notify({
                    position : 'top-right',
                    type: data.type,
                    message: data.msg
                });
                $('.sendButton').val('Подтвердить перевод');
                $('.sendButton').removeAttr('disabled');
                return false;
            },
            error : function(data) {
                console.log(data.responseText);
            }
        });
    });
});

function showPopup(el) {
    if($('.popup').is('.active')) {
        $('.popup').removeClass('active');
    }
    $('.overlay, body, .popup.'+el).addClass('active');
}
function chatdelet(id) {
    if(admin == 1) {
        $.post('/admin/chatdel', {messages: id}, function (data) {
            if (data) {
                $.notify({
                    position : 'top-right',
                    type: data.status,
                    message: data.message
                });
            }
        });
    }
    if(moder == 1) {
        $.post('/moder/chatdel', {messages: id}, function (data) {
            if (data) {
                $.notify({
                    position : 'top-right',
                    type: data.status,
                    message: data.message
                });
            }
        });
    }
}
function copyToClipboard(element) {
    var $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(element).val()).select();
    document.execCommand('copy');
    $temp.remove();
    $.notify({
        position : 'top-right',
        type: 'success',
        message: 'Скопировано в буфер обмена!'
    });
}
function unbanMe() {
    $.ajax({
        url : '/unbanMe',
        type : 'post',
        success : function(data) {
            $.notify({
                position : 'top-right',
                type: data.type,
                message: data.msg
            });
            return false;
        },
        error : function(data) {
            console.log(data.responseText);
        }
    });
}
function sendToChat() {
    $.post('/chat', {messages: '$bal'}, function (data) {
        if (data) {
            $.notify({
                position : 'top-right',
                type: data.status,
                message: data.message
            });
        }
    });
}
function sharePromo(code) {
    $.post('/chat', {messages: '$promo ' + code}, function (data) {
        if (data) {
            if(data.status == 'success') $('#promoshare tr[data-id="'+ code +'"]').remove();
            $('.popup, .overlay, body').removeClass('active');
            $.notify({
                position : 'top-right',
                type: data.status,
                message: data.message
            });
        }
    });
}
function declOfNum(number, titles)
{
    cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}
function audio(audio, vol) {
    var media = new Audio();
    media.src = audio;
    media.volume = vol;
    var playPromise = media.play();
    setTimeout(function() {
        playPromise;
    }, 300);
    if(playPromise !== undefined) {
        playPromise.then(_ => {
            playPromise;
        })
        .catch(error => {
            media.play();
        });
    }
}
 $('.form-select select').change(function(){
        $('.picked-method .item').removeClass('active');
        $('.picked-method .' + $(this).val()).addClass('active');
        checkSystem();
        calcSum();
    });





function updateBalanceAnimated(selector, balance){
    current_balance = balance;
    var init_balance = parseInt($(selector).text().split(' ').join(''));
    jQuery({cur_balance: init_balance}).animate({cur_balance: balance}, {
        duration: 500,
        easing:'swing',
        step: function() {
            $(selector).text(beautifyBalance(Math.ceil(this.cur_balance)));
        },
        complete: function() { 
            $(selector).text(beautifyBalance(Math.ceil(balance)));
        }
    });
}

function beautifyBalance(balance){
    return balance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
