<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PagesController@homePage');
Route::get('/jackpot', ['as' => 'jackpot', 'uses' => 'JackpotController@index']);
Route::get('/pvp', ['as' => 'pvp', 'uses' => 'CoinController@index']);
Route::get('/double', ['as' => 'double', 'uses' => 'DoubleController@index']);
Route::get('/dice', ['as' => 'dice', 'uses' => 'DiceController@index']);
Route::get('/battle', ['as' => 'battle', 'uses' => 'BattleController@index']);
Route::get('/crash', ['as' => 'crash', 'uses' => 'CrashController@index']);
Route::get('/jackpot/history', ['as' => 'jackpotHistory', 'uses' => 'JackpotController@history']);
Route::get('/jackpot/history/game/{room}/{id}', ['as' => 'jackpotHistory', 'uses' => 'JackpotController@gameHistory']);
Route::get('/double/history', ['as' => 'doubleHistory', 'uses' => 'DoubleController@history']);
Route::get('/reviews', ['as' => 'reviews', 'uses' => 'PagesController@reviews']);
Route::get('/top/daily', ['as' => 'top', 'uses' => 'PagesController@top']);
Route::get('/top/partners', ['as' => 'topPartners', 'uses' => 'PagesController@topPartners']);
Route::get('/faq', ['as' => 'faq', 'uses' => 'PagesController@faq']);
Route::get('/rules', ['as' => 'rules', 'uses' => 'PagesController@rules']);
Route::get('/mines', ['as' => 'mines', 'uses' => 'PagesController@mines']);
Route::get('/raffle', ['as' => 'raffle', 'uses' => 'PagesController@raffle']);
Route::get('/rank', ['as' => 'rank', 'uses' => 'PagesController@rank']);
Route::get('/result', 'PagesController@result');
Route::get('/topAjax', 'PagesController@topAjax');
Route::get('/topPartnersAjax', 'PagesController@topPartnersAjax');
Route::get('/success', 'PagesController@success');
Route::get('/fail', 'PagesController@fail');
Route::get('/profile', ['as' => 'profile', 'uses' => 'PagesController@profile']);
Route::post('/sound', ['as' => 'sound.switch', 'uses' => 'SettingsController@switchSound']);
Route::post('/theme', ['as' => 'theme.switch', 'uses' => 'SettingsController@switchTheme']);

Route::group(['prefix' => '/auth'], function () {
    Route::get('/{provider}', ['as' => 'login', 'uses' => 'AuthController@login']);
    Route::get('/callback/{provider}', 'AuthController@callback');
});

