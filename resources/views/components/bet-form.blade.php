<div class="game-sidebar__input-wrapper game-sidebar__input-wrapper_no-margin">
    <label class="game-sidebar__input-label">Сумма ставки</label>
    <div class="validation-wrapper">
        <span class="validation-message validation-message_bottom-mobile"></span>
        <div class="mobile-button-combined-wrapper">
            <div class="game-sidebar__input-relative-wrapper">
                <input inputmode="numeric" value="0" type="text" id="amount" data-what="Ставка" data-min="1"
                    data-max="300" data-value-on-nonnumeric="0" data-check-balance="true" data-dont-fix-on-fo="true"
                    data-helper=".input-helper" class="numeric-input-validate game-sidebar__input show-input-helper"
                    autocomplete="off">
                <span class="myicon-coins game-sidebar__input_coins"></span>
            </div>
        </div>
    </div>
    <div class="game-sidebar__input-helper input-helper">
        <button class="game-sidebar__input-helper-action" data-id="amount" data-value="1" data-method="plus">+1</button>
        <button class="game-sidebar__input-helper-action" data-id="amount" data-value="10"
            data-method="plus">+10</button>
        <button class="game-sidebar__input-helper-action" data-id="amount" data-value="100"
            data-method="plus">+100</button>
        <button class="game-sidebar__input-helper-action" data-id="amount" data-value="1000"
            data-method="plus">+1k</button>
        <button class="game-sidebar__input-helper-action" data-id="amount" data-value="2"
            data-method="divide">1/2</button>
        <button class="game-sidebar__input-helper-action" data-id="amount" data-value="2"
            data-method="multiply">x2</button>
        <button class="game-sidebar__input-helper-action" data-id="amount" data-method="clear"><i
                class="fas fa-trash-alt"></i></button>
    </div>
</div>