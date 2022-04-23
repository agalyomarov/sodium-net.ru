$(document).ready(function(){
    $('.games-table__room-picker').click(function(e){
        e.stopPropagation();
        hideGamesSubmenu();
        $('.rooms-dropdown').slideToggle(100);
        $('.user-profile-caregories__room-arrow').toggleClass('active');
    });

    $('.beatify-numbers').each(function(){
        $(this).text(beautifyBalance($(this).text()));
    });

    $('.table-picker').click(function(){
        if ($(this).data('load') != current_game){
            hideWhenLoading();
            current_game = $(this).data('load');
            current_page = 0;
            if (current_game == 'partnership'){
                current_page = -1;
            }
            if (current_game == 'pays' || current_game == 'withdraws' || current_game == 'other'){
                resetState(current_game);
            }
            loadGameData(current_game, current_page);
            $('.user-profile-caregories__category_active').removeClass('user-profile-caregories__category_active');
            if (!$(this).hasClass('games-dropdown__game')){
                $(this).addClass('user-profile-caregories__category_active');
            }
            else{
                $('.user-profile-caregories__category_games').addClass('user-profile-caregories__category_active');
            }
        }
    });


    $('.user-profile-full-stats__btn-prev').click(function(){
        hideWhenLoading();
        current_page--;
        loadGameData(current_game, current_page);
    });
    $('.user-profile-full-stats__btn-next').click(function(){
        hideWhenLoading();
        current_page++;
        loadGameData(current_game, current_page);
    });

    current_game = 'pays';
    current_page = 0;
    loadGameData(current_game, current_page);
});

var cancel_reasons = {};

var user_id;
var current_game;
var current_page;

