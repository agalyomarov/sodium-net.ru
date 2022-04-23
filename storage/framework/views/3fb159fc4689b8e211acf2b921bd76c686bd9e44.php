

<?php $__env->startSection('content'); ?>
<div class="head-game">
	<span class="game-name">Double</span>
	<ul>
		<li><a href="/double">Играть</a></li>
	</ul>
</div>
<div class="cont-a">
	<div class="second-title"><span>История игр</span></div>
	<ul class="double-history">
		<?php $__currentLoopData = $games; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<li>
				<div class="game">Игра #<?php echo e($game->id); ?></div>
				<div class="number" <?php if($game->winner_color == 'red'): ?> style="background-color:#dd4137;" <?php elseif($game->winner_color == 'green'): ?> style="background-color:#56c05a;" <?php else: ?> style="background-color:#32383b;" <?php endif; ?>><?php echo e($game->winner_num); ?></div>
				<div class="check-random">
					<a href="/fair/<?php echo e($game->hash); ?>" class="btn btn-white btn-sm btn-right">Проверить</a>
				</div>
			</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>