<?php $__env->startSection('content'); ?>
    <div class="main-width hide hide-menu">
    <div class="user-profile">
        <div class="user-profile-overview-loop gray-color">
            <div class="user-profile-overview-loop__left">
                <div class="flex-row">
                    <div class="user-profile-overview-loop__left-avatar">
                        <div class="user-profile-overview-loop__left-avatar-wrapper relative-wrapper">
                            <img src="<?php echo e($u->avatar); ?>" alt="">
                            <a href="/logout"  class="user-profile-overview-loop__left-vk">Выйти</a>
                        </div>
                        <p class="user-profile-overview-loop__left-since">На сайте с <?php echo e(explode(' ', $u->created_at)[0]); ?></p>
                    </div>
                    <div class="user-profile-overview-loop__left-resources">
                    <div class="user-profile-overview-loop__left-resources__name"><?php echo e($u->username); ?></span></div>
                        <div class="user-profile-overview-loop__left-resources__balance"><span class="myicon-coins tooltip" title="Монеты"></span> <span class="user-profile-overview-loop__left-resources__money beatify-numbers"><?php echo e($u->balance); ?></span></div>

                        <div class="user-profile-overview-loop__left-actions">
                            <button class="dark-button small-button user-profile-overview-loop__left-action-button user-profile-overview-loop__left-action-button_pay" rel="popup" data-popup="popup-pay">Пополнить</button>
                            <button class="dark-button small-button user-profile-overview-loop__left-action-button user-profile-overview-loop__left-action-button_withdraw" rel="popup" data-popup="popup-withdraw">Вывести</button>
							<li><a href="/pay/history" class="menu <?php echo e(Request::is('pay/history') ? 'active' : ''); ?>"><i class=""></i><div class="name">История счета</div></a></li>
                        </div>
                    </div>
                </div>
                <div class="code">
                    <div class="value">
                            <input type="text" placeholder="Есть промокод?" class="promoCode">
                            <i class="fas fa-check tooltip promoButton" title="Активировать"></i>
                    </div>
                </div>
            </div>


        </div>
        <div class="user-profile-full-stats gray-color">

            <div class="user-profile-full-stats__content">



















                <div class="games-table partnership partner-lvl-1 games-table__partnership" style="display: block;">
                    <div class="your-code">
                        <div class="your-code__text">Ваш реф.код для привлечения рефералов:</div>
                        <div class="your-code__link">
                        <input type="text" class="partnership__code" id="code" readonly="" value="<?php echo e($u->affiliate_id); ?>">
                            <i class="fas fa-copy tooltip tooltipstered partnership__copy" onclick="copyToClipboard('#code')"></i>
                        </div>
                    </div>
                    <div class="ref-descr">
                        <div class="ref-summary">
                            Ваш реферальный уровень — <span class="u-ref__level ref-value"><?php echo e($lvl); ?></span>.
<br>
                                                        Каждый реферал получает бонус <span class="u-ref__bonus ref-value">50 <span class="myicon-coins"></span></span> при регистрации.
                        </div>
                        <center>
                        <div class="ref">
                        <div class="lvl">
			<div class="lvl-title">Ваш реферальный уровень:</div>
			<div class="value">
				<div class="lvl-bar">
					<span class="lvl-up"><?php echo e($lvl); ?></span>
					<span class="lvl-bar-fill" style="width: <?php echo e($width); ?>%"></span>
				</div>
			</div>
			<?php if($ref): ?>
			<div class="desc">Вы пригласили <?php echo e($ref); ?> человек и получаете <?php echo e($perc); ?>% от выиграных ставок.</div>
			<?php else: ?>
			<div class="desc">Еще ни кто не ввел Ваш реферальный код.</div>
			<?php endif; ?>
		</div>
				</div>
			</center>
                    </div>
                    <div class="ref-profit">
                        <div class="ref-profit__item ref-profit__avaiable">
                            <div class="ref-profit__img"><span class="myicon-coins"></span></div>
                            <div class="ref-profit__numer ref-profit__numer_available to-get"><?php echo e(floor($u->ref_money)); ?></div>
                            <div class="ref-profit__descr">Доступно для вывода</div>
                            <?php if($u->ref_money > 0.99): ?>
			<a class="getMoney">Забрать</a>
			<?php endif; ?>
                                                    </div>

                        <div class="ref-profit__item ref-profit__total">
                            <div class="ref-profit__img"><span class="myicon-team"></span></div>
                            <div class="ref-profit__numer"><?php echo e($ref); ?></div>
                            <div class="ref-profit__descr">Рефералов привлечено</div>
                        </div>

                        <div class="ref-profit__item ref-profit__count">
                            <div class="ref-profit__img"><span class="myicon-coins"></span></div>
                            <div class="ref-profit__numer"><?php echo e(floor($u->ref_money_history)); ?></div>
                            <div class="ref-profit__descr">Всего заработано</div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="user-profile-full-stats__controls">
                <button class="user-profile-full-stats__btn-prev user-profile-full-stats__btn" disabled>
                    <span class="myicon-down-arrow user-profile-full-stats__btn__icon" style="transform:rotate(90deg);"></span>
                    <span class="user-profile-full-stats__btn__text">Предыдущая страница</span>
                </button>
                <button class="user-profile-full-stats__btn-next user-profile-full-stats__btn" disabled>
                    <span class="user-profile-full-stats__btn__text">Следующая страница</span>
                    <span class="myicon-down-arrow user-profile-full-stats__btn__icon" style="transform:rotate(-90deg);"></span>
                </button>
            </div>
        </div>
    </div>
    </div>
  </div>
</span>
</li>
</span>
</li>
</ul>
</th>
</tr>
</thead>
</table>
</div>
</div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>