$(document).ready(function(){
    $('.user-profile-caregories__category_games').click(function(e){
        e.stopPropagation();
        hideRoomsSubmenu();
        $('.games-dropdown').slideToggle(100);
        $('.user-profile-caregories__dropdown-arrow').toggleClass('active');
    });

    $('.games-table__room-picker').click(function(e){
        e.stopPropagation();
        hideGamesSubmenu();
        $('.rooms-dropdown').slideToggle(100);
        $('.user-profile-caregories__room-arrow').toggleClass('active');
    });

    function hideRoomsSubmenu(){
        $('.rooms-dropdown').slideUp(100);
        $('.user-profile-caregories__room-arrow').removeClass('active');
    }

    function hideGamesSubmenu(){
        $('.games-dropdown').slideUp(100);
        $('.user-profile-caregories__dropdown-arrow').removeClass('active');
    }

    $(document).click(function (e) {
        hideGamesSubmenu();
        hideRoomsSubmenu();
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

    $('.games-dropdown__game').click(function(){
        $('.user-profile-caregories__picked-game-value').text($(this).data('game'));
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

    current_game = 'jackpot-small';
    current_page = 0;
    loadGameData(current_game, current_page);
});

var user_id;
var current_game;
var current_page;

var games_data = {
    'jackpot-small': {
        index: 0,
        has_more: true,
        threshold: 60,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'jackpot-classic': {
        index: 0,
        has_more: true,
        threshold: 60,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'jackpot-major': {
        index: 0,
        has_more: true,
        threshold: 60,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'double': {
        index: 0,
        has_more: true,
        threshold: 105,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'battle': {
        index: 0,
        has_more: true,
        threshold: 40,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'crash': {
        index: 0,
        has_more: true,
        threshold: 20,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'dice': {
        index: 0,
        has_more: true,
        threshold: 20,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
    'raffle': {
        index: 0,
        has_more: true,
        threshold: 20,
        per_page: 10,
        page_to_load: 1,
        raw_data: [],
        pages: [],
    },
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
    },
    'partnership': {
        index: 0,
        has_more: false,
        threshold: 0,
        per_page: 1,
        page_to_load: 0,
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
    if (phrase.toLowerCase() === 'small') return 'Бомж';
    if (phrase.toLowerCase() === 'classic') return 'Классик';
    if (phrase.toLowerCase() === 'major') return 'Мажор';
}

function translatePaymentSystem(phrase){
    if (phrase.toLowerCase() === 'qiwi') return 'Qiwi';
    if (phrase.toLowerCase() === 'yandex') return 'Yandex.Money';
    if (phrase.toLowerCase() === 'visa') return 'Банковская карта';
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
    if (game == 'jackpot-small' || game == 'jackpot-classic' || game == 'jackpot-major'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            game_obj.index++;
            while(game_obj.index < game_obj.raw_data.length && game_obj.raw_data[game_obj.index].id === entry.id){
                entry.bet += game_obj.raw_data[game_obj.index].bet;
                game_obj.index++;
            }
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }
    if (game == 'double'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            entry.black = 0; entry.green = 0; entry.yellow = 0;
            if (entry.color == 'red') entry.yellow += entry.bet;
            if (entry.color == 'black') entry.black += entry.bet;
            if (entry.color == 'green') entry.green += entry.bet;
            game_obj.index++;
            while(game_obj.index < game_obj.raw_data.length && game_obj.raw_data[game_obj.index].id === entry.id){
                if (game_obj.raw_data[game_obj.index].color == 'red') entry.yellow += game_obj.raw_data[game_obj.index].bet;
                if (game_obj.raw_data[game_obj.index].color == 'black') entry.black += game_obj.raw_data[game_obj.index].bet;
                if (game_obj.raw_data[game_obj.index].color == 'green') entry.green += game_obj.raw_data[game_obj.index].bet;
                entry.win_sum += game_obj.raw_data[game_obj.index].win_sum;
                game_obj.index++;
            }
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }

    if (game == 'battle'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            game_obj.index++;
            while(game_obj.index < game_obj.raw_data.length && game_obj.raw_data[game_obj.index].id === entry.id){
                entry.bet += game_obj.raw_data[game_obj.index].bet;
                entry.win_sum += game_obj.raw_data[game_obj.index].win_sum;
                game_obj.index++;
            }
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }
    if (game == 'crash'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            game_obj.index++;
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }
    if (game == 'dice'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            game_obj.index++;
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }
    if (game == 'raffle'){
        for (; game_obj.index < game_obj.raw_data.length;){
            var entry = game_obj.raw_data[game_obj.index];
            game_obj.index++;
            new_page.push(entry);
            if (new_page.length == game_obj.per_page) break;
        }
    }
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
    if (game == 'jackpot-small' || game == 'jackpot-classic' || game == 'jackpot-major'){
        game_name = 'jackpot';
        for(var i = 0; i < data.page.length; i++){
            var won = data.page[i].winner_id == user_id;
            html += '<tr class="games-table__body-tr">\
                <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
                <td class="games-table__body-td">' + translateRoom(data.page[i].room) +  '</td>\
                <td class="games-table__body-td games-table__money"><span class="myicon-coins"></span> ' + data.page[i].bet + '</td>\
                <td class="games-table__body-td">' + Math.round(data.page[i].bet / data.page[i].price * 100) + '%</td>\
                <td class="games-table__body-td games-table__money games-table__money_' + (won ? "win" : "lost") + '">' + (won ? data.page[i].winner_sum : 0) + ' <span class="myicon-coins"></span></td>\
                <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.page[i].hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
            </tr>';
        }
    }

    if (game == 'double'){
        for(var i = 0; i < data.page.length; i++){
            var won = data.page[i].win_sum > 0;
            var winner_color = data.page[i].winner_color; if (data.page[i].winner_color == 'red') winner_color = 'yellow';
            html += '<tr class="games-table__body-tr"> \
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td">\
                ' + (data.page[i].yellow > 0 ? "<span class=\"games-table__body-double-bet bet_yellow\">" + data.page[i].yellow + "</span>": "") + '\
                ' + (data.page[i].black > 0 ? "<span class=\"games-table__body-double-bet bet_black\">" + data.page[i].black + "</span>": "") + '\
                ' + (data.page[i].green > 0 ? "<span class=\"games-table__body-double-bet bet_green\">" + data.page[i].green + "</span>": "") + '\
            </td>\
            <td class="games-table__body-td games-table__body-td_no_padding"><span class="double-history-result double-history-result_small double-history-result_' + winner_color + '"><span class="double-history-result__number">' + data.page[i].winner_num + '</span></span></span></td>\
            <td class="games-table__body-td games-table__money games-table__money_' + (won ? "win" : "lost") + '">' + (won ? data.page[i].win_sum : 0) + ' <span class="myicon-coins"></span></td>\
            <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.page[i].hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
        </tr>';
        }
    }

    if (game == 'battle'){
        for(var i = 0; i < data.page.length; i++){
            var won = data.page[i].win_sum > 0;
            var winner_color = data.page[i].winner_team == 'red' ? 'yellow' : 'violet'; 
            var bet_color = data.page[i].color == 'red' ? 'yellow' : 'violet'; 
            html += '<tr class="games-table__body-tr"> \
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td">\
                <span class="games-table__body-battle-bet bet_' + bet_color + '">' + data.page[i].bet + '</span>\
            </td>\
            <td class="games-table__body-td">' + data.page[i].winner_factor.toFixed(2) + 'x</td>\
            <td class="games-table__body-td games-table__body-td_no_padding"><span class="games-table__body-battle-winner bet_' + winner_color + '"></span></td>\
            <td class="games-table__body-td games-table__money games-table__money_' + (won ? "win" : "lost") + '">' + (won ? data.page[i].win_sum : 0) + ' <span class="myicon-coins"></span></td>\
            <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.page[i].hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
        </tr>';
        }
    }

    if (game == 'crash'){
        for(var i = 0; i < data.page.length; i++){
            var won = data.page[i].win_sum > 0;
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td games-table__money">\
                <span class="myicon-coins"></span> ' + data.page[i].bet  + '\
            </td>\
            <td class="games-table__body-td">' + (won ? data.page[i].withdraw.toFixed(2) : "0.00") + 'x</td>\
            <td class="games-table__body-td games-table__money games-table__money_' + (won ? "win" : "lost") + '">' + (won ? data.page[i].win_sum : 0) +' <span class="myicon-coins"></span></td>\
            <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.page[i].hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
        </tr>';
        }
    }
    
    if (game == 'dice'){
        for(var i = 0; i < data.page.length; i++){
            var won = data.page[i].win_sum > 0;
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td games-table__money">\
                <span class="myicon-coins"></span> ' + data.page[i].bet + '\
            </td>\
            <td class="games-table__body-td">' + (won ? data.page[i].coef.toFixed(2) : "0.00") + 'x</td>\
            <td class="games-table__body-td">' + data.page[i].num.toFixed(2) + '</td>\
            <td class="games-table__body-td games-table__money games-table__money_' + (won ? "win" : "lost") + '">' + (won ? data.page[i].win_sum : 0) + ' <span class="myicon-coins"></span></td>\
            <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.page[i].hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
        </tr>';
        }
    }

    if (game == 'raffle'){
        for(var i = 0; i < data.page.length; i++){
            var won = data.page[i].winner_id == user_id;
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td games-table__tickets games-table__body-td_no_padding">\
                <div class="flex-align"><span class="myicon-ticket-raffle"></span> ' + data.page[i].bet + '</div>\
            </td>\
            <td class="games-table__body-td games-table__money games-table__money_' + (won ? "win" : "lost") + '">' + (won ? data.page[i].winner_sum : 0) + ' <span class="myicon-coins"></span></td>\
            <td class="games-table__body-td games-table__body-td__fair games-table__body-td_no_padding"><a href="/fair/' + data.page[i].hash + '" class="tooltip" title="Проверить" target="_blank"><span class="myicon-security"></span></a></td>\
        </tr>';
        }
    }

    if (game == 'pays'){
        for(var i = 0; i < data.page.length; i++){
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td">' + data.page[i].sum / 10 + ' ₽</td>\
            <td class="games-table__body-td">Free-kassa</td>\
            <td class="games-table__body-td status-success"><span class="games-table__body-status"><span class="myicon-success games-table__body-status-image"></span> <span class="games-table__body-status-text">Выполнен</span></span></td>\
        </tr>';
        }
    }

    if (game == 'withdraws'){
        $('.popup-cancel-comment').remove();
        for(var i = 0; i < data.page.length; i++){
            var status = 'success';
            var status_translate = 'Выполнен';
            if (data.page[i].status == 2){
                status = 'error';
                status_translate = 'Отменен';
            }
            if (data.page[i].status == 0){
                status = 'pending';
                status_translate = 'В процессе';
            }
            html += '<tr class="games-table__body-tr">\
            <td class="games-table__body-td">' + formatDate(data.page[i].date) + '</td>\
            <td class="games-table__body-td">' + data.page[i].sum + ' ₽</td>\
            <td class="games-table__body-td games-table__body-td_no_padding"><img src="/assets/images/' + data.page[i].system + '.png" class="games-table__body-wallet-image tooltip" title="' + translatePaymentSystem(data.page[i].system) + '" alt=""></td>\
            <td class="games-table__body-td"><span class="games-table__body-td__dots">*** </span>' + data.page[i].wallet.slice(data.page[i].wallet.length - 4) + '</td>\
            <td class="games-table__body-td  status-' + status + '"><span class="games-table__body-status"><span class="myicon-' + status + ' games-table__body-status-image"></span> <span class="games-table__body-status-text">' + status_translate + '</span></span></td>';
            if (data.page[i].status == 0){
                html += '<td class="games-table__body-td games-table__body-td_no_padding"><a class="buttoninzc tooltip" title="Отменить" href="/withdraw/cancel/' + data.page[i].id + '"><i class="fas fa-times"></i></a></td>';
            }
            else if(data.page[i].status == 1){
                html += '<td class="games-table__body-td"> <a href="#" data-popup="popup-bonus-comment" class="ajax-popup def_link games-table__popup-caller">Бонус</a></td>';
            }
            else{
                var popup = '<div class="popup htp-popup narrow-popup popup-cancel-comment popup-cancel-comment-' + data.page[i].id + '">\
                                <div class="heading">\
                                    <a class="close ajax-close myicon-close"></a>\
                                    Причина отмены\
                                </div>\
                                <div class="htp-content">\
                                    <div class="htp-popup-message">\
                                        ' + ((data.page[i].comment != null && data.page[i].comment != '') ? data.page[i].comment : "Причина не указана") + '\
                                    </div>\
                                </div>\
                            </div>';
                $('.overlay').append(popup);
                html += '<td class="games-table__body-td"><a href=""  data-popup="popup-cancel-comment-' + data.page[i].id + '" class="ajax-popup def_link games-table__popup-caller">Причина</a></td>';
            }
            html += '</tr>';
        }
    }

    if (game == 'other'){
        for(var i = 0; i < data.page.length; i++){
            var status_text;
            if (data.page[i].status == 2) {
                status_text = 'Реферальный код';
            }
            else if (data.page[i].status == 3) {
                status_text = 'Промокод';
            }
            else if (data.page[i].status == 4) {
                status_text = 'Повышение ранга';
            }
            else {
                status_text = 'Бонус от администрации';
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