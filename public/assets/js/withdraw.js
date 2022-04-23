var prefixes = ["375", "994", "91", "7", "77", "380", "44", "9955", "370", "992", "66", "998", "507", "374", "371", "90", "905", "373", "972", "84", "372", "82", "996", "79", "995", "37", "84"];
var rub_to_uah = 0.35;

$(document).ready(function(){

    $('#withdraw-wallet').on('input', function(){
        // validateAndFormatWallet();
    });

    $('.wallet-options__item').on('click', function(){
        $('.wallet-options__item.active').removeClass('active');
        $(this).addClass('active');
        changePayway($(this).data('payway'));
    });

    $('#payway-select').on('change', function(){
        changePayway(this.value);
    });
    
    $('.confirm-wallet-go').on('click', function(){
        wallets.push({'wallet': $('#withdraw-wallet').val().replace(/[^0-9]/g, "")});
        withdraw();
        closePopup();
    }); 


    $('#amount').on('input', calculateWithdraw);
    $('.withdraw_button').on('click', withdraw);
    calculateWithdraw();

});

function checkPreviousWallets(wallet){
    for (var i = 0; i < wallets.length; i++){
        if (wallets[i]['wallet'] == wallet) return true;
    }
    return false;
}
    
function closePopup(){
        $('.modal-window, .modal-backdrop').removeClass('active');
        $('body').removeClass('modal-active');
    }
    
function withdraw(){
    $('.withdraw_button').prop('disabled', true);
    var amount = parseInt($('#amount').val());
    var payway = $('#payment-payway').val();
    var wallet = $('#withdraw-wallet').val().replace(/[^0-9]/g, "");
    // if (!checkPreviousWallets(wallet)){
    //     $('.modal_withdraw-confirm .confirm-wallet').text($('#withdraw-wallet').val());
    //     $('.modal_withdraw-confirm, .modal-backdrop').addClass('active');
    //     $('body').addClass('modal-active');
    //     $('.withdraw_button').prop('disabled', false);
    //     return;
    // }
    $.ajax({
        url : '/withdraw',
        type : 'post',
        data : {
            system : payway,
            value : amount,
            wallet : wallet
        },
        success : function(data) {
            if (data.status == 'success' || (data.status == 'error' && data.reason != 'multiaccount' && data.reason != 'wager' && data.reason != 'deposit')){
                $.notify({
                    position : 'bottom-right',
                    type: data.status,
                    message: data.msg
                });
                if (data.status == 'success') {
                    updateBalanceAnimated('#money', data.balance);
                }
            }
            else{
                $('.modal_withdraw-error .htp-popup-message').html(data.msg);
                $('.modal_withdraw-error, .modal-backdrop').addClass('active');
                $('body').addClass('modal-active');
            }
           
            $('.withdraw_button').prop('disabled', false);
        },
        error : function(data) {
            $.notify({
                position : 'bottom-right',
                type: 'error',
                message: 'Ошибка при отправке данных. Попробуйте позже.'
            });
            $('.withdraw_button').prop('disabled', false);
        }
    });
}


function changePayway(payway){
    $('#payment-payway').val(payway);
    $('.wallet-area__payway-name').text(payment_options[payway]['name']);
    $('.wallet-area__payway-image').attr('src', payment_options[payway]['image']);
    $('#amount').data('min', payment_options[payway]['min']);
    $('#amount').data('max', payment_options[payway]['max']);
    $('#amount').data('value-on-nonnumeric', payment_options[payway]['min']);
    $('#amount').val(payment_options[payway]['min']);
    $('#withdraw-wallet').attr('placeholder', payment_options[payway]['format']);
    current_payway = payway;
    type = payment_options[payway]['type'];
    // if ($('#withdraw-wallet').val().length){
    //     validateAndFormatWallet();
    // }
    // validateField('#amount');
    calculateWithdraw();

    if (current_payway == 'card_rub'){
        $('.info-tip').attr('title', 'Р—Р° РїСЂРѕРІРµРґРµРЅРёРµ Р±Р°РЅРєРѕРІСЃРєРѕР№ С‚СЂР°РЅР·Р°РєС†РёРё РІР·РёРјР°РµС‚СЃСЏ С„РёРєСЃРёСЂРѕРІР°РЅРЅР°СЏ РєРѕРјРёСЃСЃРёСЏ 50 СЂСѓР±.');
        $('.info-tip').removeClass('hidden');
    }
    else if (current_payway == 'card_uah') {
        $('.info-tip').attr('title', 'Р—Р° РїСЂРѕРІРµРґРµРЅРёРµ Р±Р°РЅРєРѕРІСЃРєРѕР№ С‚СЂР°РЅР·Р°РєС†РёРё РІР·РёРјР°РµС‚СЃСЏ С„РёРєСЃРёСЂРѕРІР°РЅРЅР°СЏ РєРѕРјРёСЃСЃРёСЏ 10 UAH. РЈРєР°Р·Р°РЅ РїСЂРёРјРµСЂРЅС‹Р№ РєСѓСЂСЃ, РЅР°СЃС‚РѕСЏС‰РёР№ РјРѕР¶РµС‚ РѕС‚Р»РёС‡Р°С‚СЊСЃСЏ');
        $('.info-tip').removeClass('hidden');
    }
    else{
        $('.info-tip').addClass('hidden');
    }
    $('.tooltip-fixed').tooltipster("destroy");
    $('.tooltip-fixed').tooltipster({
        side: 'top',
        theme: 'tooltipster-borderless',
        maxWidth: 250,
        trigger: 'custom',
        triggerOpen: {
            mouseenter: true,
            tap: true,
        },
        triggerClose: {
            mouseleave: true,
            tap: true
        },
        delay: 30
    });
}

