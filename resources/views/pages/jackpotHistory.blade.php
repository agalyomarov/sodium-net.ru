@extends('layout')

@section('content')
<div class="head-game">
	<span class="game-name">Jackpot</span>
	<ul>
		<li><a href="/">Играть</a></li>
	</ul>
</div>
<div class="cont-a">
	<div class="rooms">
		<div class="rooms-title">Выберите комнату</div>
		<ul class="room-selector">
			@foreach($rooms as $r)
			<li class="room">
				<a>
					<div class="room-name">{{$r->title}}</div>
				</a>
			</li>
			@endforeach
		</ul>
	</div>
	<div class="second-title"><span>История игр</span></div>
	@foreach($rooms as $r)
	<ul class="historyTable">
		@foreach($history as $game) @if($game['room'] == $r->name)
			<li>
				<div class="game">Игра #{{$game['game_id']}}</div>
				<div class="show"><a href="/jackpot/history/game/{{$game['room']}}/{{$game['game_id']}}"><i class="fas fa-eye"></i></a></div>
				<div class="user">
					<div class="avatar"><img src="{{$game['winner_avatar']}}" alt=""></div>
					<div class="username">{{$game['winner_name']}}</div>
				</div>
				<div class="win">Выигрыш: <span>{{$game['winner_sum']}} <i class="fas fa-coins"></i></span></div>
				<div class="ticket">Билет: <span><i class="fas fa-ticket-alt"></i> {{$game['winner_ticket']}}</span></div>
				<div class="chance">Шанс: <span>{{$game['winner_chance']}}%</span></div>
				<div class="checkApi">
					<a href="/fair/{{$game['hash']}}" class="btn">Проверить</a>
				</div>
			</li>
		@endif @endforeach
	</ul>
	@endforeach
</div>
@endsection