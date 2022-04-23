

<?php $__env->startSection('content'); ?>
<script src="https://d3js.org/d3-path.v1.min.js"></script>
<script src="https://d3js.org/d3-shape.v1.min.js"></script>
<script src="<?php echo e(asset('/assets/js/battle.js?v=3')); ?>"></script>

<div class="battle">
	<div class="main-block flex" style="height: 100%;">
		<div class="roulette no-copy">
			<div class="counter flex">
				<span id="timer" style="-webkit-animation: blink 2s linear infinite; animation: blink 2s linear infinite"><i class="fas fa-hourglass-start"></i></span>
			</div>
			<svg class="UsersInterestChart" width="400" height="400">
				<g class="chart" transform="translate(200, 200)">
					<g class="timer" transform="translate(0,0)">
						<g class="bets" id="circle" style="transition: ; transform: rotate(0deg);">
							<path id="blue" fill="#640cab" stroke-width="5px" d="M1.1021821192326179e-14,-180A180,180,0,1,1,1.1021821192326179e-14,180L9.491012693391987e-15,155A155,155,0,1,0,9.491012693391987e-15,-155Z" transform="rotate(0)" style="opacity: 1;"></path>
							<path id="red" fill="#ffc200" stroke-width="5px" d="M1.1021821192326179e-14,180A180,180,0,1,1,-3.3065463576978534e-14,-180L-2.847303808017596e-14,-155A155,155,0,1,0,9.491012693391987e-15,155Z" transform="rotate(0)" style="opacity: 1;"></path>
						</g>
					</g>
				</g>
				<polygon points="200,30 220,80 180,80" style="fill: #24304b;stroke: #b58ff7;stroke-width:2;"></polygon>
			</svg>
			<div class="counter" style="background-color: #fff; border-radius: 200px; z-index: -999; width: 360px; height: 360px; top: 20px; left: 20px">

			</div>
		</div>
	</div>
	<div class="hash">HASH: <span id="hash"><?php echo e($game->hash); ?></span></div>

	<div class="block">
		<div class="flex">
			<div id="red_persent" class="fair-item no-copy" style="color: #ffc200;"><?php echo e($chances[0]); ?>%</div>
			<div id="blue_persent" class="fair-item no-copy" style="color: #640cab;"><?php echo e($chances[1]); ?>%</div>
		</div>
		<div class="flex">
			<div class="fair-item-bottom">
				1-<span id="red_tickets"><?php echo e($tickets[0]); ?></span>
			</div>
			<div class="fair-item-bottom">
				<span id="blue_tickets"><?php echo e($tickets[1]); ?></span>-1000
			</div>
		</div>
	</div>
	<!-- <div class="second-title"><span>Последние игры</span></div> -->
	<div class="battle-last" id="history">
		<?php $__currentLoopData = $lastwins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $game): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<a href="/fair/<?php echo e($game->hash); ?>" class="battle-last-i <?php echo e($game->winner_team); ?>"></a>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
	<?php if(auth()->guard()->check()): ?>
	<?php echo $__env->make('../components/bet-form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<div class="flex">
		<div class="button makeBet" style="background-color: #ffc200; margin-right: 10px; margin-top: 8px; margin-bottom: 8px;" onclick="bet('red')">
			<i class="fas fa-crosshairs icon"></i>Поставить
		</div>
		<div class="button makeBet" style="background-color: #640cab; margin-left: 10px; margin-top: 8px; margin-bottom: 8px;" onclick="bet('blue')">
			<i class="fas fa-crosshairs icon"></i>Поставить
		</div>
	</div>
	<?php endif; ?>
	<div class="game">
		<div class="bets">
			<div class="flex" style="justify-content: space-evenly;">
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;"><i class="fas fa-piggy-bank" style="color: #ffc200; cursor: pointer; margin-right: 10px;" data-toggle="tooltip" title="" data-original-title="Банк золотой команды"></i><span id="red_sum"><?php echo e($bank[0]); ?></span></h1>
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;" id="red_factor"><?php echo e($factor[0] ? 'x'.$factor[0] : '<i class="fas fa-question"></i>'); ?></h1>
			</div>
			<div class="line" style="background-color: #ffc200"></div>
			<div class="list" id="red_list">
				<?php $__currentLoopData = $bets->where('color', 'red'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="list-item flex" style="justify-content: left;">
					<div class="ava" style="background-image: url(<?php echo e($b->user->avatar); ?>);"></div>
					<div class="name"><?php echo e($b->user->username); ?></div>
					<div class="sum"><?php echo e($b->price); ?></div>
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		</div>
		<div class="bets">
			<div class="flex" style="justify-content: space-evenly;">
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;" id="blue_factor"><?php echo e($factor[1] ? 'x'.$factor[1] : '<i class="fas fa-question"></i>'); ?></h1>
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;"><i class="fas fa-piggy-bank" style="color: #640cab; cursor: pointer; margin-right: 10px;" data-toggle="tooltip" title="" data-original-title="Банк фиолетовой команды"></i><span id="blue_sum"><?php echo e($bank[1]); ?></span></h1>
			</div>
			<div class="line" style="background-color: #640cab"></div>
			<div class="list" id="blue_list">
				<?php $__currentLoopData = $bets->where('color', 'blue'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="list-item flex" style="justify-content: left;">
					<div class="ava" style="background-image: url(<?php echo e($b->user->avatar); ?>);"></div>
					<div class="name"><?php echo e($b->user->username); ?></div>
					<div class="sum"><?php echo e($b->price); ?></div>
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		</div>
    </div>
</div>
<script type="text/javascript">
	var sitename = '⌛ <?php echo e($settings->sitename); ?>';
	$(document).ready(function() {
		build(<?php echo e($chances[1] / 100); ?>);
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>