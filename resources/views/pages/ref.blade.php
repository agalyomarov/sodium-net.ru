@extends('layout')

@section('content')
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
				<input type="text" value="{{$u->affiliate_id}}" id="code" readonly>
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
		<div class="ref lvl">
			<div class="lvl-title">Ваш реферальный уровень:</div>
			<div class="value">
				<div class="lvl-bar">
					<span class="lvl-up">{{$lvl}}</span>
					<span class="lvl-bar-fill" style="width: {{$width}}%"></span>
				</div>
			</div>
			@if($ref)
			<div class="desc">Вы пригласили {{$ref}} человек и получаете {{$perc}}% от выиграных ставок.</div>
			@else
			<div class="desc">Еще ни кто не ввел Ваш реферальный код.</div>
			@endif
		</div>
		<div class="moneyRef">
			<div class="to-get">Доступно для получения: <span>{{floor($u->ref_money)}}</span> <i class="fas fa-coins"></i></div>
			<div class="total">Всего заработано: <span>{{floor($u->ref_money_history)}}</span> <i class="fas fa-coins"></i></div>
			@if($u->ref_money > 0.99)
			<a class="getMoney">Забрать</a>
			@endif
		</div>
	</div>


</div>
@endsection