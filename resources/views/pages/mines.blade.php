@extends('layout')

@section('content')
<div class="game-container game-container_mines">
<div class="game-area">
                <div class="game-sidebar">
                        <div class="game-sidebar__body">
                                <div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin">
                                        <label class="game-sidebar__input-label">Сумма ставки</label>
                                        <div class="validation-wrapper">
                                                <span class="validation-message validation-message_bottom-mobile"></span>
                                                <div class="mobile-button-combined-wrapper">
                                                        <div class="game-sidebar__input-relative-wrapper">
                                                                <input inputmode="numeric" value="0" type="text" name="mines-bet" id="amount" data-what="Ставка" data-dont-fix-on-fo="true" data-min="1" data-max="100000" data-value-on-nonnumeric="0" data-check-balance="true" class="numeric-input-validate game-sidebar__input show-input-helper" data-helper=".mines-amount-helper" autocomplete="off">
                                                                <span class="myicon-coins game-sidebar__input_coins"></span>
                                                        </div>
                                                                                                                <button class="game-sidebar__play-button_mobile game-sidebar__play-button game-sidebar__play-button_full_width mines-create-game">Играть</button>
                                                                                                                                                                </div>

                                        </div>
                                        <div class="game-sidebar__input-helper input-helper mines-amount-helper">
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-value="1" data-method="plus">+1</button>
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-value="10" data-method="plus">+10</button>
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-value="100" data-method="plus">+100</button>
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-value="1000" data-method="plus">+1k</button>
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-value="2" data-method="divide">1/2</button>
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-value="2" data-method="multiply">x2</button>
                                                <button class="game-sidebar__input-helper-action" data-id="amount" data-method="clear"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                </div>

                                <div class="game-sidebar__input-wrapper">
                                        <label class="game-sidebar__input-label">Количество мин</label>
                                        <div class="game-sidebar__number-input-wrapper">
                                                <div class="validation-wrapper">
                                                        <span class="validation-message"></span>
                                                        <input class="game-sidebar__input numeric-input-validate show-input-helper" data-helper=".mines-helper" data-what="Кол-во бомб" data-min="2" data-max="24" min="2" max="24" name="mines-quantity" id="mines-quantity" value="3" type="number">
                                                </div>
                                        </div>
                                        <div class="game-sidebar__input-helper game-sidebar__input-helper_bottom-mobile input-helper mines-helper">
                                                <button class="game-sidebar__input-helper-action" data-value="3" data-method="set" data-id="mines-quantity">3</button>
                                                <button class="game-sidebar__input-helper-action" data-value="5" data-method="set" data-id="mines-quantity">5</button>
                                                <button class="game-sidebar__input-helper-action" data-value="10" data-method="set" data-id="mines-quantity">10</button>
                                                <button class="game-sidebar__input-helper-action" data-value="20" data-method="set" data-id="mines-quantity">20</button>
                                                <button class="game-sidebar__input-helper-action" data-value="24" data-method="set" data-id="mines-quantity">24</button>
                                        </div>
                                </div>

                                <div class="game-sidebar__play-button-wrapper">
                                                                                <button class="game-sidebar__play-button game-sidebar__play-button_full_width mines-create-game">Играть</button>
                                        
                                                                        </div>


                        </div>
                        <div class="game-sidebar__footer">
                                <button class="game-sidebar__htp-trigger game-sidebar__footer-button tooltip show-modal tooltipstered" data-modal=".modal_mines"><span class="myicon-why"></span></button>
                                <button class="game-sidebar__fair-trigger game-sidebar__footer-button tooltip show-modal tooltipstered" data-modal=".modal_mines-fair"><span class="myicon-security"></span></button>
                        </div>
                </div>

                <div class="game-component">
                        <div class="game-area game-area_mines">
                                <div class="game-area-wrapper mines-field-wrapper">
                                        <div class="outcome-window outcome-window_won outcome-window_centered">
                                                <div class="outcome-window__coeff outcome-window_won__coeff">x1.05</div>
                                                <div class="outcome-window__text outcome-window_won__text">Вы выиграли
                                                        <span class="outcome-window_won-wrapper"><span class="outcome-window_won__sum">1050</span>
                                                                <span class="myicon-coins"></span></span></div>
                                        </div>
                                        <div class="mines-summary-field">
                                                <div class="mines-summary diamonds-count">
                                                        <img class="mines-summary-img" src="https://money-x.top/assets/images/diamond.svg" alt="">
                                                        <div class="diamonds-count-text">
                                                                22
                                                        </div>
                                                </div>

                                                <div class="mines-summary mines-count">
                                                        <img class="mines-summary-img" src="https://money-x.top/assets/images/bomb.svg" alt="">
                                                        <div class="mines-count-text">
                                                                3</div>
                                                </div>
                                        </div>

                                        <div class="mines-field">
                                                <button data-id="0" class="mines-cell-0 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="1" class="mines-cell-1 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="2" class="mines-cell-2 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="3" class="mines-cell-3 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="4" class="mines-cell-4 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>

                                                <button data-id="5" class="mines-cell-5 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="6" class="mines-cell-6 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="7" class="mines-cell-7 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="8" class="mines-cell-8 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="9" class="mines-cell-9 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>

                                                <button data-id="10" class="mines-cell-10 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="11" class="mines-cell-11 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="12" class="mines-cell-12 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="13" class="mines-cell-13 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="14" class="mines-cell-14 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>

                                                <button data-id="15" class="mines-cell-15 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="16" class="mines-cell-16 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="17" class="mines-cell-17 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="18" class="mines-cell-18 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="19" class="mines-cell-19 mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>

                                                <button data-id="20" class="mines-cell-20 mines-cell_last mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="21" class="mines-cell-21 mines-cell_last mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="22" class="mines-cell-22 mines-cell_last mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="23" class="mines-cell-23 mines-cell_last mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>
                                                <button data-id="24" class="mines-cell-24 mines-cell_last mines-cell " disabled=""><span class="mines-cell-content"></span><span class="mines-cell-reveal-animation"></span></button>

                                        </div>
                                </div>
								
                </div>
        </div>
@endsection