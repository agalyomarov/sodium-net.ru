@extends('layout')

@section('content')
<script src="{{ asset('/assets/js/roulette.js?v=3') }}"></script>

<div class="double-loop">
	<div class="flex-wrap">
		<div class="double">
			<div class="double-timer">
				<div class="double-time is-countdown" id="timer3">
					<span class="countdown-row countdown-show1">
						<span class="countdown-section">
							<span class="countdown-amount" id="rez-numbr">{{$settings->roulette_timer}}</span>
							<span class="countdown-period"></span>
						</span>
					</span>
				</div>
				<span class="double-text">осталось</span>
			</div>
			<div class="double-rel" style="background: none; display: none;"></div>
			<div class="double-win"><img src="/assets/images/right-arrow.svg" alt=""></div>
			<div class="double-row" id="reletteact" style="transform: rotate({{ $rotate }}deg); transition: -webkit-transform 10000ms cubic-bezier(0.32, 0.64, 0.45, 1);"><img src="/assets/images/double-row.png" alt=""></div>
					</div>
		@auth
		<div class="double-right">
			<div class="bet-input">
				<div class="value">
					<input inputmode="numeric" type="text" id="amount" autocomplete="off" placeholder="1 - 50000">
					<i class="myicon-coins"></i>
				</div>
				<div class="upper">
					<a data-value="1" data-method="plus">+1</a>
					<a data-value="10" data-method="plus">+10</a>
					<a data-value="100" data-method="plus">+100</a>
					<a data-value="1000" data-method="plus">+1k</a>
					<a data-value="2" data-method="divide">1/2</a>
					<a data-value="2" data-method="multiply">x2</a>
					<a data-method="clear"><i class="fas fa-trash-alt"></i></a>
				</div>
			</div>

			<div class="double-button">
                <a class="double-button-1 betButton" data-bet-type="red"><span class="bet-amount">0</span></a>
                <a class="double-button-2 betButton" data-bet-type="green"><span class="bet-amount">0</span></a>
                <a class="double-button-3 betButton" data-bet-type="black"><span class="bet-amount">0</span></a>
            </div>
		</div>
		@endauth
	</div>

	@if($game->status == 2)
        <script>
            setTimeout(() => {
                $('#reletteact').css({
                    'transition' : 'transform {{ $time }}s ease',
                    'transform' : 'rotate({{ $rotate2 }}deg)'
                });
            }, 1);
            setTimeout(() => {
                $('#rez-numbr').text('{{ $game->winner_num }}');
            }, parseInt('{{ $time }}')*1000);
        </script>
    @endif
	</div>
	<div class="hash">HASH: <span id="hash">{{$game->hash}}</span></div>
	<div class="double-last">
	@foreach($history as $l)
	<span class="double-last-history_margined double-history-result double-history-result_medium double-history-result_{{ $l->winner_color }}">
			<a href="/fair/{{$l->hash}}" class="double-history-result__number">{{ $l->winner_num }}</a>
		</span>

	@endforeach
	</div>




	<div class="rates-full">
		<div class="rates-loop" data-type="red">
			<div class="rates-top yellow"><div>ОБЩАЯ СТАВКА: <span class="bet" id="bank_red">{{ (isset($prices['red'])) ? $prices['red'] : 0 }}</span></div></div>
			<div class="rates-content_red bets">
				@foreach($bets as $bet) @if($bet->type == 'red')
				<div class="rates-i" data-userid="{{ $bet->user_id }}">
					<div class="rates-ava">
						<img class="avatar" src="{{ $bet->avatar }}">
					</div>
					<div class="hidden">
						<div class="rates-login"><b class="ell">{{ $bet->username }}</b></div>
						<div class="rates-rub">{{ $bet->value }}</div>
					</div>
				</div>
			@endif @endforeach
			  			</div>
		</div>
		<div class="rates-loop" data-type="green">
			<div class="rates-top green"><div>ОБЩАЯ СТАВКА: <span class="bet" id="bank_green">{{ (isset($prices['green'])) ? $prices['green'] : 0 }}</span></div></div>
			<div class="rates-content_green bets">
				@foreach($bets as $bet) @if($bet->type == 'green')
				<div class="rates-i" data-userid="{{ $bet->user_id }}">
					<div class="rates-ava">
						<img class="avatar" src="{{ $bet->avatar }}">
					</div>
					<div class="hidden">
						<div class="rates-login"><b class="ell">{{ $bet->username }}</b></div>
						<div class="rates-rub">{{ $bet->value }}</div>
					</div>
				</div>
			@endif @endforeach
			  			</div>
		</div>
		<div class="rates-loop" data-type="black">
			<div class="rates-top black"><div>ОБЩАЯ СТАВКА: <span class="bet" id="bank_black">{{ (isset($prices['black'])) ? $prices['black'] : 0 }}</span></div></div>
			<div class="rates-content_black bets">
			 				@foreach($bets as $bet) @if($bet->type == 'black')
				<div class="rates-i" data-userid="{{ $bet->user_id }}">
					<div class="rates-ava">
						<img class="avatar" src="{{ $bet->avatar }}">
					</div>
					<div class="hidden">
						<div class="rates-login"><b class="ell">{{ $bet->username }}</b></div>
						<div class="rates-rub">{{ $bet->value }}</div>
					</div>
				</div>
			@endif @endforeach
			 			</div>
		</div>
	</div>
</div>
    </div>


@endsection
