@extends('layout')

@section('content')
<script src="{{ asset('assets/js/coin.js?v=3') }}"></script>
@if($settings->dep_perc)
<div class="alert alert-red">
	<span class="alert-close" data-close="alert" title="Закрыть">×</span>
	<center>
		<strong style="font-weight: 100;">При пополнении от 100р. введите промокод {{$settings->dep_code}} и получите +{{$settings->dep_perc}}%!</strong>
	</center>
</div>
@endif
<div class="coin">
	@auth
	<div class="second-title"><span>Создать новую игру</span></div>
	@include('../components/bet-form')
	@endauth
	<div class="second-title"><span>Активные игры</span></div>
	<ul class="games">
		@foreach($rooms as $room)
		<li class="flip_{{$room['id']}}">
			<div class="game">
				<div class="user">
					<div class="avatar"><img src="{{$room['avatar']}}" alt=""></div>
					<div class="username">{{$room['username']}}</div>
				</div>
				<div class="bet">Ставка: <span>{{$room['price']}} <i class="fas fa-coins"></i></span></div>
				<div class="button"><a class="joinGame" onclick="joinRoom({{$room['id']}})">Играть</a></div>
			</div>
		</li>
		@endforeach
	</ul>
	<div class="second-title"><span>Завершенные игры</span></div>
	<ul class="last">
		@foreach($ended as $end)
		<li class="flip-block_{{$end->id}}">
			<div class="title">Игра #{{$end->id}} <div class="bank">Банк: <span>{{$end->price}} <i class="fas fa-coins"></i></span></div></div>
			<div class="gameBlock">
				<div class="left">
					<div class="avatar"><img src="{{ \App\User::find($end->user1)->avatar }}" alt=""></div>
					<div class="username">{{ \App\User::find($end->user1)->username }}</div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #{{$end->from1}}-{{$end->to1}}</div>
				</div>
				<div class="vs">VS</div>
				<div class="right">
					<div class="username">{{ \App\User::find($end->user2)->username }}</div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #{{$end->from2}}-{{$end->to2}}</div>
					<div class="avatar"><img src="{{ \App\User::find($end->user2)->avatar }}" alt=""></div>
				</div>
				<div class="center">
					<div id="timer_{{$end->id}}" style="display: none;"><div class="time"><span id="count_num_{{$end->id}}">0</span></div></div>
					<div id="coin-flip-cont_{{$end->id}}" style="">
						<div id="coin_{{$end->id}}">
                        	<div class="front winner_a"><img src="{{ \App\User::find($end->winner_id)->avatar }}"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="check-random">
				<a href="/fair/{{$end->hash}}" class="btn btn-white btn-sm btn-right">Проверить</a>
			</div>
			<div class="bottom">
				<div class="win">Счастливый билет: <span><i class="fas fa-ticket-alt"></i> {{$end->winner_ticket}}</span></div>
			</div>
		</li>
		@endforeach
	</ul>
</div>
@endsection
