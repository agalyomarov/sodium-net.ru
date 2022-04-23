@extends('layout')

@section('content')
@if($bet)
<script>
    var game_active = '{{ $gameStatus }}';
    var bet = parseInt('{{ $bet->price }}');
    var isCashout = false;
    var withdraw = parseFloat('{{ $bet->withdraw }}');
</script>
@else
<script>
    var game_active = '{{ $gameStatus }}';
    var bet;
    var isCashout;
    var withdraw;
</script>
@endif
<script src="{{ asset('assets/js/jquery.flot.min.js') }}"></script>
<link rel="stylesheet" href="/assets/css/simplebar.css" />
<script src="/assets/js/simplebar.min.js"></script>
<script src="/assets/js/chart.js"></script>
<script src="/assets/js/crash.js"></script>

<div class="game-container game-container_dice">
    <div class="game-area">
        @auth
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                @include('../components/bet-form')

                <div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin" style="margin-top: 20px;">
                    <label class="game-sidebar__input-label">Автовывод</label>
                    <div class="validation-wrapper">
                        <span class="validation-message"></span>
                        <input type="text" data-helper=".crash-auto-helper" value="2.00" name="coeff" data-what="Коэффициент"
                            id="crash-auto" data-min="1.02" data-max="950000" data-value-on-nonnumeric="2.00" data-precision="2"
                            class="dice-input-coeff float-input-validate game-sidebar__input game-area_dice-input duplicate-on-input select-on-focus show-input-helper"
                            data-duplicate=".game-area_dice-input-sfx_coef" autocomplete="off">
                        {{-- <div class="game-area_dice-input-sfx">
                            <span class="game-area_input-cp game-area_dice-input-sfx_coef">2.00</span>
                            <span class="game-area_dice-input-sfxtxt">x</span>
                        </div> --}}
                    </div>
                    <div class="game-sidebar__input-helper game-sidebar__input-helper_bottom-mobile input-helper crash-auto-helper">
                        <button class="game-sidebar__set-action" data-value="1.20" data-method="set" data-id="crash-auto">1.20</button>
                        <button class="game-sidebar__set-action" data-value="1.50" data-method="set" data-id="crash-auto">1.50</button>
                        <button class="game-sidebar__set-action" data-value="2.00" data-method="set" data-id="crash-auto">2.00</button>
                        <button class="game-sidebar__set-action" data-value="5.00" data-method="set" data-id="crash-auto">5.00</button>
                        <button class="game-sidebar__set-action" data-value="10.00" data-method="set" data-id="crash-auto">10.00</button>
                    </div>
                </div>

                <div class="game-sidebar__play-button-wrapper">
                    <button class="game-sidebar__play-button game-sidebar__play-button_full_width crash-play" disabled="">Играть</button>
                </div>

            </div>
            <div class="game-sidebar__footer">
                <button
                    class="game-sidebar__htp-trigger game-sidebar__footer-button game-sidebar__footer-button_small tooltip show-modal tooltipstered"
                    data-modal=".modal_crash"><span class="myicon-why"></span></button>
                <button
                    class="game-sidebar__fair-trigger game-sidebar__footer-button game-sidebar__footer-button_small tooltip show-modal tooltipstered"
                    data-modal=".modal_crash-fair"><span class="myicon-security"></span></button>
            </div>
        </div>
        @endauth
        <div class="game-component game-component__short">
            <div class="game-area game-area_double">

                <div class="game-area-wrapper crash-field-wrapper">
                    <div class="crash-block">
                        <div class="crash">
                            <div class="chart" id="chart" style="height:200px;"></div>
                            <div class="chart-info">Загрузка</div>
                        </div>
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
                                                
                                                @foreach($history as $m)
                                                <a href="/fair/{{$m->secret}}" target="_blank">
                                                    <div class="crash-item" style="background-color: {{ $m->color }}; border-color: {{ $m->color }};">
                                                        {{ number_format($m->multiplier, 2, '.', '') }}x</div>
                                                </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 622px; height: 63px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: visible;">
                            <div class="simplebar-scrollbar"
                                style="width: 202px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="crash-history" style="padding: 0px;">
        <div class="crash-bets">
            @foreach($game['bets'] as $bet)
            <div class="crash-bet">
                <div class="crash-bet__user-wrapper">
                    <div class="crash-bet__image-wrapper"> 
                    <img src="{{ $bet['user']['avatar'] }}"
                        class="crash-bet__image" style="border-color: #333" alt=""> 
                    </div>
                    <div class="crash-bet__username-wrapper">
                        <div class="crash-bet__username">{{ $bet['user']['username'] }}:</div>
                    </div>
                </div>
                @if(!$bet['status'] == 1)
                <div class="crash-bet__values">
                    <div class="crash-bet__value crash-bet__sum"><span class="myicon-coins"></span> {{ $bet['price'] }}</div>
                    <div class="crash-bet__value crash-bet__coeff">В игре</div>
                    <div class="crash-bet__value crash-bet__win @if(!$bet['status'] == 1) crash-bet__win_hidden @endif">
                        <span class="myicon-coins"></span>
                    </div>
                </div>
                @else
                <div class="crash-bet__values">
                    <div class="crash-bet__value crash-bet__sum"><span class="myicon-coins"></span> {{ $bet['price'] }}</div>
                    <div class="crash-bet__value crash-bet__coeff crash-bet__value crash-bet__coeff_won">{{ $bet['withdraw'] }}x</div>
                    <div class="crash-bet__value crash-bet__win @if(!$bet['status'] == 1) crash-bet__win_hidden @endif"><span class="myicon-coins"></span> {{ $bet['won'] }}</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection