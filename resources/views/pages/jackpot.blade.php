@extends('layout')

@section('content')
<script>
    const ROOM = '{{$room}}';
</script>
<script src="{{ asset('assets/js/jackpot.js?v=5') }}"></script>

<div class="game-container game-container_jackpot">
    <div class="game-area">
        @auth
        <div class="game-sidebar game-sidebar__short">
            <div class="game-sidebar__body">
                @include('../components/bet-form')
                <div class="game-sidebar__play-button-wrapper">
                    <button class="makeBet makeBet__{{$room}} game-sidebar__play-button game-sidebar__play-button_full_width jackpot-create-game">Играть</button>
                </div>
            </div>
            <div class="game-sidebar__footer">
                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_jackpot"><span class="myicon-why"></span></button>
                <button class="game-sidebar__fair-trigger game-sidebar__footer-button tooltip show-modal tooltipstered"
                    data-modal=".modal_jackpot-fair"><span class="myicon-security"></span></button>
            </div>
        </div>
        @endauth
        <div class="game-component game-component__short">
            <div class="jackpot">
                <div class="room">
                    @foreach($rooms as $r)
                        <a href="?room={{$r->name}}" class="jackpot-tab tooltip @if ( $room === $r->name ) active @endif tooltipstered">
                            <span class="jacpot-tab__title">{{$r->title}} 
                            <span class="jackpot-tab-bank" id="roombank_{{$r->name}}">{{ \App\Jackpot::getBank($r->name) }}</span></span>
                        </a>
                    @endforeach
                </div>
                <div class="jackpot-field-wrapper">
                    <div class="bank">Банк: 
                        <span class="bank_amount"> 
                            <span class="gameBank" id="gamebank_{{$room}}">0</span>
                            <i class="myicon-coins"></i>
                        </span>
                    </div>
                    <div class="chances-wrapper">
                        <ul class="chances kinetic-active" id="chances_{{$room}}"
                            style="cursor: grab; transform: translate3d(0px, 0px, 0px); perspective: 1000px; backface-visibility: hidden;">
                            
                            @foreach($chances as $user)
                            <li class="tooltip tooltipstered"> 
                                <img src="{{ $user['avatar'] }}" class="avatar" alt=""> 
                                {{-- <img class="tooltip rank tooltipstered" src="assets/images/ranks/9.svg" alt="">  --}}
                                <span class="chances-field chances-field__money">{{$user['sum']}} 
                                    <span class="myicon-coins"></span>
                                </span>
                                <span class="chances-field">{{ $user['chance'] }}%</span>
                                <color style="background: #{{ $user['color'] }};"></color>
                            </li>
                            @endforeach
                        </ul>
                        <div class="jackpot-preloader jackpot-preloader__{{$room}}"  >
                            <span class="myicon-jack-preview jackpot-preloader__icon"></span>
                            <p>Делайте ставки</p>
                        </div>
                        <div class="outcome-window outcome-window_winner outcome-window_winner__{{$room}} outcome-window_centered"
                            style="display: none;">
                            <span href="" class="winner-label">Победитель</span>
                            <div class="outcome-window_winner-name outcome-window_winner-name__{{$room}}">Даня Николаев
                            </div>
                            <div class="outcome-window_winner-field">Выиграл <span
                                    class="outcome-window_winner-wrapper"><span
                                        class="outcome-window_winner__sum outcome-window_winner__sum__{{$room}}">498</span>
                                    <span class="myicon-coins"></span></span></div>
                            <div class="outcome-window_winner-field">Шанс <span
                                    class="outcome-window_winner-wrapper"><span
                                        class="outcome-window_winner__percent outcome-window_winner__percent__{{$room}}">34.52%</span></span>
                            </div>
                            <div class="outcome-window_winner-tickets"><span class="fas fa-ticket-alt"></span> <span
                                    class="outcome-window_winner-ticket-number__{{$room}}">5112</span></div>
                            {{-- <a href="{{  }}"
                                target="_blank" class="jackpot-fair-check jackpot-fair-check__small">Проверить</a> --}}

                        </div>
                    </div>
                    <div class="timer">
                        <div class="timer-title">До старта</div>
                        <div class="timer-bar">
                            <div class="time">
                                <div class="elements">
                                    <span class="minsec" id="time_{{$room}}">00:30</span>
                                </div>
                            </div>
                            <span class="timer-bar-fill" style="width: 100%;" id="timeline_{{$room}}"></span>
                        </div>
                    </div>
                    <div class="roulette" id="chouser_{{$room}}" style="display: none;">
                        <div class="pointer"></div>
                        <div class="list">
                            <div class="fixed-width">
                                <div class="overview" id="carousel_{{$room}}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="game-container_history" style="padding: 0px;">
        <div class="history-cards history-cards__small" id="bets_{{$room}}" style="display: flex;">
            @if($bets)
            @foreach($bets as $bet)
            <div class="history-card-wrapper">
                <div class="history-card">
                    <div class="history-card__top-side">
                        <div class="history-param_ticket"> <span class="history-param__txt"> #{{ round($bet->from) }} - #{{ round($bet->to) }}</span> </div>
                        <div class="history-card__top-side_image-wrapper">
                            <img src="{{$bet->avatar}}" class="history-card__top-side_image" style="border-color: #{{$bet->color}}" alt=""> 
                                {{-- <img
                                class="tooltip rank history-card__top-side_image-rank tooltipstered"
                                src="/assets/images/ranks/9.svg" alt=""> --}}
                        </div>
                        <div class="history-card__right">
                            <div class="history-card__username">{{ $bet->username }}</div>
                        </div>
                    </div>
                    <div class="history-card__bottom-side">
                        <div class="history-param history-param_money"> 
                            <span class="history-param__icon myicon-coins"></span>
                            <span class="history-param__txt betSum">{{ $bet->sum }}</span> 
                        </div>
                        <div class="history-param history-param_chance"> 
                            <span class="history-param__txt">{{ $bet->chance }}</span>
                            <span class="history-param__icon">%</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>



