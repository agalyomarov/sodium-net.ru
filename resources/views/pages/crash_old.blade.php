@extends('layout')
 
@section('content')
@if($bet)
<script>
	window.bet = parseInt('{{ $bet->price }}');
	window.isCashout = false;
	window.withdraw = parseFloat('{{ $bet->withdraw }}');
</script>
@endif
<script src="{{ asset('assets/js/jquery.flot.min.js') }}"></script>
<script src="{{ asset('assets/js/crash.js?v=2') }}"></script>

<div class="crash-game">
	<div class="crash-block">
		<div class="crash">
			<div class="chart" id="chart" style="height:200px;"></div>
			<div class="chart-info">Загрузка</div>
		</div>
	</div>
	<div class="second-title"><span>история</span></div>
	<div class="game-history">
		<div class="history">
			@foreach($history as $m)
			<a href="/fair/{{$m->secret}}"><div class="item" style="color: {{ $m->color }}; border-color: {{ $m->color }};">{{ number_format($m->multiplier, 2, '.', '') }}x</div></a>
			@endforeach
		</div>
	</div>
	<div class="hash">HASH: <span id="hash_small">{{ $game['hash'] }}</span></div>
	@auth
	<div class="bet-input">
		<div class="value" style="@if(!is_null($bet)) display : none; @endif">
			<input type="text" class="bet-amount" id="amount">
			<i class="fas fa-coins"></i>
		</div>
		<div class="autoout" style="@if(!is_null($bet)) display : none; @endif">
			<input type="text" value="2.0" id="betout" class="bet-cashout">
			<i class="fas fa-asterisk"></i>
		</div>
		<div class="upper" style="@if(!is_null($bet)) display : none; @endif">
			<a data-value="1" data-method="plus">+1</a>
			<a data-value="10" data-method="plus">+10</a>
			<a data-value="100" data-method="plus">+100</a>
			<a data-method="last"><i class="fas fa-sync-alt"></i></a>
			<a data-method="clear"><i class="fas fa-trash-alt"></i></a>
		</div>
			<!-- <a class="makeBet withdraw-button">Вывести 0</a>
			<a class="makeBet bet-button" style="display:none;">Поставить</a> -->
			<a class="makeBet bet-button" style="@if(!is_null($bet)) display : none; @endif">Поставить</a>
			<a class="makeBet withdraw-button" style="@if(is_null($bet)) display : none; @endif">Вывести</a>
	</div>
	@endauth
	<div class="second-title">Ставок в игре: <span class="players_bet">{{ count($game['bets']) }}</span> на сумму <span class="players_sum">{{ $game['price'] }}</span> <i class="fas fa-coins"></i></div>
	<ul class="crash-bets" id="bets">
	@foreach($game['bets'] as $bet)
		<li>
			<div class="user">
    <div class="ava"><img class="avatar" src="{{ $bet['user']['avatar'] }}" alt=""></div>
    <div class="info">
        <div class="points"><span class="text-clip">{{ $bet['user']['username'] }}: </span> <span class="bet-sum">{{ $bet['price'] }} <i class="myicon-coins"></i></span></div>
    </div>
    @if($bet['status'] == 1)
    <div class="detail">
        <div class="percent" style="color:{{ $bet['color'] }};">{{ $bet['withdraw'] }}x</div>
        <div class="tickets">{{ $bet['won'] }} <i class="myicon-coins"></i></div>
    </div>
    @else
    <div class="detail"><div class="wait"><i class="far fa-clock"></i></div></div>
    @endif
</div>

		</li>
	@endforeach
	</ul>
</div>
@endsection
