

<?php $__env->startSection('content'); ?>
<div class="head-game">
	<span class="game-name">Перевод монет</span>
</div>
<div class="cont-b">
	<div class="ref">
		<div class="info">
			<h3 class="title">Хотите попросить монеты у друзей?</h3>
			<div class="desc">Скопируйте ваш ID и отправьте его своему другу!</div>
		</div>
		<div class="code">
			<div class="code-title">Ваш уникальный ID:</div>
			<div class="value">
				<input type="text" value="<?php echo e($u->user_id); ?>" id="userID" readonly="">
				<i class="fas fa-copy tooltip tooltipstered" onclick="copyToClipboard('#userID')"></i>
			</div>
		</div>
		<div class="info">
			<h3 class="title">Перевод монет</h3>
			<div class="desc">Для перевода монет игроку вам достаточно знать его уникальный ID</div>
		</div>
		<div class="code">
			<div class="code-title">Введите ID получателя:</div>
			<div class="value">
				<input type="text" placeholder="Уникальный идентификатор" class="targetID">
			</div>
		</div>
		<div class="code">
			<div class="code-title">Cумму перевода:</div>
			<div class="value">
				<input type="text" placeholder="Желаемая сумма" class="sum" id="sumToSend">
			</div>
		</div>
		<div class="info">
			<h3 class="title">Будет списанно: <span id="minusSum">0</span> <i class="fas fa-coins"></i></h3>
			<h3 class="title" style="font-size: 12px; color: #949494;">(комиссия 5%)</h3>
		</div>
		<div class="info">
			<div class="desc">
				Минимальная сумма перевода 20 монет<br>
				Для выполнения перевода нужно сделать вывод минимум на 250 рублей
			</div>
		</div>
		<a class="btn sendButton">ПЕРЕВЕСТИ</a>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>