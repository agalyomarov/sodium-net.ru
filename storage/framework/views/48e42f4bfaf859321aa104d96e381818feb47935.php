

<?php $__env->startSection('content'); ?>
<div class="head-game">
	<span class="game-name">История счета</span>
</div>
<div class="cont-b">
	<div class="second-title"><span>Активные запросы</span></div>
	<?php if($active != '[]'): ?>
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Система<br>Кошелек</th>
					<th>Сумма</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><div class="id"><?php echo e($a->id); ?></div></td>
					<td><div class="system"><?php echo e($a->wallet); ?> (<?php echo e($a->system); ?>)</div></td>
					<td><div class="sum <?php echo e($a->status ? 'ok' : 'dec'); ?>"><?php if($a->status == 2): ?> +<?php echo e($a->value); ?> <?php else: ?> -<?php echo e($a->value); ?> <?php endif; ?></div></td>
					<td><div class="status"><a class="buttoninzc" href="/withdraw/cancel/<?php echo e($a->id); ?>"><i class="fas fa-times"></i></a></div></td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
	<?php endif; ?>
	<div class="second-title"><span>История пополнений</span></div>
	<?php if($pays != '[]'): ?>
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Тип</th>
					<th>Система<br>Кошелек</th>
					<th>Сумма</th>
					<th>Статус</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $pays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><div class="id"><?php echo e($pay->id); ?></div></td>
					<td><div class="type ok"><?php if($pay->status == 1): ?> Пополнение баланса <?php elseif($pay->status == 2): ?> Реферальный код <?php else: ?> Промокод <?php endif; ?></div></td>
					<td><div class="system"><?php if($pay->status == 1): ?> Free-kassa <?php else: ?> <?php echo e($pay->code); ?> <?php endif; ?></div></td>
					<td><div class="sum ok">+<?php echo e($pay->price); ?></div></td>
					<td><div class="status ok">Выполнен</div></td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
	<?php endif; ?>
	<div class="second-title"><span>История выводов</span></div>
	<?php if($withdraws != '[]'): ?>
	<div class="payHistory">
		<table class="list">
			<thead>
				<tr>
					<th>Номер</th>
					<th>Тип</th>
					<th>Система<br>Кошелек</th>
					<th>Сумма</th>
					<th>Статус</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $with): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><div class="id"><?php echo e($with->id); ?></div></td>
					<td><div class="type <?php echo e($with->status ? 'ok' : 'dec'); ?>">Вывод</div></td>
					<td><div class="system"><?php echo e($with->system); ?></div></td>
					<td><div class="sum <?php echo e($with->status ? 'ok' : 'dec'); ?>"><?php if($with->status == 2): ?> +<?php echo e($with->value); ?> <?php else: ?> -<?php echo e($with->value); ?> <?php endif; ?></div></td>
					<td><div class="status <?php echo e($with->status ? 'ok' : 'dec'); ?>"><?php if($with->status == 0): ?> На модерации <?php elseif($with->status == 1): ?> Выполнен <?php else: ?> Возвращен <?php endif; ?></div></td>
				</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	</div>
	<?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>