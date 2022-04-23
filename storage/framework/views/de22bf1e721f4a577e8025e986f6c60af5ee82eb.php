<?php $__env->startSection('content'); ?>
<?php if($bet): ?>
<script>
    var game_active = '<?php echo e($gameStatus); ?>';
    var bet = parseInt('<?php echo e($bet->price); ?>');
    var isCashout = false;
    var withdraw = parseFloat('<?php echo e($bet->withdraw); ?>');
</script>
<?php else: ?>
<script>
    var game_active = '<?php echo e($gameStatus); ?>';
    var bet;
    var isCashout;
    var withdraw;
</script>
<?php endif; ?>
<script src="<?php echo e(asset('assets/js/jquery.flot.min.js')); ?>"></script>
<link rel="stylesheet" href="/assets/css/simplebar.css" />
<script src="/assets/js/simplebar.min.js"></script>
<script src="/assets/js/chart.js"></script>
<script src="/assets/js/crash.js"></script>

<div class="game-container game-container_dice">
    <div class="game-area">
        <?php if(auth()->guard()->check()): ?>
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                <?php echo $__env->make('../components/bet-form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                <div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin" style="margin-top: 20px;">
                    <label class="game-sidebar__input-label">Автовывод</label>
                    <div class="validation-wrapper">
                        <span class="validation-message"></span>
                        <input type="text" data-helper=".crash-auto-helper" value="2.00" name="coeff" data-what="Коэффициент"
                            id="crash-auto" data-min="1.02" data-max="950000" data-value-on-nonnumeric="2.00" data-precision="2"
                            class="dice-input-coeff float-input-validate game-sidebar__input game-area_dice-input duplicate-on-input select-on-focus show-input-helper"
                            data-duplicate=".game-area_dice-input-sfx_coef" autocomplete="off">
                        
                    </div>
                    <div class="game-sidebar__input-helper game-sidebar__input-helper_bottom-mobile input-helper crash-auto-helper">
                        <button class="game-sidebar__set-action" data-value="1.20" data-method="set" data-id="crash-auto">1.20</button>
                        <button class="game-sidebar__set-action" data-value="1.50" data-method="set" data-id="crash-auto">1.50</button>
                        <button class="game-sidebar__set-action" data-value="2.00" data-method="set" data-id="crash-auto">2.00</button>
                        <button class="game-sidebar__set-action" data-value="5.00" data-method="set" data-id="crash-auto">5.00</button>
                        <button class="game-sidebar__set-action" data-value="10.00" data-method="set" data-id="crash-auto">10.00</button>
                    </div>
                </div>

                <div class="game-sidebar__play-button-wrapper">
                    <button class="game-sidebar__play-button game-sidebar__play-button_full_width crash-play" disabled="">Играть</button>
                </div>

            </div>
            <div class="game-sidebar__footer">
                <button
                    class="game-sidebar__htp-trigger game-sidebar__footer-button game-sidebar__footer-button_small tooltip show-modal tooltipstered"
                    data-modal=".modal_crash"><span class="myicon-why"></span></button>
                <button
                    class="game-sidebar__fair-trigger game-sidebar__footer-button game-sidebar__footer-button_small tooltip show-modal tooltipstered"
                    data-modal=".modal_crash-fair"><span class="myicon-security"></span></button>
            </div>
        </div>
        <?php endif; ?>
        <div class="game-component game-component__short">
            <div class="game-area game-area_double">

                <div class="game-area-wrapper crash-field-wrapper">
                    <div class="crash-block">
                        <div class="crash">
                            <div class="chart" id="chart" style="height:200px;"></div>
                            <div class="chart-info">Загрузка</div>
                        </div>
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
                                                
                                                <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="/fair/<?php echo e($m->secret); ?>" target="_blank">
                                                    <div class="crash-item" style="background-color: <?php echo e($m->color); ?>; border-color: <?php echo e($m->color); ?>;">
                                                        <?php echo e(number_format($m->multiplier, 2, '.', '')); ?>x</div>
                                                </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 622px; height: 63px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
                            <div class="simplebar-scrollbar"
                                style="width: 202px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="crash-history" style="padding: 0px;">
        <div class="crash-bets">
            <?php $__currentLoopData = $game['bets']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="crash-bet">
                <div class="crash-bet__user-wrapper">
                    <div class="crash-bet__image-wrapper"> 
                    <img src="<?php echo e($bet['user']['avatar']); ?>"
                        class="crash-bet__image" style="border-color: #333" alt=""> 
                    </div>
                    <div class="crash-bet__username-wrapper">
                        <div class="crash-bet__username"><?php echo e($bet['user']['username']); ?>:</div>
                    </div>
                </div>
                <?php if(!$bet['status'] == 1): ?>
                <div class="crash-bet__values">
                    <div class="crash-bet__value crash-bet__sum"><span class="myicon-coins"></span> <?php echo e($bet['price']); ?></div>
                    <div class="crash-bet__value crash-bet__coeff">В игре</div>
                    <div class="crash-bet__value crash-bet__win <?php if(!$bet['status'] == 1): ?> crash-bet__win_hidden <?php endif; ?>">
                        <span class="myicon-coins"></span>
                    </div>
                </div>
                <?php else: ?>
                <div class="crash-bet__values">
                    <div class="crash-bet__value crash-bet__sum"><span class="myicon-coins"></span> <?php echo e($bet['price']); ?></div>
                    <div class="crash-bet__value crash-bet__coeff crash-bet__value crash-bet__coeff_won"><?php echo e($bet['withdraw']); ?>x</div>
                    <div class="crash-bet__value crash-bet__win <?php if(!$bet['status'] == 1): ?> crash-bet__win_hidden <?php endif; ?>"><span class="myicon-coins"></span> <?php echo e($bet['won']); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>