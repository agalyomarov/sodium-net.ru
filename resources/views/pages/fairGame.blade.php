@extends('layout')

@section('content')
<div class="head-game">
	<span class="game-name">Честная игра</span>
</div>
<div class="cont-a">
	<div class="second-title"><span>Введите ваш хэш</span></div>
	<div class="faq">
		<div class="desc fair">
			<input type="text" class="input" id="hash" placeholder="хххххххххххххххх" value="{{$hash}}">
			<a class="btn checkHash">ПРОВЕРИТЬ</a>
			<div class="col" style="display: none;">
				<div class="label">Номер игры</div>
				<input type="text" class="input" id="round" value="" disabled>
			</div>
			<div class="col" style="display: none;">
				<div class="label">Победное число</div>
				<input type="text" class="input" id="number" value="" disabled>
			</div>
		</div>
	</div>
</div>
@endsection