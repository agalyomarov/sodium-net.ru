

<?php $__env->startSection('content'); ?>

<div class="game-container game-container_wallet">
        <div class="wallet-header">
            <a class="wallet-header__item active" href="/wallet/deposit">Пополнение</a>
            <a class="wallet-header__item " href="/wallet/withdraw">Вывод</a>
            <a class="wallet-header__item " href="/wallet/history">История</a>
        </div>
            <script> 
        var comission_free = 500;
        var dep_code = 'GHWXN';
        var dep_perc = 100;
        var can_deposit_with_promocode = true;
        var avaiable_payways = [{"id":1,"title":"Qiwi","name":"qiwi_rub","min":10,"max":100000,"comission":4,"status":1,"is_default":1},{"id":2,"title":"Yandex.Money","name":"yamoney_rub","min":10,"max":100000,"comission":4,"status":1,"is_default":0},{"id":3,"title":"Visa\/Mastercard","name":"card_rub","min":10,"max":100000,"comission":4,"status":1,"is_default":0},{"id":6,"title":"MTS","name":"mts_rub","min":10,"max":15000,"comission":4,"status":1,"is_default":0},{"id":7,"title":"Tele2","name":"tele2_rub","min":10,"max":15000,"comission":4,"status":1,"is_default":0},{"id":8,"title":"Megafon","name":"megafon_rub","min":10,"max":15000,"comission":4,"status":1,"is_default":0},{"id":9,"title":"Beeline","name":"beeline_rub","min":10,"max":15000,"comission":4,"status":1,"is_default":0},{"id":10,"title":"Perfect Money","name":"perfectmoney_usd","min":10,"max":100000,"comission":2,"status":1,"is_default":0},{"id":11,"title":"\u0410\u043b\u044c\u0444\u0430 \u041a\u043b\u0438\u043a","name":"alfaclick_rub","min":10,"max":15000,"comission":3,"status":1,"is_default":0},{"id":12,"title":"\u0422\u0435\u0440\u043c\u0438\u043d\u0430\u043b\u044b (\u0420\u0424)","name":"terminal_rub","min":10,"max":15000,"comission":3,"status":1,"is_default":0}];
        var payment_options = {};
        var default_payway = {"id":1,"title":"Qiwi","name":"qiwi_rub","min":10,"max":100000,"comission":4,"status":1,"is_default":1};
        var comission = default_payway.comission;
        
        for (var i = 0; i < avaiable_payways.length; i++){
            payment_options[avaiable_payways[i].name] = {
                name: avaiable_payways[i].title,
                min: avaiable_payways[i].min,
                max: avaiable_payways[i].max,
                comission: avaiable_payways[i].comission,
                image: '/assets/images/' + avaiable_payways[i].name + '.png'
            };
        }
    </script>
    <script src="/assets/js/deposit.js?v=2.0"></script>
    <div class="wallet-body">
        <div class="wallet-sidebar">
            <div class="wallet-sidebar__header">
                Выберите способ
            </div>
            <div class="wallet-options">
                                <button class="wallet-options__item active" data-payway="qiwi_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/qiwi_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Qiwi</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="yamoney_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/yamoney_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Yandex.Money</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="card_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/card_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Visa/Mastercard</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="mts_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/mts_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">MTS</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="tele2_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/tele2_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Tele2</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="megafon_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/megafon_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Megafon</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="beeline_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/beeline_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Beeline</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">4</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="perfectmoney_usd">
                        <div class="wallet-options__item-image"><img src="/assets/images/perfectmoney_usd.png" alt=""></div>
                        <div class="wallet-options__item-name">Perfect Money</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">2</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="alfaclick_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/alfaclick_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Альфа Клик</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">3</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                                <button class="wallet-options__item " data-payway="terminal_rub">
                        <div class="wallet-options__item-image"><img src="/assets/images/terminal_rub.png" alt=""></div>
                        <div class="wallet-options__item-name">Терминалы (РФ)</div>              
                        <div class="wallet-options__item-comission">
                            <span class="wallet-options__item-comission__value-wrapper">
                                <span class="wallet-options__item-comission__value">3</span>%
                            </span>
                            <span class="wallet-options__item-comission__checked"></span>
                        </div>              
                    </button>
                            </div>
        </div>

        <div class="wallet-area">
                            <div class="wallet-area__row" style="margin-bottom: 15px">
                    <div class="deposit-notification">
                        Комиссия <span class="deposit-notification_highlight">0%</span> <span class="hide-500">при пополнении</span> от <span class="deposit-notification_highlight">500</span> рублей
                    </div>
                </div>
                        <div class="wallet-area__row">
                <div class="wallet-area__payway">
                    <span>Способ оплаты: </span>
                    <img class="wallet-area__payway-image" src="/assets/images/qiwi_rub.png" alt="">
                    <div class="wallet-area__payway-name">Qiwi</div>      
                </div>
                <div class="wallet-area-select mt-10">
                    <div class="form-select form-select_light">
                        <select id="payway-select">
                            <option disabled="" value="none" selected="">Выбрать другой способ</option>
                                                            <option value="qiwi_rub">Qiwi</option>
                                                            <option value="yamoney_rub">Yandex.Money</option>
                                                            <option value="card_rub">Visa/Mastercard</option>
                                                            <option value="mts_rub">MTS</option>
                                                            <option value="tele2_rub">Tele2</option>
                                                            <option value="megafon_rub">Megafon</option>
                                                            <option value="beeline_rub">Beeline</option>
                                                            <option value="perfectmoney_usd">Perfect Money</option>
                                                            <option value="alfaclick_rub">Альфа Клик</option>
                                                            <option value="terminal_rub">Терминалы (РФ)</option>
                                                    </select>
                        <span class="myicon-down-arrow"></span>
                    </div>
                </div>
            </div>
            <div class="wallet-area__delimiter">
                <span class="myicon-down-arrow wallet-area__delimiter_item"></span>
            </div>
            <div class="wallet-area__row">
                <span>Выберите сумму</span>
                <div class="sum-options">
                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="50">
                            <div class="sum-option__value">
                                50 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>
                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="100">
                            <div class="sum-option__value">
                                100 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>
                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="250">
                            <div class="sum-option__value">
                                250 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>

                    <div class="sum-option-wrapper">
                        <button class="sum-option active" data-sum="500">
                            <div class="sum-option__value">
                                500 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>

                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="1000">
                            <div class="sum-option__value">
                                1000 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>
                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="2500">
                            <div class="sum-option__value">
                                2500 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>
                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="5000">
                            <div class="sum-option__value">
                                5000 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>

                    <div class="sum-option-wrapper">
                        <button class="sum-option" data-sum="10000">
                            <div class="sum-option__value">
                                10000 <span class="sum-option__currency">₽</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="wallet-area__delimiter">
                <span class="myicon-down-arrow wallet-area__delimiter_item"></span>
            </div>
            <div class="wallet-area__row">
                <form action="/pay" method="GET" target="_blank">
                    <div class="payment-total">
                        <div class="payment-total__first-row">
                            <div class="payment-field payment-sum">
                                <label for="deposit-sum">Сумма<span class="sm-hidden"> пополнения</span></label>
                                <div class="validation-wrapper">
                                    <span class="validation-message"></span>
                                    <div class="wallet-input-wrapper">
                                        <input inputmode="numeric" value="500" type="text" name="num" id="amount" data-what="Сумма" data-min="10" data-max="100000" data-value-on-nonnumeric="10" class="numeric-input-validate game-sidebar__input game-sidebar__input_dark" autocomplete="off">
                                        <div class="wallet-input-currency">руб.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-field payment-to-enroll">
                                <label for="deposit-sum">К зачислению</label>
                                <div class="validation-wrapper">
                                    <div class="wallet-input-wrapper">
                                        <input value="5000" type="text" name="deposit-coins" id="deposit-coins" class="game-sidebar__input game-sidebar__input_dark" autocomplete="off" disabled="" style="opacity: 0.5">
                                        <div class="wallet-input-currency"><span class="myicon-coins" style="font-size: 18px"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-total__second-row">
                            <div class="promo-column">
                                <a class="payment-promocode-call">
                                    Есть промокод?
                                </a>
                                <div class="payment-promo success">
                                    <div class="validation-wrapper">
                                        <span class="validation-message validation-message_bottom"></span>
                                        <div class="wallet-input-wrapper">
                                            <input placeholder="Введите промокод" type="text" name="dep_code" id="deposit-promocode" class="game-sidebar__input game-sidebar__input_dark small-placeholder" autocomplete="off">
                                            <div class="wallet-input-success"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-result">
                                <div class="payment-result__row">
                                    <div class="payment-result__row-label">Комиссия</div>
                                    <div class="payment-result__row-dots"></div>
                                    <div class="payment-result__row-value payment-result__row-value_comission">0%</div>
                                </div>
                                <div class="payment-result__row payment-result__row_finish">
                                    <div class="payment-result__row-label">К оплате</div>
                                    <div class="payment-result__row-dots"></div>
                                    <div class="payment-result__row-value payment-result__row-value_to-pay">500 руб.</div>
                                </div>
                                <div class="payment-result__row">
                                    <input type="hidden" name="payway" value="qiwi_rub" id="payment-payway">
                                    <button type="submit" class="game-sidebar__play-button game-sidebar__play-button_green pay_button">Оплатить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="wallet-area__row">
                <div class="payment-raffle">
                    <span class="myicon-raffle payment-raffle__left"></span>
                    <div class="payment-raffle__right">
                        <div class="payment-raffle__title">
                            Режим Raffle
                        </div>
                        <div class="payment-raffle__description">
                            <p>Ежедневно в 22:00 по МСК проводится лотерея, в которой автоматически принимают участие все игроки, пополнившие счет на любую сумму.
                               Узнайте больше на <a href="/raffle" target="_blank" class="def_link" style="color: #fff;">странице режима</a> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>