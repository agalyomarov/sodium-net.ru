@extends('layout')

@section('content')
<script src="https://vk.com/js/api/openapi.js?158" type="text/javascript"></script>
<div class="head-game">
	<span class="game-name">Бонусы</span>
</div>
<div class="cont-a">
	@if($line)
	<div class="bonus">
		<div class="title">Получить бонус можно 1 раз в 24 часа.</div>
		<div class="desc">Для получения ежедневного бонуса Вам нужно состоять в нашей группе.</div>
		<div class="line">
			<div class="cooldown" style="{{ $check ? '' : 'display: none;' }}">
				<div class="title">Бонус выдан</div>
			</div>
			<ul class="carousel" id="bonus_carousel">
				@foreach($line as $l)
					<li>
						<div class="sum">{{$l['sum']}}</div>
						<div class="background" style="background: {{$l['color']}}"></div>
						<div class="bottom" style="background: {{$l['color']}}">{{trans_choice('монета|монеты|монет', $l['sum'])}}</div>
					</li>
				@endforeach
			</ul>
			<div class="picker"></div>
		</div>
		{!! NoCaptcha::renderJs() !!}
		{!! NoCaptcha::display() !!}
		<div class="button">
			<a class="getBonus">Получить бонус</a>
		</div>
	</div>
	@endif
</div>
@endsection