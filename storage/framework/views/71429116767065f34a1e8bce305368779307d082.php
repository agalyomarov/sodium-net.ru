<?php $__env->startSection('content'); ?>
<script>
    var comission = 0;
    var min_chance = 1;
    var max_chance = 95;
</script>
<link rel="stylesheet" href="/assets/css/ion.rangeSlider.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<script src="<?php echo e(asset('/assets/js/dice.js')); ?>"></script>

<div class="game-container game-container_dice">
    <div class="game-area">
        <?php if(auth()->guard()->check()): ?>
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                <?php echo $__env->make('../components/bet-form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <div class="game-sidebar-hide-desktop">
                    
                    <div class="flex-align">
                        <div class="game-sidebar__input-wrapper" style="flex: 1; margin-right: 5px;">
                            <label class="game-sidebar__input-label">Шанс</label>
                            <div class="validation-wrapper">
                                <span class="validation-message"></span>
                                <input type="text" value="50.00" name="chance" data-what="Шанс" data-min="1"
                                    data-max="95" data-value-on-nonnumeric="50.00" data-precision="2"
                                    class="dice-input-chance float-input-validate game-sidebar__input game-area_dice-input duplicate-on-input select-on-focus"
                                    data-duplicate=".game-area_dice-input-sfx_chance" autocomplete="off">
                                <div class="game-area_dice-input-sfx">
                                    <span class="game-area_dice-input-cp game-area_dice-input-sfx_chance">50.00</span>
                                    <span class="game-area_dice-input-sfxtxt">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="game-sidebar__input-wrapper" style="flex: 1;">
                            <label class="game-sidebar__input-label">Коэфф.</label>
                            <div class="validation-wrapper">
                                <span class="validation-message"></span>
                                <input type="text" value="2.00" name="coeff" data-what="Коэффициент" data-min="1.05"
                                    data-max="100.00" data-value-on-nonnumeric="2.00" data-precision="2"
                                    class="dice-input-coeff float-input-validate game-sidebar__input game-area_dice-input duplicate-on-input select-on-focus"
                                    data-duplicate=".game-area_dice-input-sfx_coef" autocomplete="off">
                                <div class="game-area_dice-input-sfx">
                                    <span class="game-area_dice-input-cp game-area_dice-input-sfx_coef">2.00</span>
                                    <span class="game-area_dice-input-sfxtxt">x</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-sidebar__play-button-wrapper">
                    <button
                        class="game-sidebar__play-button game-sidebar__play-button_full_width dice-create-game">Играть</button>
                </div>
            </div>
            <div class="game-sidebar__footer">
                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_dice"><span class="myicon-why"></span></button>
                <button class="game-sidebar__fair-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_dice-fair"><span class="myicon-security"></span></button>
            </div>
        </div>
        <?php endif; ?>
        <div class="game-component game-component__short">
            <div class="game-area game-area_dice">
                <div class="game-area_dice-options">
                    <div class="game-area_dice-option">
                        <div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin">
                            <label class="game-sidebar__input-label">Коэфф.</label>
                            <div class="validation-wrapper">
                                <span class="validation-message"></span>
                                <input type="text" value="2.00" name="coeff" data-what="Коэффициент" data-min="1.05"
                                    data-max="100.00" data-value-on-nonnumeric="2.00" data-precision="2"
                                    class="dice-input-coeff float-input-validate game-sidebar__input game-area_dice-input duplicate-on-input select-on-focus"
                                    data-duplicate=".game-area_dice-input-sfx_coef" autocomplete="off">
                                <div class="game-area_dice-input-sfx">
                                    <span class="game-area_dice-input-cp game-area_dice-input-sfx_coef">2.00</span>
                                    <span class="game-area_dice-input-sfxtxt">x</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="game-area_dice-option">
                        <div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin">
                            <label class="game-sidebar__input-label">Шанс</label>
                            <div class="validation-wrapper">
                                <span class="validation-message"></span>
                                <input type="text" value="50.00" name="chance" data-what="Шанс" data-min="1"
                                    data-max="95" data-value-on-nonnumeric="50.00" data-precision="2"
                                    class="dice-input-chance float-input-validate game-sidebar__input game-area_dice-input duplicate-on-input select-on-focus"
                                    data-duplicate=".game-area_dice-input-sfx_chance" autocomplete="off">
                                <div class="game-area_dice-input-sfx">
                                    <span class="game-area_dice-input-cp game-area_dice-input-sfx_chance">50.00</span>
                                    <span class="game-area_dice-input-sfxtxt">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="game-area-possible-winning">
                    <div class="game-area-possible-winning-wrapper">
                        <div class="game-area-possible-winning-val">0</div>
                    </div>
                    <img class="game-area-pw-bg-img" src="/assets/images/dice-pw.svg" alt="">
                </div>
                <div class="game-area-slider">
                    <div class="inner-game-area-slider">
                        <div class="index__home__indicator__inner index__home__indicator__inner--line"
                            style="display: none;">
                            <div class="index__home__indicator__inner__number is-rolling is-hidden">
                                <div class="index__home__indicator__inner__number__roll is-negative">
                                    <span>0.00</span>
                                </div>
                            </div>
                        </div>
                        <input id="slider" type="hidden" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="game-container_history">
        <table class="games-table games-table__dice games-table__dice_history" style="display: table">
            <thead>
                <tr class="games-table__header-tr">
                    <th class="games-table__header-th">Игрок</th>
                    <th class="games-table__header-th">Ставка</th>
                    <th class="games-table__header-th">Коэф.</th>
                    <th class="games-table__header-th">Число</th>
                    <th class="games-table__header-th">Выигрыш</th>
                    <th class="games-table__header-th"></th>
                </tr>
            </thead>
            <tbody id="lastGame">
                <?php $__currentLoopData = $game; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="show games-table__body-tr">
                    <td class="games-table__body-td games-table__body-td_low_padding">
                        <div class="flex-align game-container__table__player">
                        
                        <span><?php echo $g['username']; ?></span> </div>
                    </td>
                    <td class="games-table__body-td games-table__money"> <span class="myicon-coins"></span> <?php echo $g['sum']; ?> </td>
                    <td class="games-table__body-td"><?php echo e($g['num']); ?></td>
                    <td class="games-table__body-td"><?php echo e($g['vip']); ?>x</td>
                    <td class="games-table__body-td games-table__money games-table__money_<?php echo e($g['win'] ? 'win' : 'lost'); ?>"> <?php echo e($g['win'] ? $g['win_sum'] : 0); ?> 
                    <span class="myicon-coins"></span></td>
                    <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a
                            href="/fair/<?php echo e($g['hash']); ?>"
                            class="tooltip tooltipstered" target="_blank"><span class="myicon-security"></span></a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>