var games_data = {
    'pays': {
        index: 0,
        has_more: true,
        threshold: 20,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'withdraws': {
        index: 0,
        has_more: true,
        threshold: 20,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'other': {
        index: 0,
        has_more: true,
        threshold: 20,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    }
};

function resetState(game){
    games_data[game].index = 0;
    games_data[game].has_more = true;
    games_data[game].page_to_load = true;
    games_data[game].raw_data.length = 0;
    games_data[game].pages.length = 0;
}

function hideWhenLoading(){
    $('.games-table').hide();
    $('.user-profile-full-stats__controls').removeClass('active');
    $('.user-profile-full-stats').removeClass('show-controls');
}

function addZero(n){
    if (n < 10) return '0' + n;
    return n;
}

function formatDate(date){
    date = date.replace(' ', 'T');
    var d = new Date(date + "+03:00");
    return addZero(d.getDate()) + '.' + addZero(d.getMonth() + 1) + ' ' + addZero(d.getHours()) + ':' + addZero(d.getMinutes());
}

function translateRoom(phrase){
    if (phrase.toLowerCase() === 'small') return 'Small';
    if (phrase.toLowerCase() === 'classic') return 'Classic';
    if (phrase.toLowerCase() === 'major') return 'Major';
}

function translatePaymentSystem(phrase){
    if (phrase.toLowerCase() === 'qiwi') return 'Qiwi';
    if (phrase.toLowerCase() === 'yandex') return 'Yandex.Money';
    if (phrase.toLowerCase() === 'visa') return 'Р‘Р°РЅРєРѕРІСЃРєР°СЏ РєР°СЂС‚Р°';

    if (phrase.toLowerCase() === 'qiwi_rub') return 'Qiwi';
    if (phrase.toLowerCase() === 'yamoney_rub') return 'Yandex.Money';
    if (phrase.toLowerCase() === 'card_rub') return 'Visa/Mastercard (RU)';
    if (phrase.toLowerCase() === 'card_uah') return 'Visa/Mastercard (UA)';

    if (phrase.toLowerCase() === 'alfaclick_rub') return 'РђР»СЊС„Р° РљР»РёРє';
    if (phrase.toLowerCase() === 'terminal_rub') return 'РўРµСЂРјРёРЅР°Р»С‹ (Р Р¤)';
    if (phrase.toLowerCase() === 'perfectmoney_usd') return 'Perfect Money';
    if (phrase.toLowerCase() === 'payeer_rub') return 'Payeer';

    if (phrase.toLowerCase() === 'mts_rub') return 'MTS';
    if (phrase.toLowerCase() === 'beeline_rub') return 'Beeline';
    if (phrase.toLowerCase() === 'megafon_rub') return 'Megafon';
    if (phrase.toLowerCase() === 'tele2_rub') return 'Tele2';
    
    if (phrase.toLowerCase() === 'freekassa') return 'Free-kassa';
    if (phrase.toLowerCase() === 'enot') return 'Enot.io';
}

function showControls(left, right){
    if (left && right){
        $('.user-profile-full-stats__controls').addClass('active');
        $('.user-profile-full-stats').addClass('show-controls');
        showControl('prev');
        showControl('next');
    }
    else if (left && !right){
        $('.user-profile-full-stats__controls').addClass('active');
        $('.user-profile-full-stats').addClass('show-controls');
        showControl('prev');
        hideControl('next');
    }
    else if (right && !left){
        $('.user-profile-full-stats__controls').addClass('active');
        $('.user-profile-full-stats').addClass('show-controls');
        hideControl('prev');
        showControl('next');
    }
    else{
        $('.user-profile-full-stats__controls').removeClass('active');
        $('.user-profile-full-stats').removeClass('show-controls');
    }
}

function showControl(type){
    $('.user-profile-full-stats__btn-' + type).addClass('active');
    $('.user-profile-full-stats__btn-' + type).prop('disabled', false);
}

function hideControl(type){
    $('.user-profile-full-stats__btn-' + type).removeClass('active');
    $('.user-profile-full-stats__btn-' + type).prop('disabled', true);
}

function hasNext(game, page){
    if (page < games_data[game].pages.length - 1) return true;
    if (page >= games_data[game].pages.length) return false;
    return games_data[game].index != games_data[game].raw_data.length;
}


function handleGame(game, page){
    var game_obj = games_data[game];
    var new_page = [];
    if (game == 'pays' || game == 'withdraws' || game == 'other'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            game_obj.index++;
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }
    game_obj.pages.push(new_page);
   
    displayGame(game, {
        page: game_obj.pages[page],
        has_next: hasNext(game, page),
        has_prev: page != 0,
        page_number: page
    });
}



function displayGame(game, data){
    $('.games-table').hide();
    var html = '';
    var game_name = game;

    if (game == 'pays'){
        for(var i = 0; i < data.page.length; i++){
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td">' + data.page[i].sum / 10 + ' в‚Ѕ</td>\
            <td class="games-table__body-td games-table__body-td_no_padding"><img src="/assets/images/' + (data.page[i].system ? data.page[i].system : 'freekassa') + '.png" class="games-table__body-wallet-image tooltip" title="' + translatePaymentSystem((data.page[i].system ? data.page[i].system : 'freekassa')) + '" alt=""></td>\
            <td class="games-table__body-td status-success"><span class="games-table__body-status"><span class="myicon-success games-table__body-status-image"></span> <span class="games-table__body-status-text">Р’С‹РїРѕР»РЅРµРЅ</span></span></td>\
        </tr>';
        }
    }

    if (game == 'withdraws'){
        
        for(var i = 0; i < data.page.length; i++){
            var status = 'success';
            var status_translate = 'Р’С‹РїРѕР»РЅРµРЅ';
            if (data.page[i].status == 2){
                status = 'error';
                status_translate = 'РћС‚РјРµРЅРµРЅ';
            }
            if (data.page[i].status == 0){
                status = 'pending';
                status_translate = 'Р’ РїСЂРѕС†РµСЃСЃРµ';
            }
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td">' + data.page[i].sum + ' в‚Ѕ</td>\
            <td class="games-table__body-td games-table__body-td_no_padding"><img src="/assets/images/' + data.page[i].system + '.png" class="games-table__body-wallet-image tooltip" title="' + translatePaymentSystem(data.page[i].system) + '" alt=""></td>\
            <td class="games-table__body-td"><span class="games-table__body-td__dots">*** </span>' + data.page[i].wallet.slice(data.page[i].wallet.length - 4) + '</td>\
            <td class="games-table__body-td  status-' + status + '"><span class="games-table__body-status"><span class="myicon-' + status + ' games-table__body-status-image"></span> <span class="games-table__body-status-text">' + status_translate + '</span></span></td>';
            if (data.page[i].status == 0){
                html += '<td class="games-table__body-td games-table__body-td_no_padding"><a class="def_link" style="color: #fff;" href="/withdraw/cancel/' + data.page[i].id + '">РћС‚РјРµРЅРёС‚СЊ</a></td>';
            }
            else if(data.page[i].status == 1){
                
                html += '<td class="games-table__body-td"></td>';
                
            }
            else if (data.page[i].status == 2) {
                cancel_reasons[data.page[i].id] = ((data.page[i].comment != null && data.page[i].comment != '') ? data.page[i].comment : "РџСЂРёС‡РёРЅР° РЅРµ СѓРєР°Р·Р°РЅР°");
                html += '<td class="games-table__body-td"><a href="" data-modal=".modal_withdraw-cancel-reason" data-reason-id="' + data.page[i].id + '" class="show-modal show-cancel-reason def_link games-table__popup-caller">РџСЂРёС‡РёРЅР°</a></td>';
            }
            html += '</tr>';
        }
    }

    if (game == 'other'){
        for(var i = 0; i < data.page.length; i++){
            var status_text;
            if (data.page[i].status == 2) {
                status_text = 'Р РµС„РµСЂР°Р»СЊРЅС‹Р№ РєРѕРґ';
            }
            else if (data.page[i].status == 3) {
                status_text = 'РџСЂРѕРјРѕРєРѕРґ';
            }
            else if (data.page[i].status == 4) {
                status_text = 'РџРѕРІС‹С€РµРЅРёРµ СЂР°РЅРіР°';
            }
            else if (data.page[i].status == 6) {
                status_text = 'РџРѕР±РµРґР° РІ РІРёРєС‚РѕСЂРёРЅРµ';
            }
            else {
                status_text = 'Р‘РѕРЅСѓСЃ РѕС‚ Р°РґРјРёРЅРёСЃС‚СЂР°С†РёРё';
            }

            html += '<tr class="games-table__body-tr"> \
                        <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
                        <td class="games-table__body-td games-table__money">\
                            ' + data.page[i].sum + ' <span class="myicon-coins"></span>\
                        </td>\
                        <td class="games-table__body-td">' + status_text + '</td>\
                        <td class="games-table__body-td">' + data.page[i].code + '</td>\
                    </tr>';
        }
    }

    $('.games-table__' + game_name + ' tbody').html(html);
    $('.loader').hide();
    $('.games-table__' + game_name).show();
    showControls(data.has_prev, data.has_next);

    // to ensure no parallel code is injected while loading
    current_game = game;
    current_page = data.page_number;
    $('.user-profile-caregories__category_active').removeClass('user-profile-caregories__category_active');
    if (current_game == 'pays' || current_game == 'withdraws' || current_game == 'other' || current_game == 'partnership'){
        $('.user-profile-caregories__category_' + current_game).addClass('user-profile-caregories__category_active');
    }
    else{
        $('.user-profile-caregories__category_games').addClass('user-profile-caregories__category_active');
    }

    $('.tooltip').tooltipster({
        side: 'bottom',
        theme: 'tooltipster-borderless'
    });

    $('.buttoninzc').click(function(){
        $(this).addClass('disabled-link');
    }); 

    $(document).on('click', '.show-cancel-reason', function(){
        $('.modal_withdraw-cancel-reason .htp-popup-message').html(cancel_reasons[$(this).data('reason-id')]);
    });
    
}

function displayError(){
    $('.user-profile-full-stats__error').show();
    $('.loader').hide();
}

function hideError(){
    $('.user-profile-full-stats__error').hide();
}

function loadGameData(game, page){
    $('.loader').show();
    hideError();
    var game_obj = games_data[game];
    if (page < game_obj.pages.length) {
        displayGame(game, {
            page: game_obj.pages[page],
            has_next: hasNext(game, page),
            has_prev: page > 0,
            page_number: page
        });
        return;
    } 
    if (game_obj.raw_data.length - game_obj.index < game_obj.threshold && game_obj.has_more){
        $.ajax({
            url: "/profile/games/" + game + '?page=' + game_obj.page_to_load,
            type: 'get',
            data: {
            },
            success: function(data) {
               
                Array.prototype.push.apply(games_data[game].raw_data, data.data.data);
                user_id = data.user_id;
                game_obj.has_more = data.data.next_page_url != null;
                game_obj.page_to_load++;
                handleGame(game, page);
            },
            error: function(data){
                displayError();
            }
        });
    }
    else{
        handleGame(game, page);
    }
}