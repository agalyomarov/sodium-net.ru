@extends('layout')

@section('content')
<script>
    const ROOM = '{{$room}}';
</script>
<script src="{{ asset('assets/js/jackpot.js?v=5') }}"></script>

<div class="jackpot">
    <div class="room">
        Комната:
        <ul>

            @foreach($rooms as $r)

            <li>
                <a href="?room={{$r->name}}"
                    class="tooltip @if ( $room === $r->name ) active @endif tooltipstered">{{$r->title}}</a>
                <div class="jackpot_room_bank"><span
                        id="roombank_{{$r->name}}">{{ \App\Jackpot::getBank($r->name) }}</span> <i
                        class="myicon-coins"></i> </div>
            </li>

            @endforeach
        </ul>
    </div>
    <div class="bank">Jackpot сейчас: <span class="bank_amount"> <span id="gamebank_{{$room}}">0</span><i
                class="myicon-coins"></i></span></div>
    <div class="second-title"><span>Игроки</span></div>
    <ul class="chances kinetic-active" id="chances_{{$room}}" style="cursor: pointer;">
        @foreach($chances as $user)
        <li class="tooltip tooltipstered" title="{{ $user['username'] }}">
            <img class="avatar" src="{{ $user['avatar'] }}" alt="">
            <span>{{ $user['chance'] }}%</span>
            <color style="background: #{{ $user['color'] }};"></color>
        </li>

        @endforeach
    </ul>
    <div class="timer">
        <div class="timer-title">До начала</div>
        <div class="timer-bar">
            <div class="time">
                <div class="elements">
                    <span class="minsec" id="time_{{$room}}">{{$time[0]}}:{{$time[1]}}</span>
                    <!--<span class="ms">.000</span>-->
                </div>
            </div>
            <span class="timer-bar-fill" style="width: 100%" id="timeline_{{$room}}"></span>
        </div>
    </div>
    <div class="hash">HASH: <span id="hash_{{$room}}">{{$game->hash}}</span></div>
    @auth
    <div class="bet-input">
        <div class="flex-wrap">
            <div class="input-wrap">
                <div class="value">
                    @foreach($rooms as $r)

                    @if ( $room === $r->name )

                    <input type="text" id="amount" placeholder="{{$r->min}} - {{$r->max}}">

                    @endif
                    @endforeach

                    <i class="myicon-coins"></i>
                </div>
                <div class="upper">
                    <a data-value="1" data-method="plus">+1</a>
                    <a data-value="10" data-method="plus">+10</a>
                    <a data-value="100" data-method="plus">+100</a>
                    <a data-value="1000" data-method="plus">+1000</a>
                    <a data-method="last"><i class="fas fa-sync-alt"></i></a>
                    <a data-method="clear"><i class="fas fa-trash-alt"></i></a>
                </div>
            </div>

            <a class="makeBet">Поставить</a>
        </div>
    </div>
    @endauth
    <div class="winner" id="winnerBox_{{$room}}" style="display: none;">
        <div class="second-title"><span>Победитель!</span></div>
        <ul>
            <li>
                <div class="chance-w">
                    <span class="titles">Шанс выигрыша</span>
                    <span class="chance" id="winnerChance_{{$room}}">0%</span>
                </div>
            </li>
            <li>
                <div class="winner-w">
                    <div class="ava"><img src="/assets/images/no_avatar.jpg" alt="" id="winnerAvatar_{{$room}}"></div>
                    <div class="nickname" id="winnerName_{{$room}}">none</div>
                    <div class="points">Выигрыш: <b id="winnerSum_{{$room}}">0</b> <i class="myicon-coins"></i></div>
                </div>
            </li>
            <li>
                <div class="ticket-w">
                    <span class="titles">Счастливый билет</span>
                    <span class="ticket"><i class="fas fa-ticket-alt"></i> <b id="winnerTicket_{{$room}}">0</b></span>
                </div>
            </li>
        </ul>
        <div class="check-random">
            <a id="check_{{$room}}" href="#" class="btn btn-white btn-sm btn-right">Проверить</a>
        </div>
    </div>
    <div class="roulette" id="chouser_{{$room}}" style="display: none;">
        <div class="second-title"><span>Выбираем победителя</span></div>
        <div class="pointer"></div>
        <div class="list">
            <div class="fixed-width">
                <div class="overview" id="carousel_{{$room}}"></div>
            </div>
        </div>
    </div>
    <div class="second-title"><span>Ставки в этой игре</span></div>
    <ul class="bets" id="bets_{{$room}}">
        @if($bets)
        @foreach($bets as $bet)
        <li>
            <color style="background: #{{$bet->color}};"></color>
            <div class="user">
                <div class="ava"><img class="avatar" src="{{$bet->avatar}}" alt=""></div>
                <div class="info">
                    <div class="points"><span class="text-clip">{{ $bet->username }}:</span><span class="bet-sum">
                            {{ $bet->sum }} <i class="myicon-coins"></i></span></div>
                </div>
                <div class="detail">
                    <div class="percent">{{ $bet->chance }}%</div>
                    <div class="tickets"><i class="fas fa-ticket-alt"></i> #{{ round($bet->from) }} -
                        #{{ round($bet->to) }}</div>
                </div>
            </div>
        </li>

        @endforeach
        @endif
    </ul>
