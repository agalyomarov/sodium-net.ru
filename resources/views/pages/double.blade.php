@extends('layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.3.0/simplebar.css"
    integrity="sha512-0EGld845fSKUDzrxFGdF//lic4e8si6FTrlLpOlts8P0ryaV8XkWy/AnwH9yk0G1wHcOvhY9L14W5LCMWa7W+Q=="
    crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplebar/5.3.0/simplebar.min.js"></script>    
<script src="/assets/js/roulette.js"></script>

<div class="game-container game-container_dice">
    <div class="game-area">
        @auth
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                @include('../components/bet-form')

                <div class="game-sidebar__play-button-wrapper game-sidebar__play-button-wrapper_no-hide">
                    <div class="double-bet-buttons">
                        <button class="game-sidebar__double-bet game-sidebar__double-bet_yellow betButton"
                            data-bet-type="red"><span class="double-bet-sum"><span
                                    class="double-x">x</span>2</span></button>
                        <button class="game-sidebar__double-bet game-sidebar__double-bet_green betButton"
                            data-bet-type="green"><span class="double-bet-sum"><span
                                    class="double-x">x</span>14</span></button>
                        <button class="game-sidebar__double-bet game-sidebar__double-bet_black betButton"
                            data-bet-type="black"><span class="double-bet-sum"><span
                                    class="double-x">x</span>2</span></button>
                    </div>

                </div>

            </div>
            <div class="game-sidebar__footer">
                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_double"><span class="myicon-why"></span></button>
                <button class="game-sidebar__fair-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_double-fair"><span class="myicon-security"></span></button>
            </div>
        </div>
        @endauth

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

        <div class="game-component">
            <div class="game-area game-area_double">
                <div class="game-area-wrapper double-field-wrapper">
                    <div class="double">
                        <div class="double-timer" style="display: flex;">
                            <div class="double-time is-countdown" id="timer3">
                                <span class="countdown-row countdown-show1">
                                    <span class="countdown-section">
                                        <span class="countdown-amount" id="rez-numbr">wait..</span>
                                        <span class="countdown-period"></span>
                                    </span>
                                </span>
                            </div>
                            <span class="double-text">осталось</span>
                        </div>
                        <div class="double-rel" style="background: rgb(255, 194, 0); display: none;">3</div>
                        <div class="double-win"><img src="/assets/images/right-arrow.svg" alt=""></div>
                        <div class="double-row" id="reletteact"
                            style="transform: rotate({{ $rotate }}deg); transition: transform 0s linear 0s;"><img
                                src="/assets/images/rr5.png" alt=""></div>
                    </div>
                </div>

                <div class="game-footer game-footer_double">
                    <div class="double-footer-wrapper" id="hline" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: -15px 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper"
                                        style="height: auto; overflow: scroll hidden;">
                                        <div class="simplebar-content" style="padding: 15px 0px;">
                                            <div class="double-history-line">

                                                @foreach($history as $l)
                                                <div class="double-circle double-circle_{{ $l->winner_color }}">
                                                    <span class="double-circle-inner">
                                                        <a href="/fair/{{$l->hash}}"
                                                            target="_blank" class="double-circle-inner__number">{{ $l->winner_num }}</a>
                                                    </span>
                                                </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 622px; height: 74px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
                            <div class="simplebar-scrollbar"
                                style="width: 152px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="game-container_history">
        <div class="double-history__cols">

            <div class="double-history__col double-history__col_yellow">
                <div class="double-history__col-header">
                    <span class="double-history__col-bets"><span class="myicon-coins"></span> <span
                            class="double-history__col-bets__value double-history__col-bets__value_red">{{ (isset($prices['red'])) ? $prices['red'] : 0 }}</span></span>
                </div>
                <div class="double-history__col-body double-history__col-body_red" style="display: block;">
                    @foreach($bets as $bet) @if($bet->type == 'red')
                    <div class="double-history__col-header-bet__user" data-userid="{{ $bet->user_id }}">
                        <div class="double-history__col-header-bet__photo"> <img
                                src="{{ $bet->avatar }}"
                                class="avatar"> 
                            {{-- <img class="tooltip rank tooltipstered" src="/assets/images/ranks/2.svg" alt="">  --}}
                        </div>
                        <div class="double-history__col-header-bet__row">
                            <div class="double-history__col-header-bet__username">{{ $bet->username }}</div>
                            <div class="double-history__col-header-bet__amount">{{ $bet->value }}</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            <div class="double-history__col double-history__col_green">
                <div class="double-history__col-header">
                    <span class="double-history__col-bets"><span class="myicon-coins"></span> <span
                            class="double-history__col-bets__value double-history__col-bets__value_green">{{ (isset($prices['green'])) ? $prices['green'] : 0 }}</span></span>
                </div>
                <div class="double-history__col-body double-history__col-body_green" style="display: block;">
                    @foreach($bets as $bet) @if($bet->type == 'green')
                    <div class="double-history__col-header-bet__user" data-userid="{{ $bet->user_id }}">
                        <div class="double-history__col-header-bet__photo"> <img src="{{ $bet->avatar }}" class="avatar">
                            {{-- <img class="tooltip rank tooltipstered" src="/assets/images/ranks/2.svg" alt="">  --}}
                        </div>
                        <div class="double-history__col-header-bet__row">
                            <div class="double-history__col-header-bet__username">{{ $bet->username }}</div>
                            <div class="double-history__col-header-bet__amount">{{ $bet->value }}</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            <div class="double-history__col double-history__col_black">
                <div class="double-history__col-header">
                    <span class="double-history__col-bets"><span class="myicon-coins"></span> <span
                            class="double-history__col-bets__value double-history__col-bets__value_black">{{ (isset($prices['black'])) ? $prices['black'] : 0 }}</span></span>
                </div>
                <div class="double-history__col-body double-history__col-body_black" style="display: block;">
                    @foreach($bets as $bet) @if($bet->type == 'black')
                    <div class="double-history__col-header-bet__user" data-userid="{{ $bet->user_id }}">
                        <div class="double-history__col-header-bet__photo"> <img src="{{ $bet->avatar }}" class="avatar">
                            {{-- <img class="tooltip rank tooltipstered" src="/assets/images/ranks/2.svg" alt="">  --}}
                        </div>
                        <div class="double-history__col-header-bet__row">
                            <div class="double-history__col-header-bet__username">{{ $bet->username }}</div>
                            <div class="double-history__col-header-bet__amount">{{ $bet->value }}</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            
        </div>
    </div>
</div>

@endsection
