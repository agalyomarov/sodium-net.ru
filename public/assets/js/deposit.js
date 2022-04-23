$(document).ready(function(){
    
    $('.payment-promocode-call').on('click', function(){
        $(this).hide();
        $('.payment-promo').fadeIn(0);
    });

    $('.wallet-options__item').on('click', function(){
        $('.wallet-options__item.active').removeClass('active');
        $(this).addClass('active');
        changePayway($(this).data('payway'));
    });

    $('#payway-select').on('change', function(){
        changePayway(this.value);
    });

    function changePayway(payway){
        $('#payment-payway').val(payway);
        $('.wallet-area__payway-name').text(payment_options[payway]['name']);
        $('.wallet-area__payway-image').attr('src', payment_options[payway]['image']);
        $('#amount').data('min', payment_options[payway]['min']);
        $('#amount').data('max', payment_options[payway]['max']);
        comission = parseInt(payment_options[payway]['comission']);
        
        // validateField('#amount');
        calculatePayment();
    }


    $('.sum-option').on('click', function(){
        $('.sum-option.active').removeClass('active');
        $(this).addClass('active');
        $('#amount').val($(this).data('sum'));
        // validateField('#amount');
        calculatePayment();
    });

    $('#amount').on('input', calculatePayment);
    $('#deposit-promocode').on('input', calculatePayment);

    calculatePayment();

});



function pickSumOption(sum){
    $('.sum-option.active').removeClass('active');
    $('.sum-option').each(function(){
        if (parseInt($(this).data('sum')) == parseInt(sum)){
            $(this).addClass('active');
            return true;
        }
    });
}

function calculatePayment(){
    var current_sum = parseInt($('#amount').val());
    if (isNaN(current_sum) || current_sum < 0) current_sum = 0;
    pickSumOption(current_sum);
    var promocode = $('#deposit-promocode').val();
    var to_enroll_coins = current_sum * 10;
    if (promocode.toUpperCase() === dep_code.toUpperCase()){
        if (can_deposit_with_promocode && current_sum >= 100){
            to_enroll_coins += Math.floor(to_enroll_coins * dep_perc / 100);
            $('#deposit-promocode').removeClass('hasErrors');
            $('#deposit-promocode').parents('.validation-wrapper').children('.validation-message').fadeOut(100);
            $('#deposit-promocode').addClass('success');
            $('.wallet-input-success').addClass('active');
        }
        else{
            var errorMsg;
            if (current_sum < 100){
                errorMsg = 'Промокод действует от 100 руб.';
            }
            if (!can_deposit_with_promocode){
                errorMsg = 'Промокод уже использован';
            }
      
            $('#deposit-promocode').addClass('hasErrors');
            $('.wallet-input-success').removeClass('active');
            $('#deposit-promocode').parents('.validation-wrapper').children('.validation-message').fadeIn(100).text(errorMsg);
        }
    }
    else{
        $('#deposit-promocode').removeClass('hasErrors').removeClass('success');
        $('.wallet-input-success').removeClass('active');
        $('#deposit-promocode').parents('.validation-wrapper').children('.validation-message').fadeOut(100);
    }
    $('#deposit-coins').val(to_enroll_coins);
    var _cm = comission;
    if (current_sum >= comission_free && comission_free >= 0) _cm = 0;
    $('.payment-result__row-value_comission').text(_cm + '%');
    $('.payment-result__row-value_to-pay').text(Math.floor((current_sum + current_sum * _cm / 100) * 100) / 100 + ' руб.');
}