Route::group(['middleware' => 'auth'], function () {
	Route::group(['prefix' => 'roulette'], function() {
		Route::post('/addBet', 'DoubleController@addBet');
		Route::post('/getBet', 'DoubleController@getBet');
		Route::get('/history', 'DoubleController@history');
	});
	Route::group(['prefix' => 'crash'], function() {
		Route::post('/addBet', 'CrashController@createBet');
		Route::post('/last', 'CrashController@lastBet');
		Route::post('/cashout', 'CrashController@Cashout');
	});
	Route::get('/wallet/deposit', ['as' => 'deposit', 'uses' => 'PagesController@deposit']);
	Route::get('/wallet/withdraw', ['as' => 'withdraw', 'uses' => 'PagesController@withdrawPage']);
	Route::get('/wallet/history', ['as' => 'history', 'uses' => 'PagesController@history']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
    Route::get('/ref', ['as' => 'ref', 'uses' => 'PagesController@ref']);
    Route::get('/bonus', ['as' => 'bonus', 'uses' => 'PagesController@bonus']);
	Route::get('/pay', ['as' => 'pay', 'uses' => 'PagesController@pay']);
    Route::get('/pay/history', ['as' => 'payhistory', 'uses' => 'PagesController@payHistory']);
    Route::get('/pay/send', ['as' => 'paySend', 'uses' => 'PagesController@paySend']);
    Route::post('/payment/send/create', 'PagesController@sendCreate');
    Route::get('/withdraw/cancel/{id}', 'PagesController@withdraw_cancel');
	Route::get('/fair', ['as' => 'fair', 'uses' => 'PagesController@fair']);
    Route::get('/fair/{hash}', ['as' => 'fairGame', 'uses' => 'PagesController@fairGame']);
	Route::post('/withdraw', 'PagesController@withdraw');
	Route::post('/ref/activate', 'PagesController@refActivate');
	Route::post('/ref/getMoney', 'PagesController@getMoney');
	Route::post('/bonus/getBonus', 'PagesController@getBonus');
	Route::post('/battle/addBet', 'BattleController@newBet');
	Route::post('/newBet', 'JackpotController@newBet');
	Route::post('/dice/play', 'DiceController@play');
	Route::post('/chat', 'ChatController@add_message');
	Route::post('/flip/newGame', 'CoinController@createRoom');
	Route::post('/flip/joinRoom', 'CoinController@joinRoom');
	Route::post('/fair/check', 'PagesController@fairCheck');
	Route::post('/unbanMe', 'PagesController@unbanMe');
	Route::post('/createPromo', 'PagesController@createPromo');
});

Route::group(['middleware' => 'auth', 'middleware' => 'access:admin'], function () {
	Route::get('/admin', ['as' => 'admin', 'uses' => 'AdminController@index']);
	Route::get('/admin/botOn', ['as' => 'botOn', 'uses' => 'AdminController@botOn']);
	Route::get('/admin/botOff', ['as' => 'botOff', 'uses' => 'AdminController@botOff']);
	Route::get('/admin/users', ['as' => 'adminUsers', 'uses' => 'AdminController@users']);
	Route::get('/admin/bots', ['as' => 'adminBots', 'uses' => 'AdminController@bots']);
	Route::get('/admin/user/{id}', ['as' => 'adminUser', 'uses' => 'AdminController@user']);
	Route::get('/admin/settings', ['as' => 'adminSettings', 'uses' => 'AdminController@settings']);
	Route::get('/admin/withdraw', ['as' => 'adminWithdraw', 'uses' => 'AdminController@withdraw']);
	Route::get('/admin/bonuses', ['as' => 'adminBonus', 'uses' => 'AdminController@bonus']);
	Route::get('/admin/promo', ['as' => 'adminPromo', 'uses' => 'AdminController@promo']);
	Route::get('/admin/user/delete/{id}', 'AdminController@userDelete');
    Route::post('/admin/userSave', 'AdminController@userSave');
	Route::post('/admin/usersAjax', 'AdminController@usersAjax');
    Route::post('/admin/userSave', 'AdminController@userSave');
    Route::post('/admin/promoNew', 'AdminController@promoNew');
    Route::post('/admin/promoSave', 'AdminController@promoSave');
    Route::get('/admin/promoDelete/{id}', 'AdminController@promoDelete');
	Route::post('/admin/bonusNew', 'AdminController@bonusNew');
    Route::post('/admin/bonusSave', 'AdminController@bonusSave');
    Route::get('/admin/bonusDelete/{id}', 'AdminController@bonusDelete');
    Route::post('/admin/settingSave', 'AdminController@settingsSave');
    Route::get('/admin/withdraw/{id}', 'AdminController@withdrawSend');
    Route::get('/admin/return/{id}', 'AdminController@withdrawReturn');
    Route::post('/admin/getBalance', 'AdminController@getBalans_frw');
    Route::post('/admin/gotDouble', 'RouletteController@gotDouble');
    Route::post('/admin/gotRoulette', 'JackpotController@gotRoulette');
	Route::post('/admin/gotBattle', 'BattleController@gotThis');
	Route::post('/admin/gotCrash', 'CrashController@gotThis');
	Route::post('/admin/getVKUser', 'AdminController@getVKUser');
	Route::post('/admin/fakeSave', 'AdminController@fakeSave');
	Route::post('/admin/chatSend', 'AdminController@add_message');
	Route::post('/admin/chatdel', 'ChatController@delete_message');
	Route::post('/admin/gotDouble', 'DoubleController@gotThis');
	Route::post('/admin/gotJackpot', 'JackpotController@gotThis');
	Route::post('/admin/betJackpot', 'JackpotController@adminBet');
	Route::post('/admin/betDouble', 'DoubleController@adminBet');
	Route::post('/admin/betDice', 'DiceController@adminBet');
});

Route::group(['middleware' => 'auth', 'middleware' => 'access:moder'], function () {
	Route::post('/moder/chatdel', 'ChatController@delete_message');
});

Route::group(['prefix' => 'api'], function () {
	Route::post('/chat/gift', 'ChatController@gift');
    Route::group(['prefix' => 'jackpot'], function() {
    	Route::post('/newGame', 'JackpotController@newGame');
		Route::post('/getSlider', 'JackpotController@getSlider');
    	Route::post('/getStatus', 'JackpotController@getStatus');
    	Route::post('/setStatus', 'JackpotController@setStatus');
    	Route::post('/getRooms', 'JackpotController@getRooms');
		Route::post('/addBetFake', 'JackpotController@newBetFake');
    });
	Route::group(['prefix' => 'roulette'], function() {
        Route::post('/getGame', 'DoubleController@getGame');
        Route::post('/updateStatus', 'DoubleController@updateStatus');
        Route::post('/getSlider', 'DoubleController@getSlider');
        Route::post('/newGame', 'DoubleController@newGame');
		Route::post('/addBetFake', 'DoubleController@addBetFake');
    });
	Route::group(['prefix' => 'dice'], function() {
		Route::post('/addBetFake', 'DiceController@addBetFake');
    });
	Route::group(['prefix' => 'battle'], function() {
		Route::post('/newGame', 'BattleController@newGame');
		Route::post('/getSlider', 'BattleController@getSlider');
		Route::post('/getStatus', 'BattleController@getStatus');
		Route::post('/setStatus', 'BattleController@setStatus');
    });
	Route::group(['prefix' => 'crash'], function() {
        Route::post('/init', 'CrashController@init');
		Route::post('/slider', 'CrashController@startSlider');
		Route::post('/newGame', 'CrashController@newGame');
		Route::post('/addBetFake', 'CrashController@addBetFake');
    });
	Route::post('/unBan', 'ChatController@unban');
	Route::post('/getMerchBalance', 'AdminController@getMerchBalance');
    Route::post('/getParam', 'AdminController@getParam');
});