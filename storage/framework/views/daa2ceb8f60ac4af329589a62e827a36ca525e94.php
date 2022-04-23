

<?php $__env->startSection('content'); ?>
<div class="game-container game-container_raffle">
    <div class="game-area">
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                <div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin">
                    <label class="game-sidebar__input-label">Ваши билеты</label>
                    <div class="validation-wrapper">
                        <span class="validation-message validation-message_bottom-mobile"></span>
                        <div class="mobile-button-combined-wrapper">
                            
                                <input disabled="" value="0" type="text" id="raffle-user-tickets-balance" class="game-sidebar__input" autocomplete="off">
                                <span style="color:rgb(20, 244, 3)" class="myicon-ticket-raffle game-sidebar__input_coins"></span>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="game-sidebar__footer">
                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered" data-modal=".modal_raffle"><span class="myicon-why"></span></button>
                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered" data-modal=".modal_raffle-winners"><span class="myicon-crown"></span></button>
            </div>
        </div>

        <div class="game-component game-component__short">
            <div class="game-area game-area_double">
                <div class="game-area-wrapper raffle-field-wrapper">
                    <div class="raffle">
                        <div class="raffle-loop">
                            <div class="raffle-countdown">
                                <svg class="progress-ring" width="410" height="410">
                                    <circle class="progress-ring__bg" stroke="gray" stroke-width="1" fill="transparent" r="200" cx="205" cy="205"></circle>
                                    <circle class="progress-ring__circle" stroke="#ffc200" stroke-width="3" fill="transparent" r="200" cx="205" cy="205" style="stroke-dasharray: 1256.64, 1256.64; stroke-dashoffset: 113.097;"></circle>
                                </svg>
                                <div class="raffle-bank">Банк: <span id="raffle-price">засекречен</span></div>
                                <div class="raffle-timer">

                                    <div class="heading">До розыгрыша осталось</div>
                                    <div class="raffle-timer-contents" data-progress="91">
                                        <span class="raffle-timer__hours"><span class="raffle-timer__hours-value">0</span><span class="raffle-timer__time-descr raffle-timer__hours-word">час</span>
                                                    </span> <span class="raffle-timer__delimiter">:</span>
                                            <span class="raffle-timer__minutes"><span class="raffle-timer__minutes-value">00</span><span class="raffle-timer__time-descr raffle-timer__minutes-word">минут</span> </span> <span class="raffle-timer__seconds">
                                                        <span class="raffle-timer__seconds-value">00</span><span class="raffle-timer__time-descr raffle-timer__seconds-word">сек</span></span>
                                    </div>
                                </div>
                                <div class="raffle-picker">
                                    <div class="roulette" id="chouser_raffle" style="display: none;">
                                        <div class="second-title"><span>Выбираем победителя</span></div>
                                        <div class="pointer"></div>
                                        <div class="list">
                                            <div class="fixed-width">
                                                <div class="overview" id="carousel_raffle">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="winner-block">
                                            <div class="winner-name">
                                                Победил: <span id="winner-name-value"></span>
                                            </div>
                                            <div class="winner-prize">
                                                Приз: <span id="winner-prize-value"><span id="raffle-price" class="active"></span> <span class="myicon-coins" style="margin-left: 2px;"></span></span>
                                            </div>
                                            <div class="winner-tickets" style="margin-top: -1px;">
                                                Билеты: <span id="winner-tickets-value"></span> <span style="transform: translateY(2px); display:inline-block; margin-left: 2px;" class="myicon-ticket-raffle"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>