@extends('layout')

@section('content')

<div class="game-container game-container_wallet">
        <div class="wallet-header">
            <a class="wallet-header__item " href="/wallet/deposit">Пополнение</a>
            <a class="wallet-header__item active" href="/wallet/withdraw">Вывод</a>
            <a class="wallet-header__item " href="/wallet/history">История</a>
        </div>
            <script>
        var wallets = [];
        var avaiable_payouts = [{"id":1,"name":"qiwi", "image": "qiwi_rub", "title":"Qiwi","format":"+790xxxxxxxx","min":1000,"max":150000,"comission":0,"fixed":0,"type":"phone","currency":"rub","status":1,"is_default":1},{"id":2,"name":"yandex", "image": "yamoney_rub", "title":"Yandex.Money","format":"41001xxxxxxxxxx","min":1000,"max":100000,"comission":0,"fixed":0,"type":"yamoney","currency":"rub","status":1,"is_default":0},{"id":3,"name":"visa", "image": "card_rub", "title":"Visa\/Mastercard (RU)","format":"546934567890xxxx","min":10000,"max":150000,"comission":0,"fixed":500,"type":"card","currency":"rub","status":1,"is_default":0},{"id":4,"name":"card_uah","title":"Visa\/Mastercard (UA)","format":"546934567890xxxx","min":10000,"max":150000,"comission":0,"fixed":300,"type":"card","currency":"uah","status":1,"is_default":0},{"id":5,"name":"mts_rub","title":"MTS (RU)","format":"+790xxxxxxxx","min":1000,"max":100000,"comission":3,"fixed":0,"type":"phone","currency":"rub","status":1,"is_default":0},{"id":6,"name":"megafon_rub","title":"Megafon (RU)","format":"+790xxxxxxxx","min":1000,"max":100000,"comission":3,"fixed":0,"type":"phone","currency":"rub","status":1,"is_default":0},{"id":7,"name":"tele2_rub","title":"Tele2 (RU)","format":"+790xxxxxxxx","min":1000,"max":100000,"comission":3,"fixed":0,"type":"phone","currency":"rub","status":1,"is_default":0},{"id":8,"name":"beeline_rub","title":"Beeline (RU)","format":"+790xxxxxxxx","min":1000,"max":100000,"comission":3,"fixed":0,"type":"phone","currency":"rub","status":1,"is_default":0}];
        var payment_options = {};
        var default_payout = {"id":1,"name":"qiwi", "image": "qiwi_rub", "title":"Qiwi","format":"+790xxxxxxxx","min":1000,"max":150000,"comission":0,"fixed":0,"type":"phone","currency":"rub","status":1,"is_default":1};

        for (var i = 0; i < avaiable_payouts.length; i++){
            payment_options[avaiable_payouts[i].name] = {
                name: avaiable_payouts[i].title,
                format: avaiable_payouts[i].format,
                min: avaiable_payouts[i].min,
                max: avaiable_payouts[i].max,
                comission: avaiable_payouts[i].comission,
                fixed: avaiable_payouts[i].fixed,
                image: '/assets/images/' + avaiable_payouts[i].image + '.png',
                currency: avaiable_payouts[i].currency,
                type: avaiable_payouts[i].type
            };
        }
        var current_payway = default_payout['name'];
        var type = default_payout['type'];
    </script>
    <script src="/assets/js/withdraw.js?v=2.1"></script>
    <div class="wallet-body">
        <div class="wallet-sidebar">
            <div class="wallet-sidebar__header">
                Выберите способ
            </div>
            <div class="wallet-options">
                                    <button class="wallet-options__item active" data-payway="qiwi">
                        <div class="wallet-options__item-image"><img src="/assets/images/qiwi_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Qiwi</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">0</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <button class="wallet-options__item " data-payway="yandex">
                        <div class="wallet-options__item-image"><img src="/assets/images/yamoney_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Yandex.Money</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">0</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <button class="wallet-options__item " data-payway="visa">
                        <div class="wallet-options__item-image"><img src="/assets/images/card_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Visa/Mastercard (RU)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">0</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <!-- <button class="wallet-options__item " data-payway="card_uah">
                        <div class="wallet-options__item-image"><img src="/assets/images/card_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Visa/Mastercard (UA)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">0</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <button class="wallet-options__item " data-payway="mts_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/mts_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">MTS (RU)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">3</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <button class="wallet-options__item " data-payway="megafon_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/megafon_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Megafon (RU)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">3</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <button class="wallet-options__item " data-payway="tele2_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/tele2_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Tele2 (RU)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">3</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                    <button class="wallet-options__item " data-payway="beeline_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/beeline_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Beeline (RU)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">3</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button> -->
                            </div>
        </div>

        <div class="wallet-area">
            <div class="wallet-area__row">
                <div class="wallet-area__payway">
                    <span>Способ вывода: </span>
                    <img class="wallet-area__payway-image" src="/assets/images/qiwi_rub.png" alt="">
                    <div class="wallet-area__payway-name">Qiwi</div>     
                </div>
                <div class="wallet-area-select mt-10">
                    <div class="form-select form-select_light">
                        <select id="payway-select">
                            <option disabled="" value="none" selected="">Выбрать другой способ</option>
                                                            <option value="qiwi">Qiwi</option>
                                                            <option value="yandex">Yandex.Money</option>
                                                            <option value="visa">Visa/Mastercard (RU)</option>
                                                            <!-- <option value="card_uah">Visa/Mastercard (UA)</option>
                                                            <option value="mts_rub">MTS (RU)</option>
                                                            <option value="megafon_rub">Megafon (RU)</option>
                                                            <option value="tele2_rub">Tele2 (RU)</option>
                                                            <option value="beeline_rub">Beeline (RU)</option> -->
                                                    </select>
                        <span class="myicon-down-arrow"></span>
                    </div>
                </div>
            </div>
            <div class="wallet-area__delimiter">
                <span class="myicon-down-arrow wallet-area__delimiter_item"></span>
            </div>
            <div class="wallet-area__row">
                <span>Введите номер кошелька</span>
                <div class="validation-wrapper withdraw-wallet-field">
                    <span class="validation-message"></span>
                    <div class="wallet-input-wrapper">
                        <input placeholder="+790xxxxxxxx" type="text" name="withdraw-wallet" id="withdraw-wallet" class="game-sidebar__input game-sidebar__input_dark" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="wallet-area__delimiter">
                <span class="myicon-down-arrow wallet-area__delimiter_item"></span>
            </div>
            <div class="wallet-area__row">
               
                    <div class="payment-total">
                        <div class="payment-total__first-row">
                            <div class="payment-field payment-sum">
                                    <label class="payment-field-label" for="deposit-sum">Сумма<span class="sm-hidden"> вывода</span> 
                                    </label>
                                
                                <div class="validation-wrapper">
                                    <span class="validation-message"></span>
                                    <div class="wallet-input-wrapper">
                                        <input inputmode="numeric" value="1000" type="text" name="amount" id="amount" data-what="Сумма" data-min="1000" data-max="150000" data-value-on-nonnumeric="1000" class="numeric-input-validate game-sidebar__input game-sidebar__input_dark" autocomplete="off">
                                        <div class="wallet-input-currency" style="padding-bottom: 0;"><span class="myicon-coins" style="font-size: 18px; color: #cdcdcd;"></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-field payment-to-enroll">
                                <label class="payment-field-label" for="deposit-sum">К получению
                                    <span class="info-tip tooltip-fixed hidden tooltipstered">
                                            <span class="myicon-question-mark"></span>
                                        </span>
                                </label>
                                <div class="validation-wrapper">
                                    <div class="wallet-input-wrapper">
                                        <input value="100" type="text" name="deposit-coins" id="deposit-coins" class="game-sidebar__input game-sidebar__input_dark" autocomplete="off" disabled="" style="opacity: 0.5">
                                        <div class="wallet-input-currency wallet-input-currency_enroll">руб.</div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-total__second-row">
                            <div class="payment-fake-row">

                            </div>
                            <div class="withdraw-result__row">
                                <input type="hidden" name="payway" value="qiwi" id="payment-payway">
                                <button class="game-sidebar__play-button game-sidebar__play-button_green withdraw_button">Вывести</button>
                            </div>
                        </div>
                    </div>
               
            </div>

            <div class="wallet-area__row">
                <div class="payment-raffle">
                    <span class="myicon-clock payment-raffle__left"></span>
                    <div class="payment-raffle__right">
                        <div class="payment-raffle__title">
                            Быстрые выплаты
                        </div>
                        <div class="payment-raffle__description">
                            <p>Среднее время вывода - <b>15 минут</b>. Обработка выводов в ночное время занимает больше времени. В случае, если вывод не пришел в течении <b>12 часов</b>, обратитесь в <a href="https://vk.com/im?sel=-102866645" target="_blank" class="def_link" style="color: #fff;">поддержку</a> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<div class="modal-window modal_withdraw-error">
    <div class="modal-dialog modal-w500">
            <div class="modal htp-modal narrow-modal"> 
                    <div class="heading">Ошибка  <a class="myicon-close modal-close"></a></div>
                        <div class="htp-content">
                            <p class="htp-popup-message"></p>
                            <button class="confirm-wallet-button ok-btn" onclick="closePopup()">ОК</button>
                        </div>
                    </div>
            </div>  
    </div>
</div>
    
@endsection