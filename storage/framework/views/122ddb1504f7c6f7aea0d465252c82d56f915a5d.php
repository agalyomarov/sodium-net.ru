

<?php $__env->startSection('content'); ?>
<script src="https://vk.com/js/api/openapi.js?158" type="text/javascript"></script>
<div class="head-game">
	<span class="game-name">Бонусы</span>
</div>
<div class="cont-a">
	<?php if($line): ?>
	<div class="bonus">
		<div class="title">Получить бонус можно 1 раз в 24 часа.</div>
		<div class="desc">Для получения ежедневного бонуса Вам нужно состоять в нашей группе.</div>
		<div class="line">
			<div class="cooldown" style="<?php echo e($check ? '' : 'display: none;'); ?>">
				<div class="title">Бонус выдан</div>
			</div>
			<ul class="carousel" id="bonus_carousel">
				<?php $__currentLoopData = $line; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li>
						<div class="sum"><?php echo e($l['sum']); ?></div>
						<div class="background" style="background: <?php echo e($l['color']); ?>"></div>
						<div class="bottom" style="background: <?php echo e($l['color']); ?>"><?php echo e(trans_choice('монета|монеты|монет', $l['sum'])); ?></div>
					</li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</ul>
			<div class="picker"></div>
		</div>
		<?php echo NoCaptcha::renderJs(); ?>

		<?php echo NoCaptcha::display(); ?>

		<div class="button">
			<a class="getBonus">Получить бонус</a>
		</div>
	</div>
	<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>