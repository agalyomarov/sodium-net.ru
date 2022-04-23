

<?php $__env->startSection('content'); ?>
<div class="head-game">
	<span class="game-name">Jackpot</span>
	<ul>
		<li><a href="/">Играть</a></li>
	</ul>
</div>
<div class="cont-a">
	<div class="rooms">
		<div class="rooms-title">Выберите комнату</div>
		<ul class="room-selector">
			<?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<li class="room">
				<a>
					<div class="room-name"><?php echo e($r->title); ?></div>
				</a>
			</li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
	</div>
	<div class="second-title"><span>История игр</span></div>
	<?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<ul class="historyTable">
		<?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($game['room'] == $r->name): ?>
			<li>
				<div class="game">Игра #<?php echo e($game['game_id']); ?></div>
				<div class="show"><a href="/jackpot/history/game/<?php echo e($game['room']); ?>/<?php echo e($game['game_id']); ?>"><i class="fas fa-eye"></i></a></div>
				<div class="user">
					<div class="avatar"><img src="<?php echo e($game['winner_avatar']); ?>" alt=""></div>
					<div class="username"><?php echo e($game['winner_name']); ?></div>
				</div>
				<div class="win">Выигрыш: <span><?php echo e($game['winner_sum']); ?> <i class="fas fa-coins"></i></span></div>
				<div class="ticket">Билет: <span><i class="fas fa-ticket-alt"></i> <?php echo e($game['winner_ticket']); ?></span></div>
				<div class="chance">Шанс: <span><?php echo e($game['winner_chance']); ?>%</span></div>
				<div class="checkApi">
					<a href="/fair/<?php echo e($game['hash']); ?>" class="btn">Проверить</a>
				</div>
			</li>
		<?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>