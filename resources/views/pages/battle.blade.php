@extends('layout')

@section('content')
<script src="https://d3js.org/d3-path.v1.min.js"></script>
<script src="https://d3js.org/d3-shape.v1.min.js"></script>
<script src="{{ asset('/assets/js/battle.js?v=3') }}"></script>

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
	<div class="hash">HASH: <span id="hash">{{$game->hash}}</span></div>

	<div class="block">
		<div class="flex">
			<div id="red_persent" class="fair-item no-copy" style="color: #ffc200;">{{$chances[0]}}%</div>
			<div id="blue_persent" class="fair-item no-copy" style="color: #640cab;">{{$chances[1]}}%</div>
		</div>
		<div class="flex">
			<div class="fair-item-bottom">
				1-<span id="red_tickets">{{$tickets[0]}}</span>
			</div>
			<div class="fair-item-bottom">
				<span id="blue_tickets">{{$tickets[1]}}</span>-1000
			</div>
		</div>
	</div>
	<!-- <div class="second-title"><span>Последние игры</span></div> -->
	<div class="battle-last" id="history">
		@foreach($lastwins as $game)
			<a href="/fair/{{$game->hash}}" class="battle-last-i {{ $game->winner_team }}"></a>
		@endforeach
	</div>
	@auth
	@include('../components/bet-form')
	<div class="flex">
		<div class="button makeBet" style="background-color: #ffc200; margin-right: 10px; margin-top: 8px; margin-bottom: 8px;" onclick="bet('red')">
			<i class="fas fa-crosshairs icon"></i>Поставить
		</div>
		<div class="button makeBet" style="background-color: #640cab; margin-left: 10px; margin-top: 8px; margin-bottom: 8px;" onclick="bet('blue')">
			<i class="fas fa-crosshairs icon"></i>Поставить
		</div>
	</div>
	@endauth
	<div class="game">
		<div class="bets">
			<div class="flex" style="justify-content: space-evenly;">
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;"><i class="fas fa-piggy-bank" style="color: #ffc200; cursor: pointer; margin-right: 10px;" data-toggle="tooltip" title="" data-original-title="Банк золотой команды"></i><span id="red_sum">{{$bank[0]}}</span></h1>
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;" id="red_factor">{{ $factor[0] ? 'x'.$factor[0] : '<i class="fas fa-question"></i>' }}</h1>
			</div>
			<div class="line" style="background-color: #ffc200"></div>
			<div class="list" id="red_list">
				@foreach($bets->where('color', 'red') as $b)
				<div class="list-item flex" style="justify-content: left;">
					<div class="ava" style="background-image: url({{$b->user->avatar}});"></div>
					<div class="name">{{$b->user->username}}</div>
					<div class="sum">{{$b->price}}</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="bets">
			<div class="flex" style="justify-content: space-evenly;">
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;" id="blue_factor">{{ $factor[1] ? 'x'.$factor[1] : '<i class="fas fa-question"></i>' }}</h1>
				<h1 style="text-align: center; font-weight: bold; position: relative; width: 50%;"><i class="fas fa-piggy-bank" style="color: #640cab; cursor: pointer; margin-right: 10px;" data-toggle="tooltip" title="" data-original-title="Банк фиолетовой команды"></i><span id="blue_sum">{{$bank[1]}}</span></h1>
			</div>
			<div class="line" style="background-color: #640cab"></div>
			<div class="list" id="blue_list">
				@foreach($bets->where('color', 'blue') as $b)
				<div class="list-item flex" style="justify-content: left;">
					<div class="ava" style="background-image: url({{$b->user->avatar}});"></div>
					<div class="name">{{$b->user->username}}</div>
					<div class="sum">{{$b->price}}</div>
				</div>
				@endforeach
			</div>
		</div>
    </div>
</div>
<script type="text/javascript">
	var sitename = '⌛ {{$settings->sitename}}';
	$(document).ready(function() {
		build({{ $chances[1] / 100 }});
	});
</script>
@endsection
