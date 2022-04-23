

<?php $__env->startSection('content'); ?>
<div class="head-game">
	<span class="game-name">Реферальная система</span>
</div>
<div class="cont-a">
	<div class="ref">
		<div class="info">
			<h3 class="title">Вы можете зарабатывать вместе с нами!</h3>
			<div class="desc">Приглашайте друзей и знакомых, получайте до 1.5% процента от их побед!</div>
		</div>
		<div class="code">
			<div class="code-title">Ваш код для приглашения:</div>
			<div class="value">
				<input type="text" value="<?php echo e($u->affiliate_id); ?>" id="code" readonly>
				<i class="fas fa-copy tooltip" title="Копировать" onclick="copyToClipboard('#code')"></i>
			</div>
		</div>
		<div class="info">
			<h3 class="title">У Вас есть реферальный код или промокод?</h3>
			<div class="desc">За активацию реферального кода вы получите 50 монет!</div>
		</div>
		<div class="code">
			<div class="code-title">Активировать:</div>
			<div class="value">
				<input type="text" placeholder="Введите код" class="promoCode">
				<i class="fas fa-check tooltip promoButton" title="Активировать"></i>
			</div>
		</div>
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
		<div class="moneyRef">
			<div class="to-get">Доступно для получения: <span><?php echo e(floor($u->ref_money)); ?></span> <i class="fas fa-coins"></i></div>
			<div class="total">Всего заработано: <span><?php echo e(floor($u->ref_money_history)); ?></span> <i class="fas fa-coins"></i></div>
			<?php if($u->ref_money > 0.99): ?>
			<a class="getMoney">Забрать</a>
			<?php endif; ?>
		</div>
	</div>


</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>