

<?php $__env->startSection('content'); ?>
<div class="head-game">
	<span class="game-name">Jackpot</span>
	<ul>
		<li><a href="/jackpot/history">История</a></li>
		<li><a href="/">Играть</a></li>
	</ul>
</div>
<div class="game-info">
	<div class="game-id">Игра: <span><?php echo e($history->game_id); ?></span></div><span class="fair" rel="popup" data-popup="popup-fair">Честная игра</span>
	<a class="promo-steel" href="/ref">Промокоды</a>
</div>
<div class="jackpot">
	<div class="bank">Jackpot игры: <span><?php echo e($history->price); ?></span><i class="fas fa-coins"></i></div>
	<div class="second-title"><span>Игроки</span></div>
	<ul class="chances" id="chances">
	<?php $__currentLoopData = $historyChance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li class="tooltip" title="<?php echo e($user['username']); ?>">
			<img src="<?php echo e($user['avatar']); ?>" alt="">
			<span><?php echo e($user['chance']); ?>%</span>
			<color style="background: #<?php echo e($user['color']); ?>;"></color>
		</li>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
	<div class="timer">
		<div class="timer-title">До начала</div>
		<div class="timer-bar">
			<div class="time">
				<div class="elements">
					<span class="minsec">00:00</span>
				</div>
			</div>
			<span class="timer-bar-fill" style="width: 0%" id="timeline"></span>  
		</div>
	</div>
	<div class="winner">
		<div class="second-title"><span>Победитель!</span></div>
		<ul>
			<li>
				<div class="chance-w">
					<span class="titles">Шанс выигрыша</span>
					<span class="chance"><?php echo e($history->winner_chance); ?>%</span>
				</div>
			</li>
			<li>
				<div class="winner-w">
					<div class="ava"><img src="<?php echo e($winner->avatar); ?>" alt=""></div>
					<div class="nickname"><?php echo e($winner->username); ?></div>
					<div class="points">Выигрыш: <b><?php echo e($history->winner_sum); ?></b> <i class="fas fa-coins"></i></div>
				</div>
			</li>
			<li>
				<div class="ticket-w">
					<span class="titles">Счастливый билет</span>
					<span class="ticket"><i class="fas fa-ticket-alt"></i> <b><?php echo e($history->winner_ticket); ?></b></span>
				</div>
			</li>
		</ul>
		<div class="check-random">
			<a href="/fair/<?php echo e($history->hash); ?>" class="btn btn-white btn-sm btn-right">Проверить</a>
		</div>
	</div>
	<div class="chouser" id="chouser">
		<div class="second-title"><span>Выбираем победителя</span></div>
		<div class="carousel" style="transform: translate3d(-6727px, 0px, 0px)">
			<?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<p><img src="<?php echo e($m['avatar']); ?>" alt=""><color style="background: #<?php echo e($m['color']); ?>;"></color></p>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
		<div class="picker"></div>
	</div>
	<div class="second-title"><span>Ставки в этой игре</span></div>
	<ul class="bets" id="bets">
		<?php if($historyBets): ?>
		<?php $__currentLoopData = $historyBets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<li>
			<color style="background: #<?php echo e($bet->color); ?>;"></color>
			<div class="user">
				<div class="ava"><img src="<?php echo e($bet->avatar); ?>" alt=""></div>
				<div class="info">
					<div class="nickname"><?php echo e($bet->username); ?></div>
					<div class="points">Поставил: <?php echo e($bet->sum); ?> <i class="fas fa-coins"></i></div>
				</div>
				<div class="detail">
					<div class="percent"><?php echo e($bet->chance); ?>%</div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #<?php echo e(round($bet->from)); ?> - #<?php echo e(round($bet->to)); ?></div>
				</div>
			</div>
		</li>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php endif; ?>
	</ul>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>