<?php namespace App\Http\Controllers;

use App\User;
use App\Profit;
use App\Crash;
use App\CrashBets;
use App\Dice;
use App\Rooms;
use App\Bonus;
use App\BonusLog;
use App\Sends;
use App\Jackpot;
use App\JackpotBets;
use App\BattleBets;
use App\CoinFlip;
use App\Withdraw;
use App\Promocode;
use App\SuccessPay;
use App\Roulette;
use App\RouletteBets;
use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AdminController extends Controller
{
    const CHAT_CHANNEL = 'chat.message';
    const NEW_MSG_CHANNEL = 'new.msg';
    const CLEAR = 'chat.clear';
    const DELETE_MSG_CHANNEL = 'del.msg';

    public function __construct()
    {
        parent::__construct();
        $jackpot_small = Jackpot::where('room', 'small')->orderBy('id', 'desc')->first();
        $jackpot_classic = Jackpot::where('room', 'classic')->orderBy('id', 'desc')->first();
        $jackpot_major = Jackpot::where('room', 'major')->orderBy('id', 'desc')->first();
        view()->share('chances_small', JackpotController::getChancesOfGame('small', $jackpot_small->game_id));
        view()->share('chances_classic', JackpotController::getChancesOfGame('classic', $jackpot_classic->game_id));
        view()->share('chances_major', JackpotController::getChancesOfGame('major', $jackpot_major->game_id));
    }

    public function getParam()
    {
        return [
            'fake' => $this->settings->fake
        ];
    }

    /* Проверка баланса FKW */
    public function getBalans_frw() {
        $data = array(
            'wallet_id' => $this->settings->fk_wallet,
            'sign' => md5($this->settings->fk_wallet.$this->settings->fk_api),
            'action' => 'get_balance',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($result, true);

        if(!$json['status']) return;
        if(!isset($json['data']['RUR'])) return 0;

        return $json['data']['RUR'];
    }
    /*Проверка баланса FKW*/

    public function bots()
    {
        $bots = User::where('fake', 1)->get();
        return view('admin.bots', compact('bots'));
    }

    public function getVKUser(Request $r)
    {
        $vk_url = $r->get('url');
        $old_url = ($vk_url);
        $url = explode('/', trim($old_url,'/'));
        $url_parse = array_pop($url);
        $url_last = preg_replace('/&?id+/i', '', $url_parse);
        $runfile = 'https://api.vk.com/method/users.get?v=5.80&user_ids='.$url_last.'&fields=photo_max&lang=0&access_token='.$this->settings->vk_secret;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $runfile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $user = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($user);
        $user = $user->response;
        return $user;
    }

    public function getVKUserSave($url)
    {
        $vk_url = $url;
        $old_url = ($vk_url);
        $url = explode('/', trim($old_url,'/'));
        $url_parse = array_pop($url);
        $url_last = preg_replace('/&?id+/i', '', $url_parse);
        $runfile = 'https://api.vk.com/method/users.get?v=5.80&user_ids='.$url_last.'&fields=photo_max&lang=0&access_token='.$this->settings->vk_secret;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $runfile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $user = curl_exec($ch);
        curl_close($ch);
        $user = json_decode($user);
        $user = $user->response;
        $usr = [];
        foreach($user as $us) {
            $usr = [
                'id' => $us->id,
                'first_name' => $us->first_name,
                'last_name' => $us->last_name,
                'photo_max' => $us->photo_max
            ];
        }
        return $usr;
    }

    public function fakeSave(Request $r)
    {
        $user = $this->getVKUserSave($r->get('url'));

        User::insert([
            'username' => $user['first_name'].' '.$user['last_name'],
            'avatar' => $user['photo_max'],
            'user_id' => $user['id'],
            'fake' => 1
        ]);

        return redirect()->route('adminBots')->with('success', 'Пользователь сохранен!');
    }

    public function userDelete($id)
    {
        $case = User::where('id', $id)->first();
        User::where('id', $id)->delete();

        return redirect()->route('adminBots')->with('success', 'Пользователь удален!');
    }

    public function index()
    {
        $pay_today = SuccessPay::where('created_at', '>=', Carbon::today())->where('status', 1)->sum('price')/10;
        $pay_week = SuccessPay::where('created_at', '>=', Carbon::now()->subDays(7))->where('status', 1)->sum('price')/10;
        $pay_month = SuccessPay::where('created_at', '>=', Carbon::now()->subDays(30))->where('status', 1)->sum('price')/10;
        $pay_all = SuccessPay::where('status', 1)->sum('price')/10;
        $with_req = Withdraw::where('status', 0)->orderBy('id', 'desc')->sum('value');

        $payments = SuccessPay::where('status', 1)->orderBy('id', 'desc')->limit(10)->get();
        $users = User::orderBy('id', 'desc')->limit(10)->get();
        $userTop = User::where(['is_admin' => 0, 'is_youtuber' => 0, 'fake' => 0])->orderBy('balance', 'desc')->limit(20)->get();
        $fake = User::where('fake', 1)->orderBy('id', 'desc')->get();

        $dllast = Roulette::orderBy('id', 'desc')->first();
        $dlbetsRed = RouletteBets::where(['is_fake' => 0, 'round_id' => $dllast->id, 'type' => 'red'])->sum('price');
        $dlbetsGreen = RouletteBets::where(['is_fake' => 0, 'round_id' => $dllast->id, 'type' => 'green'])->sum('price');
        $dlbetsBlack = RouletteBets::where(['is_fake' => 0, 'round_id' => $dllast->id, 'type' => 'black'])->sum('price');

        $dlbet = [
            'red' => $dlbetsRed,
            'green' => $dlbetsGreen,
            'black' => $dlbetsBlack
        ];

        $profit = Profit::where('created_at', '>=', Carbon::today())->sum('sum')/10;

        $profit_dice = Profit::where('game', 'dice')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_battle = Profit::where('game', 'battle')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_jackpot = Profit::where('game', 'jackpot')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_pvp = Profit::where('game', 'pvp')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_double = Profit::where('game', 'double')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_crash = Profit::where('game', 'crash')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_ref = Profit::where('game', 'ref')->where('created_at', '>=', Carbon::today())->sum('sum')/10;
        $profit_send = Profit::where('game', 'send')->where('created_at', '>=', Carbon::today())->sum('sum')/10;

        $last_dep = [];
        foreach($payments as $d) {
            $user = User::where('user_id', $d->user)->first();
            $last_dep[] = [
                'id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'sum' => $d->price/10
            ];
        }

        return view('admin.index', compact('with_req', 'pay_today', 'pay_week', 'pay_month', 'pay_all', 'with_req', 'last_dep', 'users', 'fake', 'dlbet', 'userTop', 'profit_double', 'profit_dice', 'profit_battle', 'profit_jackpot', 'profit_pvp', 'profit_crash', 'profit'));
    }

    public function add_message(Request $r)
    {
        $val = \Validator::make($r->all(), [
            'message' => 'required|string|max:255'
        ],[
            'required' => 'Сообщение не может быть пустым!',
            'string' => 'Сообщение должно быть строкой!',
            'max' => 'Максимальный размер сообщения 255 символов.',
        ]);
        $error = $val->errors();

        if($val->fails()){
            return response()->json(['message' => $error->first('message'), 'status' => 'error']);
        }

        $user = User::where('user_id', $r->get('user_id'))->first();

        $messages = $r->get('message');
        if(\Cache::has('addmsg.user.' . $user->id)) return response()->json(['message' => 'Вы слишком часто отправляете сообщения!', 'status' => 'error']);
        \Cache::put('addmsg.user.' . $user->id, '', 0.05);
        $nowtime = time();
        $banchat = $user->banchat;
        $lasttime = $nowtime - $banchat;

        if($banchat >= $nowtime) {
            return response()->json(['message' => 'Вы заблокированы до: '.date("d.m.Y H:i:s", $banchat), 'status' => 'error']);
        } else {
            User::where('user_id', $user->user_id)->update(['banchat' => null]);
        }

        $time = date('H:i', time());
        $moder = $user->is_moder;
        $youtuber = $user->is_youtuber;
        $admin = 0;
        $ban = $user->banchat;
        $user_id = $user->user_id;
        $username = htmlspecialchars($user->username);
        $avatar = $user->avatar;

        function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        $words = file_get_contents(dirname(__FILE__) . '/words.json');
        $words = object_to_array(json_decode($words));

        foreach ($words as $key => $value) {
            $messages = str_ireplace($key, $value, $messages);
        }

        if(preg_match("/href|url|http|https|www|.ru|.com|.net|.info|csgo|winner|ru|xyz|com|net|info|.org/i", $messages)) {
            return response()->json(['message' => 'Ссылки запрещены!', 'status' => 'error']);
        }
        $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => htmlspecialchars($messages), 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $user->is_moder, 'youtuber' => $user->is_youtuber];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
        return response()->json(['message' => 'Ваше сообщение успешно отправлено!', 'status' => 'success']);
    }

    public function users()
    {
        return view('admin.users');
    }


    public function usersAjax()
    {
        return datatables(User::query())->toJson();
    }

    public function user($id)
    {
        $user = User::where('id', $id)->first();
        $promo = SuccessPay::where('user', $user->user_id)->where('status', 3)->sum('price')/10;
        $bon = BonusLog::where('user_id', $user->id)->sum('sum')/10;
        $dep = SuccessPay::where('user', $user->user_id)->where('status', 1)->sum('price')/10;
        $with = Withdraw::where('user_id', $user->id)->where('status', 1)->sum('value');
        $ref = $user->ref_money_history/10;
        $sends_arr = Sends::where('sender', $user->id)->get();
        $sends_arr_from = Sends::where('receiver', $user->id)->get();
        $acpromo = SuccessPay::where('user', $user->user_id)->where('status', 3)->get();

        /* Jackpot */
        $j_s = JackpotBets::where('winner_id', '==', $user->id)->where('user_id', $user->id)->sum('sum')/10;
        $j_wb = Jackpot::where('status', 3)->where('winner_id', $user->id)->sum('winner_sum')/10 - $j_s;
        $j_lb = JackpotBets::where('winner_id', '!=', $user->id)->where('user_id', $user->id)->sum('sum')/10;
        /* Double */
        $do_s = RouletteBets::where('user_id', $user->id)->where('win', 1)->sum('price')/10;
        $do_wb = RouletteBets::where('user_id', $user->id)->where('win', 1)->sum('win_sum')/10 - $do_s;
        $do_lb = RouletteBets::where('user_id', $user->id)->where('win', 0)->sum('price')/10;
        /* Dice */
        $di_wb = Dice::where('user_id', $user->id)->where('win', 1)->sum('win_sum')/10;
        $di_lb = Dice::where('user_id', $user->id)->where('win', 0)->sum('sum')/10;
        /* PvP */
        $p_s = CoinFlip::where('status', 1)->where('winner_id', $user->id)->sum('price')/20;
        $p_wu1 = CoinFlip::where('status', 1)->where('winner_id', '!=', $user->id)->where('user1', $user->id)->sum('price')/10;
        $p_wu2 = CoinFlip::where('status', 1)->where('winner_id', '!=', $user->id)->where('user2', $user->id)->sum('price')/10;
        $p_wb = CoinFlip::where('status', 1)->where('winner_id', $user->id)->sum('win_sum')/10 - $p_s;
        $p_lb = $p_wu1+$p_wu2;
        /* Battle */
        $b_m = BattleBets::where('user_id', $user->id)->where('win', 1)->sum('price')/10;
        $b_wb = BattleBets::where('user_id', $user->id)->where('win', 1)->sum('win_sum')/10 - $b_m;
        $b_lb = BattleBets::where('user_id', $user->id)->where('win', 0)->sum('price')/10;
        /* Crash */
        $cr_m = CrashBets::where('user_id', $user->id)->where('status', 1)->sum('price')/10;
        $cr_wb = CrashBets::where('user_id', $user->id)->where('status', 1)->sum('won')/10 - $cr_m;
        $cr_lb = CrashBets::where('user_id', $user->id)->where('status', 0)->sum('price')/10;

        $refs = User::where('referred_by', $user->affiliate_id)->get();

        $sends = [];
        $sends_from = [];
        foreach($sends_arr as $s) {
            $u = User::where('id', $s->receiver)->first();
            $sends[] = [
                'id' => $u->id,
                'username' => $u->username,
                'sum' => $s->sum,
                'date' => Carbon::parse($s->updated_at)->diffForHumans()
            ];
        }
        foreach($sends_arr_from as $s) {
            $u = User::where('id', $s->sender)->first();
            $sends_from[] = [
                'id' => $u->id,
                'username' => $u->username,
                'sum' => $s->sum,
                'date' => Carbon::parse($s->updated_at)->diffForHumans()
            ];
        }
        $bonus = $promo + $bon;

        return view('admin.user', compact('user', 'dep', 'with', 'bonus', 'ref', 'sends', 'sends_from', 'acpromo', 'j_wb', 'j_lb', 'do_wb', 'do_lb', 'di_wb', 'di_lb', 'p_wb', 'p_lb', 'b_wb', 'b_lb', 'cr_wb', 'cr_lb', 'refs'));
    }

    public function userSave(Request $r)
    {
        $admin = 0;
        $moder = 0;
        $youtuber = 0;
        if($r->get('id') == null) return redirect()->route('adminUsers')->with('error', 'Не удалось найти пользователя с таким ID!');
        if($r->get('balance') == null) return redirect()->route('adminUsers')->with('error', 'Поле "Баланс" не может быть пустым!');
        if($r->get('priv') == 'admin') {
            $admin = 1;
        }
        if($r->get('priv') == 'moder') {
            $moder = 1;
        }
        if($r->get('priv') == 'youtuber') {
            $youtuber = 1;
        }

        User::where('id', $r->get('id'))->update([
            'balance' => $r->get('balance'),
            'is_admin' => $admin,
            'is_moder' => $moder,
            'is_youtuber' => $youtuber,
            'affiliate_id' => $r->get('affiliate_id'),
            'ban' => $r->get('ban')
        ]);

        return redirect()->route('adminUsers')->with('success', 'Пользователь сохранен!');
    }

    public function settings()
    {
        $rooms = Rooms::get();
        return view('admin.settings', compact('rooms'));
    }

    public function settingsSave(Request $r)
    {
        Settings::where('id', 1)->update([
            'domain' => $r->get('domain'),
            'sitename' => $r->get('sitename'),
            'title' => $r->get('title'),
            'desc' => $r->get('desc'),
            'keys' => $r->get('keys'),
            'vk_key' => $r->get('vk_key'),
            'vk_secret' => $r->get('vk_secret'),
            'vk_url' => $r->get('vk_url'),
            'tg_url' => $r->get('tg_url'),
            'fake' => $r->get('fake'),
            'mrh_ID' => $r->get('mrh_ID'),
            'mrh_secret1' => $r->get('mrh_secret1'),
            'mrh_secret2' => $r->get('mrh_secret2'),
            'fk_api' => $r->get('fk_api'),
            'fk_wallet' => $r->get('fk_wallet'),
            'chat_dep' => $r->get('chat_dep'),
            'sendwith' => $r->get('sendwith'),
            'min_sendwith' => $r->get('min_sendwith'),
            'max_sendwith' => $r->get('max_sendwith'),
            'ref_invite' => $r->get('ref_invite'),
            'withpromo' => $r->get('withpromo'),
            'jackpot_commission' => $r->get('jackpot_commission'),
            'pvp_commission' => $r->get('pvp_commission'),
            'pvp_min' => $r->get('pvp_min'),
            'pvp_max' => $r->get('pvp_max'),
            'random_api' => $r->get('random_api'),
            'roulette_timer' => $r->get('roulette_timer'),
            'double_min_bet' => $r->get('double_min_bet'),
            'double_max_bet' => $r->get('double_max_bet'),
            'double_fake_min' => $r->get('double_fake_min'),
            'double_fake_max' => $r->get('double_fake_max'),
            'double_commission' => $r->get('double_commission'),
            'dice_fake_min' => $r->get('dice_fake_min'),
            'dice_fake_max' => $r->get('dice_fake_max'),
            'battle_timer' => $r->get('battle_timer'),
            'battle_min_bet' => $r->get('battle_min_bet'),
            'battle_max_bet' => $r->get('battle_max_bet'),
            'battle_commission' => $r->get('battle_commission'),
            'crash_timer' => $r->get('crash_timer'),
            'crash_min_bet' => $r->get('crash_min_bet'),
            'crash_max_bet' => $r->get('crash_max_bet'),
            'dep_perc' => $r->get('dep_perc'),
            'dep_code' => $r->get('dep_code')
        ]);
        $rooms = Rooms::get();

        foreach($rooms as $room) {
            Rooms::where('name', $room->name)->update([
                'time' => $r->get('time_'.$room->name),
                'min' => $r->get('min_'.$room->name),
                'max' => $r->get('max_'.$room->name),
                'bets' => $r->get('bets_'.$room->name)
            ]);
        }

        return redirect()->route('adminSettings')->with('success', 'Настройки сохранен!');
    }

    public function promo() {
        $codes = Promocode::where('count_use', '!=', 0)->get();

        return view('admin.promo', compact('codes'));
    }

    public function promoNew(Request $r) {
        $code = $r->get('code');
        $limit = $r->get('limit');
        $amount = $r->get('amount');
        $count_use = $r->get('count_use');
        $have = Promocode::where('code', $code)->first();
        if(!$code) return redirect()->route('adminPromo')->with('error', 'Вы заполнили не все поля!');
        if(!$amount) return redirect()->route('adminPromo')->with('error', 'Вы заполнили не все поля!');
        if(!$count_use) return redirect()->route('adminPromo')->with('error', 'Вы заполнили не все поля!');
        if($have) return redirect()->route('adminPromo')->with('error', 'Такой код уже существует');

        Promocode::create([
            'code' => $code,
            'limit' => $limit,
            'amount' => $amount,
            'count_use' => $count_use
        ]);

        return redirect()->route('adminPromo')->with('success', 'Промокод создан!');
    }

    public function promoSave(Request $r) {
        $id = $r->get('id');
        $code = $r->get('code');
        $limit = $r->get('limit');
        $amount = $r->get('amount');
        $count_use = $r->get('count_use');
        $have = Promocode::where('code', $code)->where('id', '!=', $id)->first();
        if(!$id) return redirect()->route('adminPromo')->with('error', 'Не удалось найти данный ID!');
        if(!$code) return redirect()->route('adminPromo')->with('error', 'Вы заполнили не все поля!');
        if(!$amount) return redirect()->route('adminPromo')->with('error', 'Вы заполнили не все поля!');
        if(!$count_use) $count_use = 0;
        if($have) return redirect()->route('adminPromo')->with('error', 'Такой код уже существует');

        Promocode::where('id', $id)->update([
            'code' => $code,
            'limit' => $limit,
            'amount' => $amount,
            'count_use' => $count_use
        ]);

        return redirect()->route('adminPromo')->with('success', 'Промокод обновлен!');
    }

    public function promoDelete($id) {
        if(!$id) return redirect()->route('adminPromo')->with('error', 'Нет такого промокода!');
        Promocode::where('id', $id)->delete();

        return redirect()->route('adminPromo')->with('success', 'Промокод удален!');
    }

    public function bonus() {
        $bonuses = Bonus::get();

        return view('admin.bonus', compact('bonuses'));
    }

    public function bonusNew(Request $r) {
        $sum = $r->get('sum');
        $status = $r->get('status');
        $color = $r->get('color');

        if(!$sum) return redirect()->route('adminBonus')->with('error', 'Вы заполнили не все поля!');

        Bonus::create([
            'sum' => $sum,
            'status' => $status,
            'color' => $color
        ]);

        return redirect()->route('adminBonus')->with('success', 'Бонус создан!');
    }

    public function bonusSave(Request $r) {
        $id = $r->get('id');
        $sum = $r->get('sum');
        $status = $r->get('status');
        $color = $r->get('color');

        if(!$sum) return redirect()->route('adminBonus')->with('error', 'Вы заполнили не все поля!');

        Bonus::where('id', $id)->update([
            'sum' => $sum,
            'status' => $status,
            'color' => $color
        ]);

        return redirect()->route('adminBonus')->with('success', 'Бонус обновлен!');
    }

    public function bonusDelete($id) {
        if(!$id) return redirect()->route('adminBonus')->with('error', 'Нет такого бонуса!');
        Bonus::where('id', $id)->delete();

        return redirect()->route('adminBonus')->with('success', 'Бонус удален!');
    }

    public function withdraw() {
        $list = Withdraw::where('status', 0)->get();
        $withdraws = [];
        foreach($list as $itm) {
            $user = User::where('id', $itm->user_id)->first();
            $withdraws[] = [
                'id' => $itm->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'system' => $itm->system,
                'wallet' => $itm->wallet,
                'value' => $itm->value,
                'status' => $itm->status
            ];
        }

        $list2 = Withdraw::where('status', 1)->get();
        $finished = [];
        foreach($list2 as $itm) {
            $user = User::where('id', $itm->user_id)->first();
            $finished[] = [
                'id' => $itm->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'avatar' => $user->avatar,
                'system' => $itm->system,
                'wallet' => $itm->wallet,
                'value' => $itm->value,
                'status' => $itm->status
            ];
        }

        return view('admin.withdraw', compact('withdraws', 'finished'));
    }

    public function withdrawSend($id) {
        $withdraw = Withdraw::where('id', $id)->first();
        $balance_fk = $this->getBalans_frw();

        if($withdraw->system == 'qiwi') {
            $system = 63;
            $com = 1;
            $perc = 4;
        }
        if($withdraw->system == 'webmoney') {
            $system = 1;
            $com = 0;
            $perc = 6;
        }
        if($withdraw->system == 'yandex') {
            $system = 45;
            $com = 0;
            $perc = 0;
        }
        if($withdraw->system == 'fk') {
            $system = 133;
            $com = 0;
            $perc = 0;
        }
        if($withdraw->system == 'visa') {
            $system = 94;
            $com = 50;
            $perc = 4;
        }

        if($balance_fk < $withdraw->value) return redirect()->route('adminWithdraw')->with('error', 'На вашем кошельке недостаточно средств! Доступно: '.$balance_fk.'р.');

        $data = array(
            'wallet_id' => $this->settings->fk_wallet,
            'purse' => $withdraw->wallet,
            'amount' => $withdraw->value,
            'desc' => 'Withdraw for user #'.$withdraw->user_id,
            'currency' => $system,
            'sign' => md5($this->settings->fk_wallet.$system.$withdraw->value.$withdraw->wallet.$this->settings->fk_api),
            'action' => 'cashout',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fkwallet.ru/api_v1.php');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = trim(curl_exec($ch));
        $c_errors = curl_error($ch);
        curl_close($ch);

        $json = json_decode($result, true);
        if($json['status'] == 'error') {
            if($json['desc'] == 'Balance too low') {
                $desc = 'Попробуйте пожалуйста позже.';
                return redirect()->route('adminWithdraw')->with('error', $desc);
            } elseif($json['desc'] == 'Cant make payment to anonymous wallets') {
                $desc = 'Данный пользователь использует анонимный кошелек!.';
                return redirect()->route('adminWithdraw')->with('error', $desc);
            } elseif($json['desc'] == 'РЎР»РёС€РєРѕРј С‡Р°СЃС‚С‹Рµ Р·Р°РїСЂРѕСЃС‹ Рє API') {
                $desc = 'Неизвестная ошибка!.';
                return redirect()->route('adminWithdraw')->with('error', $desc);
            } else {
                return redirect()->route('adminWithdraw')->with('error', $json['desc']);
            }

        }

        if($json['status'] == 'info') {
            $withdraw->status = 1;
            $withdraw->save();
            return redirect()->route('adminWithdraw')->with('success', 'Ваша заявка поставлена в очередь. Вывод происходит в течении 24 часов.');
        }
    }

    public function withdrawReturn($id) {
        $withdraw = Withdraw::where('id', $id)->first();
        $user = User::where('id', $withdraw->user_id)->first();

        if($withdraw->system == 'qiwi') {
            $perc = 4;
            $com = 1;
            $min = 100;
        } elseif($withdraw->system == 'webmoney') {
            $perc = 6;
            $com = 0;
            $min = 10;
        } elseif($withdraw->system == 'yandex') {
            $perc = 0;
            $com = 0;
            $min = 10;
        } elseif($withdraw->system == 'visa') {
            $perc = 4;
            $com = 50;
            $min = 1000;
        }

        $valwithcom = ($withdraw->value+($min/100*$perc)+$com)*10;
        if($user->is_youtuber) $valwithcom = $val/10;

        $user->balance += $valwithcom;
        $user->save();
        $withdraw->status = 2;
        $withdraw->save();

        return redirect()->route('adminWithdraw')->with('success', 'Вы вернули '.$withdraw->value.'р. на баланс '.$user->username);
    }

    public function getMerchBalance() {
        $sign = md5($this->settings->mrh_ID.$this->settings->mrh_secret2);
        $xml_string = file_get_contents('http://www.free-kassa.ru/api.php?merchant_id='.$this->settings->mrh_ID.'&s='.$sign.'&action=get_balance');

        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $balance = json_decode($json, true);

        if($balance['answer'] == 'info') {
            $sum = $balance['balance'];
            if($sum >= 50) {
                sleep(11);
                return $this->sendToWallet($sum);
            } else {
                return [
                    'msg' => 'Not enough money on the balance of the merchant!',
                    'type' => $balance['answer']
                ];
            }
        } else {
            return [
                'msg' => $balance['desc'],
                'type' => $balance['answer']
            ];
        }
    }

    public function sendToWallet($sum) {
        $sign = md5($this->settings->mrh_ID.$this->settings->mrh_secret2);
        $xml_string = file_get_contents('http://www.free-kassa.ru/api.php?currency=fkw&merchant_id='.$this->settings->mrh_ID.'&s='.$sign.'&action=payment&amount='.$sum);

        $xml = simplexml_load_string($xml_string);
        $json = json_encode($xml);
        $res = json_decode($json, true);

        if($res['answer'] == 'info') {
            return [
                'msg' => $res['desc'].', PaymentId - '.$res['PaymentId'],
                'type' => $res['answer']
            ];
        } else {
            return [
                'msg' => $res['desc'],
                'type' => $res['answer']
            ];
        }
        return $res;
    }
}
