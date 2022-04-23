<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.3.0/simplebar.css"
    integrity="sha512-0EGld845fSKUDzrxFGdF//lic4e8si6FTrlLpOlts8P0ryaV8XkWy/AnwH9yk0G1wHcOvhY9L14W5LCMWa7W+Q=="
    crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.3.0/simplebar.min.js"></script>    
<script src="/assets/js/roulette.js"></script>

<div class="game-container game-container_dice">
    <div class="game-area">
        <?php if(auth()->guard()->check()): ?>
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                <?php echo $__env->make('../components/bet-form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <div class="game-sidebar__play-button-wrapper game-sidebar__play-button-wrapper_no-hide">
                    <div class="double-bet-buttons">
                        <button class="game-sidebar__double-bet game-sidebar__double-bet_yellow betButton"
                            data-bet-type="red"><span class="double-bet-sum"><span
                                    class="double-x">x</span>2</span></button>
                        <button class="game-sidebar__double-bet game-sidebar__double-bet_green betButton"
                            data-bet-type="green"><span class="double-bet-sum"><span
                                    class="double-x">x</span>14</span></button>
                        <button class="game-sidebar__double-bet game-sidebar__double-bet_black betButton"
                            data-bet-type="black"><span class="double-bet-sum"><span
                                    class="double-x">x</span>2</span></button>
                    </div>

                </div>

            </div>
            <div class="game-sidebar__footer">
                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_double"><span class="myicon-why"></span></button>
                <button class="game-sidebar__fair-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_double-fair"><span class="myicon-security"></span></button>
            </div>
        </div>
        <?php endif; ?>

        <?php if($game->status == 2): ?>
        <script>
            setTimeout(() => {
                $('#reletteact').css({
                    'transition' : 'transform <?php echo e($time); ?>s ease',
                    'transform' : 'rotate(<?php echo e($rotate2); ?>deg)'
                });
            }, 1);
            setTimeout(() => {
                $('#rez-numbr').text('<?php echo e($game->winner_num); ?>');
            }, parseInt('<?php echo e($time); ?>')*1000);
        </script>
        <?php endif; ?>

        <div class="game-component">
            <div class="game-area game-area_double">
                <div class="game-area-wrapper double-field-wrapper">
                    <div class="double">
                        <div class="double-timer" style="display: flex;">
                            <div class="double-time is-countdown" id="timer3">
                                <span class="countdown-row countdown-show1">
                                    <span class="countdown-section">
                                        <span class="countdown-amount" id="rez-numbr">wait..</span>
                                        <span class="countdown-period"></span>
                                    </span>
                                </span>
                            </div>
                            <span class="double-text">осталось</span>
                        </div>
                        <div class="double-rel" style="background: rgb(255, 194, 0); display: none;">3</div>
                        <div class="double-win"><img src="/assets/images/right-arrow.svg" alt=""></div>
                        <div class="double-row" id="reletteact"
                            style="transform: rotate(<?php echo e($rotate); ?>deg); transition: transform 0s linear 0s;"><img
                                src="/assets/images/rr5.png" alt=""></div>
                    </div>
                </div>

                <div class="game-footer game-footer_double">
                    <div class="double-footer-wrapper" id="hline" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: -15px 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper"
                                        style="height: auto; overflow: scroll hidden;">
                                        <div class="simplebar-content" style="padding: 15px 0px;">
                                            <div class="double-history-line">

                                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="double-circle double-circle_<?php echo e($l->winner_color); ?>">
                                                    <span class="double-circle-inner">
                                                        <a href="/fair/<?php echo e($l->hash); ?>"
                                                            target="_blank" class="double-circle-inner__number"><?php echo e($l->winner_num); ?></a>
                                                    </span>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 622px; height: 74px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
                            <div class="simplebar-scrollbar"
                                style="width: 152px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="game-container_history">
        <div class="double-history__cols">

            <div class="double-history__col double-history__col_yellow">
                <div class="double-history__col-header">
                    <span class="double-history__col-bets"><span class="myicon-coins"></span> <span
                            class="double-history__col-bets__value double-history__col-bets__value_red"><?php echo e((isset($prices['red'])) ? $prices['red'] : 0); ?></span></span>
                </div>
                <div class="double-history__col-body double-history__col-body_red" style="display: block;">
                    <?php $__currentLoopData = $bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($bet->type == 'red'): ?>
                    <div class="double-history__col-header-bet__user" data-userid="<?php echo e($bet->user_id); ?>">
                        <div class="double-history__col-header-bet__photo"> <img
                                src="<?php echo e($bet->avatar); ?>"
                                class="avatar"> 
                            
                        </div>
                        <div class="double-history__col-header-bet__row">
                            <div class="double-history__col-header-bet__username"><?php echo e($bet->username); ?></div>
                            <div class="double-history__col-header-bet__amount"><?php echo e($bet->value); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="double-history__col double-history__col_green">
                <div class="double-history__col-header">
                    <span class="double-history__col-bets"><span class="myicon-coins"></span> <span
                            class="double-history__col-bets__value double-history__col-bets__value_green"><?php echo e((isset($prices['green'])) ? $prices['green'] : 0); ?></span></span>
                </div>
                <div class="double-history__col-body double-history__col-body_green" style="display: block;">
                    <?php $__currentLoopData = $bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($bet->type == 'green'): ?>
                    <div class="double-history__col-header-bet__user" data-userid="<?php echo e($bet->user_id); ?>">
                        <div class="double-history__col-header-bet__photo"> <img src="<?php echo e($bet->avatar); ?>" class="avatar">
                            
                        </div>
                        <div class="double-history__col-header-bet__row">
                            <div class="double-history__col-header-bet__username"><?php echo e($bet->username); ?></div>
                            <div class="double-history__col-header-bet__amount"><?php echo e($bet->value); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="double-history__col double-history__col_black">
                <div class="double-history__col-header">
                    <span class="double-history__col-bets"><span class="myicon-coins"></span> <span
                            class="double-history__col-bets__value double-history__col-bets__value_black"><?php echo e((isset($prices['black'])) ? $prices['black'] : 0); ?></span></span>
                </div>
                <div class="double-history__col-body double-history__col-body_black" style="display: block;">
                    <?php $__currentLoopData = $bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($bet->type == 'black'): ?>
                    <div class="double-history__col-header-bet__user" data-userid="<?php echo e($bet->user_id); ?>">
                        <div class="double-history__col-header-bet__photo"> <img src="<?php echo e($bet->avatar); ?>" class="avatar">
                            
                        </div>
                        <div class="double-history__col-header-bet__row">
                            <div class="double-history__col-header-bet__username"><?php echo e($bet->username); ?></div>
                            <div class="double-history__col-header-bet__amount"><?php echo e($bet->value); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>