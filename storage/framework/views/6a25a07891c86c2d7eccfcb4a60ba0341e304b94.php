

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(asset('assets/js/coin.js?v=3')); ?>"></script>
<?php if($settings->dep_perc): ?>
<div class="alert alert-red">
	<span class="alert-close" data-close="alert" title="Закрыть">×</span>
	<center>
		<strong style="font-weight: 100;">При пополнении от 100р. введите промокод <?php echo e($settings->dep_code); ?> и получите +<?php echo e($settings->dep_perc); ?>%!</strong>
	</center>
</div>
<?php endif; ?>
<div class="coin">
	<?php if(auth()->guard()->check()): ?>
	<div class="second-title"><span>Создать новую игру</span></div>
	<?php echo $__env->make('../components/bet-form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php endif; ?>
	<div class="second-title"><span>Активные игры</span></div>
	<ul class="games">
		<?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li class="flip_<?php echo e($room['id']); ?>">
			<div class="game">
				<div class="user">
					<div class="avatar"><img src="<?php echo e($room['avatar']); ?>" alt=""></div>
					<div class="username"><?php echo e($room['username']); ?></div>
				</div>
				<div class="bet">Ставка: <span><?php echo e($room['price']); ?> <i class="fas fa-coins"></i></span></div>
				<div class="button"><a class="joinGame" onclick="joinRoom(<?php echo e($room['id']); ?>)">Играть</a></div>
			</div>
		</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
	<div class="second-title"><span>Завершенные игры</span></div>
	<ul class="last">
		<?php $__currentLoopData = $ended; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $end): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li class="flip-block_<?php echo e($end->id); ?>">
			<div class="title">Игра #<?php echo e($end->id); ?> <div class="bank">Банк: <span><?php echo e($end->price); ?> <i class="fas fa-coins"></i></span></div></div>
			<div class="gameBlock">
				<div class="left">
					<div class="avatar"><img src="<?php echo e(\App\User::find($end->user1)->avatar); ?>" alt=""></div>
					<div class="username"><?php echo e(\App\User::find($end->user1)->username); ?></div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #<?php echo e($end->from1); ?>-<?php echo e($end->to1); ?></div>
				</div>
				<div class="vs">VS</div>
				<div class="right">
					<div class="username"><?php echo e(\App\User::find($end->user2)->username); ?></div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #<?php echo e($end->from2); ?>-<?php echo e($end->to2); ?></div>
					<div class="avatar"><img src="<?php echo e(\App\User::find($end->user2)->avatar); ?>" alt=""></div>
				</div>
				<div class="center">
					<div id="timer_<?php echo e($end->id); ?>" style="display: none;"><div class="time"><span id="count_num_<?php echo e($end->id); ?>">0</span></div></div>
					<div id="coin-flip-cont_<?php echo e($end->id); ?>" style="">
						<div id="coin_<?php echo e($end->id); ?>">
                        	<div class="front winner_a"><img src="<?php echo e(\App\User::find($end->winner_id)->avatar); ?>"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="check-random">
				<a href="/fair/<?php echo e($end->hash); ?>" class="btn btn-white btn-sm btn-right">Проверить</a>
			</div>
			<div class="bottom">
				<div class="win">Счастливый билет: <span><i class="fas fa-ticket-alt"></i> <?php echo e($end->winner_ticket); ?></span></div>
			</div>
		</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>