function luhn_checksum(code) {
    var len = code.length
    var parity = len % 2
    var sum = 0
    for (var i = len-1; i >= 0; i--) {
        var d = parseInt(code.charAt(i))
        if (i % 2 == parity) { d *= 2 }
        if (d > 9) { d -= 9 }
        sum += d
    }
    return sum % 10
}

function luhn_caclulate(partcode) {
    var checksum = luhn_checksum(partcode + "0")
    return checksum == 0 ? 0 : 10 - checksum
}

function luhn_validate(fullcode) {
    return luhn_checksum(fullcode) == 0
}

function formatCC(e){
    var res = "";
    for (var i = 0; i < e.length; i++){
        if (i && i % 4 == 0) res += ' ';
        res += e[i];
    }
    return res;
}

function formatPayway(e) {
    var a = function(e) {
        for (var a = 0; a < prefixes.length; a += 1) {
            var t = prefixes[a];
            if (e.startsWith(t))
                return t
        }
        return false;
    }(e);
    if (a)
        switch (a.length) {
        case 1:
            return "+* *** ***-**-**";
        case 2:
            return "90" === a ? "+** *** *** ****" : "+** ** *** ** **";
        case 3:
        case 4:
            return "+*** ** ***-**-**";
        default:
            return false;
        }
    return false;
};

function format(e, a, t) {
    for (var n = e.replace(/[^0-9]/g, "").split(""), r = 0, i = "", l = 0; l < a.length; l += 1) {
        var c = a[l];
        n[r] ? /\*/.test(c) ? (i += n[r], r += 1) : i += c : t && t.split("")[l] && (i += t.split("")[l]);
    }
    return i;
}

function validateAndFormatWallet(){
    var val = $('#withdraw-wallet').val().replace(/[^0-9]/g, "");
    if (type == 'phone'){
        var _fp = formatPayway(val);
        if (_fp !== false) {
            var _frm = format(val, _fp);
            $('#withdraw-wallet').val(_frm);
            $('#withdraw-wallet').parents('.validation-wrapper').children('.validation-message').fadeOut(100);
            $('#withdraw-wallet').removeClass('hasErrors');
            return true;
        }
        else{
            var errorMsg = 'Ошибка ввода'; 
            $('#withdraw-wallet').parents('.validation-wrapper').children('.validation-message').fadeIn(100).text(errorMsg);
            $('#withdraw-wallet').addClass('hasErrors');
            return false;
        }
    }

    if (type == 'card'){
        var _frm = formatCC(val);
        $('#withdraw-wallet').val(_frm);
        if (!luhn_validate(val) || _frm.length == 0){
            var errorMsg = 'Ошибка ввода'; 
            $('#withdraw-wallet').parents('.validation-wrapper').children('.validation-message').fadeIn(100).text(errorMsg);
            $('#withdraw-wallet').addClass('hasErrors');
            return false;
        }
        else{
            $('#withdraw-wallet').parents('.validation-wrapper').children('.validation-message').fadeOut(100);
            $('#withdraw-wallet').removeClass('hasErrors');
            return true;
        }
    }
    if (type == 'yamoney'){
        $('#withdraw-wallet').val(val);
        if (!/^41001\d*$/.test(val)){
            $('#withdraw-wallet').parents('.validation-wrapper').children('.validation-message').fadeIn(100).text('Ошибка ввода');
            $('#withdraw-wallet').addClass('hasErrors');
            return false;
        }
        else{
            $('#withdraw-wallet').parents('.validation-wrapper').children('.validation-message').fadeOut(100);
            $('#withdraw-wallet').removeClass('hasErrors');
            return true;
        }
    }
}

function calculateWithdraw(){
    var comission = parseInt(payment_options[current_payway]['comission']);
    var fixed = parseInt(payment_options[current_payway]['fixed']);
    var currency = payment_options[current_payway]['currency'];

    var current_sum_coins = parseInt($('#amount').val());
    if (isNaN(current_sum_coins) || current_sum_coins < 0) current_sum_coins = 0;
    var to_enroll = 0;
    if (current_sum_coins < payment_options[current_payway]['min']){
        to_enroll = 0;
    }
    else{
        to_enroll = Math.floor((current_sum_coins - current_sum_coins * comission / 100) / 10);
        to_enroll -= fixed / 10;
        if (currency == 'uah'){
            to_enroll = '~ ' + Math.floor(rub_to_uah * to_enroll);
            $('.wallet-input-currency_enroll').text('UAH');
        }
        else{
            $('.wallet-input-currency_enroll').text('РУБ');
        }
    }
  
    $('#deposit-coins').val(to_enroll);
}