<div class="modal-window modal_jackpot" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal htp-modal narrow-modal modal-jackpot">
            <div class="heading">Как играть? <a class="myicon-close modal-close"></a></div>
            <div class="htp-content">
                <div class="siema popup-slider" style="overflow: hidden; direction: ltr; cursor: -webkit-grab;">
                    <div
                        style="width: 1120px; transition: all 200ms ease-out 0s; transform: translate3d(0px, 0px, 0px);">
                        <div style="float: left; width: 25%;">
                            <div class="htp-slide" style="padding: 0 5px">
                                <div class="htp-image"><img class="htp-image-img" src="/assets/images/jackpot-htp-1.png"
                                        alt=""></div>
                                <div class="htp-subtitle">Комната</div>
                                <p class="htp-description">Выберите подходящую для игры комнату. Комнаты отличаются
                                    минимальной и максимальной суммой ставки, а также максимальным количеством ставок.
                                </p>
                                <p class="htp-description">
                                    <span style="color: #fff; font-weight: 500">Small</span> —
                                    общая ставка от <span style="color: #ffc200; font-weight: 500">1</span>
                                    до <span style="color: #ffc200; font-weight: 500">300</span> монет.
                                    Максимальное количество ставок — <span style="color: #fff; font-weight: 500">3.
                                    </span></p>
                                <p class="htp-description">
                                    <span style="color: #fff; font-weight: 500">Classic</span> —
                                    общая ставка от <span style="color: #ffc200; font-weight: 500">10</span>
                                    до <span style="color: #ffc200; font-weight: 500">5000</span> монет.
                                    Максимальное количество ставок — <span style="color: #fff; font-weight: 500">5.
                                    </span></p>
                                <p class="htp-description">
                                    <span style="color: #fff; font-weight: 500">Major</span> —
                                    общая ставка от <span style="color: #ffc200; font-weight: 500">100</span>
                                    до <span style="color: #ffc200; font-weight: 500">10000</span> монет.
                                    Максимальное количество ставок — <span style="color: #fff; font-weight: 500">5.
                                    </span></p>
                            </div>
                        </div>
                        <div style="float: left; width: 25%;">
                            <div class="htp-slide" style="padding: 0 5px">
                                <div class="htp-image"><img class="htp-image-img" src="/assets/images/jackpot-htp-2.png"
                                        alt=""></div>
                                <div class="htp-subtitle">Ставка</div>
                                <p class="htp-description">Введите сумму ставки и нажмите кнопку "Играть"</p>
                            </div>
                        </div>
                        <div style="float: left; width: 25%;">
                            <div class="htp-slide" style="padding: 0 5px">
                                <div class="htp-image"><img class="htp-image-img" src="/assets/images/jackpot-htp-3.png"
                                        alt=""></div>
                                <div class="htp-subtitle">Шансы</div>
                                <p class="htp-description">Чем больше ваша ставка - тем больше вы получите билетов и тем
                                    выше шанс, что выигрышный билет окажется именно у вас.</p>
                            </div>
                        </div>
                        <div style="float: left; width: 25%;">
                            <div class="htp-slide" style="padding: 0 5px">
                                <div class="htp-image"><img class="htp-image-img" src="/assets/images/jackpot-htp-4.png"
                                        alt=""></div>
                                <div class="htp-subtitle">Игра</div>
                                <p class="htp-description">После того, как хотя бы два игрока внесли ставки, запускается
                                    30-секундный таймер. На протяжении этих 30 секунд игроки имеют возможность вносить
                                    ставки. После этого прием ставок закрывается и начинается процесс розыгрыша, в
                                    результате которого определяется один победитель, который получает весь банк.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="htp-nav">
                    <span class="htp-nav-item active" data-goto="0"></span><span class="htp-nav-item"
                        data-goto="1"></span><span class="htp-nav-item" data-goto="2"></span><span class="htp-nav-item"
                        data-goto="3"></span></div>
                <button class="next htp-control popup-btn">Далее</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-window modal_jackpot-fair">
    <div class="modal-dialog">
        <div class="modal htp-modal narrow-modal">
            <div class="heading">Честная игра <a class="myicon-close modal-close"></a></div>
            <div class="htp-content">
                <div class="htp-popup-message">Система честной игры гарантирует, что исход игры был
                    сгенерирован до ее начала и не может быть изменен в процессе. Проверить игру,
                    узнать о системе и ее реализации можно на <a href="/fairness" class="colored-link">странице честной
                        игры</a></div>
            </div>
            <div class="fair-slider-wrapper">
                <div class="fair-slider-controls">
                    <button class="fair-slider-control fair-slider-prev fair-slider-prev__small"><span
                            class="myicon-down-arrow user-profile-full-stats__btn__icon"
                            style="transform:rotate(90deg);"></span> Последняя игра</button>
                    <button class="fair-slider-control fair-slider-next fair-slider-next__small" disabled="">Текущая
                        игра <span class="myicon-down-arrow user-profile-full-stats__btn__icon"
                            style="transform:rotate(-90deg);"></span></button>
                </div>
                <div class="fair-slider flickity-enabled" tabindex="0">
                    <div class="flickity-viewport" style="height: 287px; touch-action: pan-y;">
                        <div class="flickity-slider" style="left: 0px; transform: translateX(-100%);">
                            <div class="fair-slide" style="position: absolute; left: 0%;" aria-hidden="true">
                                <form action="/fairness" method="GET" target="_blank">
                                    <div class="fair-prev fair-slider__page">
                                        <div class="modal-fair-form-row">
                                            <div class="modal-fair-label">
                                                Server seed
                                            </div>
                                            <div class="popup-input-block form-group">
                                                <input autocomplete="off" type="text" name="server_seed"
                                                    id="prev_server_seed__small"
                                                    class="popup-input popup-input-rounded popup-input-no-focus prev_seed"
                                                    value="0000000000000000000d26984c0229c9f6962dc74db0a6d525f2f1640396f69c"
                                                    readonly="">
                                            </div>
                                        </div>
                                        <div class="modal-fair-form-row">
                                            <div class="modal-fair-label">
                                                Client seed
                                            </div>
                                            <div class="popup-input-block form-group">
                                                <input autocomplete="off" type="text" name="client_seed"
                                                    id="prev_client_seed__small"
                                                    class="popup-input popup-input-rounded popup-input-no-focus"
                                                    value="000000000000000000024e9be1c7b56cab6428f07920f21ad8457221a91371ae"
                                                    readonly="">
                                            </div>
                                        </div>
                                        <div class="modal-fair-form-row">
                                            <div class="modal-fair-label">
                                                Salt
                                            </div>
                                            <div class="popup-input-block form-group">
                                                <input autocomplete="off" type="text" id="prev_salt__small" name="salt"
                                                    class="popup-input popup-input-rounded popup-input-no-focus"
                                                    value="1d4a34c47c8b0549545b2f043d6f0e1cc86488ec48337496279362e33a47a93d"
                                                    readonly="">
                                            </div>
                                        </div>
                                        <input type="hidden" name="mode" value="jackpot">
                                        <input type="hidden" id="jackpot_tickets__small" name="jackpot_tickets"
                                            value="2380">

                                    </div>
                                    <div class="modal-fair-check">
                                        <button type="submit" class="fair-submit">Проверить игру</button>
                                    </div>
                                </form>
                            </div>
                            <div class="fair-slide is-selected" style="position: absolute; left: 100%;">
                                <div class="fair-cur fair-slider__page">
                                    <div class="modal-fair-form-row">
                                        <div class="modal-fair-label">
                                            Server seed
                                        </div>
                                        <div class="popup-input-block form-group">
                                            <input autocomplete="off" type="text" id="current_server_seed__small"
                                                class="popup-input popup-input-no-focus  popup-input-rounded"
                                                value="0000000000000000000d26984c0229c9f6962dc74db0a6d525f2f1640396f69c"
                                                readonly="">

                                        </div>
                                    </div>
                                    <div class="modal-fair-form-row">
                                        <div class="modal-fair-label">
                                            Client seed
                                        </div>
                                        <div class="popup-input-block form-group">
                                            <input autocomplete="off" type="text" id="current_client_seed__small"
                                                class="popup-input popup-input-no-focus popup-input-rounded"
                                                value="000000000000000000024e9be1c7b56cab6428f07920f21ad8457221a91371ae"
                                                data-pvalue="000000000000000000024e9be1c7b56cab6428f07920f21ad8457221a91371ae"
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="modal-fair-form-row">
                                        <div class="modal-fair-label">
                                            Salt
                                        </div>
                                        <div class="popup-input-block form-group">
                                            <input autocomplete="off" type="text" id="current_salt__small"
                                                class="popup-input popup-input-rounded popup-input-no-focus"
                                                value="hidden" readonly="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

@endsection