</div>


<!--
<div class="rooms">
	<div class="rooms-title">Выберите комнату</div>
	<div class="rooms-desc">Сделай ставку и получи возможность выиграть Джекпот</div>
	<ul class="room-selector">
		@foreach($rooms as $r)
		<li class="room {{$room}}">
			<a href="?room={{$room}}">
				<div class="room-name">{{$r->title}}</div>
				<div class="room-bet">от {{$r->min}}-{{$r->max}} <i class="fas fa-coins"></i></div>
				<div class="room-bank">Банк: <span id="roombank_{{$room}}">{{ \App\Jackpot::getBank($r->name) }}</span> <i class="fas fa-coins"></i></div>
			</a>
		</li>
		@endforeach
	</ul>
</div>
<div class="jackpot">

	<div class="bank"><div class="ico-jackpot"></div>Jackpot сейчас: <span id="gamebank_{{$room}}">{{$game->price}}</span><i class="fas fa-coins"></i></div>
	<div class="second-title"><span>Игроки</span></div>
	<ul class="chances" id="chances_{{$room}}">
	@foreach($chances as $user)
		<li class="tooltip" title="{{ $user['username'] }}">
			<img src="{{ $user['avatar'] }}" alt="">
			<span>{{ $user['chance'] }}%</span>
			<color style="background: #{{ $user['color'] }};"></color>
		</li>
	@endforeach
	</ul>
	<div class="timer">
		<div class="timer-title">До начала</div>
		<div class="timer-bar">
			<div class="time">
				<div class="elements">
					<span class="minsec" id="time_{{$room}}">{{$time[0]}}:{{$time[1]}}</span>
					-->
<!--<span class="ms">.000</span>-->
<!--</div>
			</div>
			<span class="timer-bar-fill" style="width: {{(($time[2]/$time[2])*100)}}%" id="timeline_{{$room}}"></span>
		</div>
	</div>
	<div class="hash">HASH: <span id="hash_{{$room}}">{{$game->hash}}</span></div>
	@auth
	<div class="bet-input">
		<div class="value">
			<input type="text" id="amount">
			<i class="fas fa-coins"></i>
		</div>
		<div class="upper">
			<a data-value="1" data-method="plus">+1</a>
			<a data-value="10" data-method="plus">+10</a>
			<a data-value="100" data-method="plus">+100</a>
			<a data-value="1000" data-method="plus">+1000</a>
			<a data-method="last"><i class="fas fa-sync-alt"></i></a>
			<a data-method="clear"><i class="fas fa-trash-alt"></i></a>
		</div>
		<a class="makeBet">Поставить</a>
	</div>
	@endauth
	<div class="winner" id="winnerBox_{{$room}}" style="display: none;">
		<div class="second-title"><span>Победитель!</span></div>
		<ul>
			<li>
				<div class="chance-w">
					<span class="titles">Шанс выигрыша</span>
					<span class="chance" id="winnerChance_{{$room}}">0%</span>
				</div>
			</li>
			<li>
				<div class="winner-w">
					<div class="ava"><img src="/assets/images/no_avatar.jpg" alt="" id="winnerAvatar_{{$room}}"></div>
					<div class="nickname" id="winnerName_{{$room}}">none</div>
					<div class="points">Выигрыш: <b id="winnerSum_{{$room}}">0</b> <i class="fas fa-coins"></i></div>
				</div>
			</li>
			<li>
				<div class="ticket-w">
					<span class="titles">Счастливый билет</span>
					<span class="ticket"><i class="fas fa-ticket-alt"></i> <b id="winnerTicket_{{$room}}">0</b></span>
				</div>
			</li>
		</ul>
		<div class="check-random">
			<a id="check_{{$room}}" href="#" class="btn btn-white btn-sm btn-right">Проверить</a>
		</div>
	</div>
	<div class="roulette" id="chouser_{{$room}}" style="display: none;">
		<div class="second-title"><span>Выбираем победителя</span></div>
		<div class="pointer"></div>
		<div class="list">
			<div class="fixed-width">
				<div class="overview" id="carousel_{{$room}}"></div>
			</div>
		</div>
	</div>
	<div class="second-title"><span>Ставки в этой игре</span></div>
	<ul class="bets" id="bets_{{$room}}">
		@if($bets)
		@foreach($bets as $bet)
		<li>
			<color style="background: #{{ $bet->color }};"></color>
			<div class="user">
				<div class="ava"><img src="{{ $bet->avatar }}" alt=""></div>
				<div class="info">
					<div class="nickname">{{ $bet->username }}</div>
					<div class="points">Поставил: {{ $bet->sum }} <i class="fas fa-coins"></i></div>
				</div>
				<div class="detail">
					<div class="percent">{{ $bet->chance }}%</div>
					<div class="tickets"><i class="fas fa-ticket-alt"></i> #{{ round($bet->from) }} - #{{ round($bet->to) }}</div>
				</div>
			</div>
		</li>
		@endforeach
		@endif
	</ul>
</div>-->
@endsection