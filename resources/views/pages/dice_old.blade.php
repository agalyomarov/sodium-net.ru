@extends('layout')

@section('content')
<script src="{{ asset('/assets/js/dice.js?v=5') }}"></script>
 
<div class="dice"> 
	@auth
	<div class="dice-loop">
		<div class="left">
			<div class="bet">
				<div class="title">Введите ставку</div>
				<div class="value">
					<input type="text" id="stavka" oninput="calc()" placeholder="От 10 монет" value="10">
					<i class="fas fa-coins"></i>
				</div>
				<div class="multipler">
					<button type="submit" class="action" data-value="1" data-method="plus">+1</button>
					<button type="submit" class="action" data-value="10" data-method="plus">+10</button>
					<button type="submit" class="action" data-value="100" data-method="plus">+100</button>
					<button type="submit" class="action" data-value="2" data-method="divide">1/2</button>
					<button type="submit" class="action" data-value="2" data-method="multiply">x2</button>
					<button type="submit" class="action" data-method="all">ВСЁ</button>
				</div>
			</div>
			<div class="value">
				<div class="title">Возможный выигрыш</div>
				<div class="val"><span id="win">19</span><i class="fas fa-coins"></i></div>
			</div>
		</div>
		<div class="right">
			<div class="factor">
				<div class="title">Множитель</div>
				<div class="val"><span id="winner">1.98</span>x</div>
			</div>
			<div class="chance">
				<div class="title">Шанс на победу</div>
				<div class="val"><span id="one">50.00</span>%</div>
			</div>
		</div>
		<div class="wrap_range">
			<div class="index__home__indicator__inner index__home__indicator__inner--line" style="display: none;">
				<div class="index__home__indicator__inner__number is-rolling is-hidden " style="transform: translate(-45%, 42px);">
					<div class="index__home__indicator__inner__number__roll is-negative ">
						<img alt="" src="/assets/images/cub.svg"><span>0.00</span>
					</div>
				</div>
			</div>
			<input type="range" oninput="calc()" id="r1" style="background: -webkit-linear-gradient(left, rgb(241, 2, 96) 0%, rgb(241, 2, 96) 50%, rgb(8, 229, 71) 50%, rgb(8, 229, 71) 100%);" min="2" value="50" max="99" step="0.01" class="range">
		</div>
		<div class="wrap">
			<div class="auto-game-block">
				<button id="play" class="btn">Сделать ставку</button>
			</div>
		</div>
		<div class="hash">HASH: <span id="hash">{{$hash}}</span></div>
	</div>
	@endauth
	<div class="second-title"><span>Последние игры</span></div>

	<div class="dice-history">
		<table class="games-table games-table__dice games-table__dice_history" style="display: table">
			<thead>
				<tr class="games-table__header-tr">
					<th class="games-table__header-th">Игрок</th>
					<th class="games-table__header-th">Ставка</th>
					<th class="games-table__header-th">Коэф.</th>
					<th class="games-table__header-th">Число</th>
					<th class="games-table__header-th">Выигрыш</th>
					<th class="games-table__header-th"></th>
				</tr>
			</thead>
			<tbody id="lastGame">

				@foreach($game as $g)
				<center>
				<tr class="show games-table__body-tr">
        <td class="games-table__body-td games-table__body-td_low_padding">
            <div class="flex-align games-table__dice_history__name"> <span>{!!$g['username']!!}</span> </div>
        </td>
        <td class="games-table__body-td games-table__money"> <span class="myicon-coins"></span> {!!$g['sum']!!} </td>
        <td class="games-table__body-td">{{$g['num']}}</td>
        <td class="games-table__body-td">{{$g['vip']}}x</td>
        <td class="games-table__body-td games-table__money games-table__money_{{ $g['win'] ? 'win' : 'lose' }}">{{ $g['win'] ? '+'.$g['win_sum'] : $g['win_sum'] }} <span class="myicon-coins"></span></td>
        <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/{{$g['hash']}}" class="tooltip tooltipstered" target="_blank"><span class="myicon-security"></span></a></td>
    </tr>
</center>
				@endforeach

			</tbody>
		</table>
	</div>
</div>
@endsection
