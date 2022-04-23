<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Redis;
use App\Settings;
use App\Dice;
use App\Profit;
use Carbon\Carbon;

class DiceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
		$list = Dice::limit(9)->orderBy('id', 'desc')->get();
		$last = Dice::orderBy('id', 'desc')->first();
		$hash = bin2hex(random_bytes(16));
		if($this->user) Redis::set('dice.hash.' . $this->user->id, $hash);
		$sum = Dice::orderBy('id', 'desc')->sum('sum');
		$betsCount = ($last ? $last->id : 0);
		$betsSum = ($sum ? $sum : 0);
		$game = [];
		foreach($list as $l) {
			$user = User::where('id', $l->user_id)->first();
			
			$game[] = [
				'username' => $user->username,
				'sum' => $l->sum,
				'num' => $l->num,
				'vip' => $l->vip,
				'perc' => $l->perc,
				'win' => $l->win,
				'win_sum' => $l->win_sum,
				'hash' => $l->hash
			];
		}
        return view('pages.dice', compact('game', 'betsCount', 'betsSum', 'hash'));
    }
	
	public function play(Request $r) {
		//if(!$this->user->is_admin) return;
		$profit = Profit::where('created_at', '>=', Carbon::today())->sum('sum');
		$perc = preg_replace('/[^0-9.]/', '', $r->perc);
        $sum = preg_replace('/[^0-9.]/', '', floor($r->sum));
		
		if($sum < 10) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Минимальная сумма ставки 10 монет!',
                'type'  => 'error'
            ]));
            return;
		}
		if($sum > 500000) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Минимальная сумма ставки 500000 монет!',
                'type'  => 'error'
            ]));
            return;
		}
		if($sum > $this->user->balance) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Не хватает монет для ставки!',
                'type'  => 'error'
            ]));
            return;
		}
		if(!$perc) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы не ввели шанс на победу!',
                'type'  => 'error'
            ]));
            return;
		}
		if(!$sum) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы не ввели сумму ставки!',
                'type'  => 'error'
            ]));
            return;
		}
		if($perc < 2) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы ввели неверный шанс!',
                'type'  => 'error'
            ]));
            return;
		}
		if($perc > 99) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Вы ввели неверный шанс!',
                'type'  => 'error'
            ]));
            return;
		}
		
		$chance = round(100 - ($perc),2);
		$vip = round(99/$chance,2);
		$rand = rand(0, 10000);
		$generate = $rand / 100;
		$ret = 0;
		
		if($profit < ($profit + ($profit/2) * 1.2) && mt_rand(1, 40) > 20) {
		/*if(mt_rand(1, 6) == 3) { */
			while($perc <= $generate) {
				$chance = round(100 - ($perc),2);
				$vip = round(99/$chance,2);
				$rand = rand(0, 10000);
				$generate = $rand / 100;
			}
			$ret = 1;
		}
		/*}*/
		
		if($sum == floor($sum*$vip)) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Ваша ставка равна выигрышу!',
                'type'  => 'error'
            ]));
            return;
		}
		
		$win = 0;
		$win_sum = 0;
		$profit = 0;
		
		if($perc <= $generate) {
			$win = 1;
			$win_sum += floor($sum*$vip)-$sum;
			$profit -= floor($sum*$vip)-$sum;
		} else {
			$win = 0;
			$win_sum -= floor($sum);
			$profit += floor($sum);
		}
		
		$this->user->balance += $win_sum;
		$this->user->save();
		
		$hash = Redis::get('dice.hash.' . $this->user->id);
		
		Dice::create([
			'user_id' => $this->user->id,
			'sum' => $sum,
			'perc' => $chance,
			'vip' => $vip,
			'num' => $generate,
			'win' => $win,
			'win_sum' => $win_sum,
			'hash' => $hash
		]);
		
		Profit::create([
			'game' => 'dice',
			'sum' => $profit
		]);
		
		$lastGame = Dice::orderBy('id', 'desc')->first();
		$betsSum = Dice::orderBy('id', 'desc')->sum('sum');
		$betsCount = ($lastGame ? $lastGame->id : 0);
		$betsSumToday = ($betsSum ? $betsSum : 0);
		
		$this->redis->publish('dice', json_encode([
            'username' => $this->user->username,
			'sum' => $sum,
			'num' => $generate,
			'vip' => $vip,
			'perc' => $chance,
			'win' => $win,
			'win_sum' => $win_sum,
			'betsSum' => $betsSumToday,
			'betsCount' => $betsCount,
			'hash' => $hash
        ]));
		
		Dice::where('updated_at', '>=', Carbon::today()->addDays(2))->delete();
		
		Redis::del('dice.hash.' . $this->user->id);
		
		$newHash = bin2hex(random_bytes(16));
		Redis::set('dice.hash.' . $this->user->id, $newHash);
		
		return [
			'status' => 'success',
			'chislo' => $generate,
			'chance' => $chance,
			'win' => $win,
			'hash' => $newHash,
			'balance' => $this->user->balance,
			'22' => $ret
		];
	}
	
	public function addBetFake() {
		$user = $this->getUser();
		
		if(!$user) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Не удалось получить пользователя!'
        ];
		
		$perc = round((1 + (98-1)) * mt_rand(0, 2147483647) / 2147483647, 2);
		
		$o = [5, 10, 15];
		$ar_o = array_rand($o, 2);
		$sum = $this->roundToTheNearestAnything(mt_rand($this->settings->dice_fake_min, $this->settings->dice_fake_max), $o[$ar_o[0]]);
		
		if($sum < 10) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Минимальная сумма ставки 10 монет!'
        ];
		if($sum > 500000) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Минимальная сумма ставки 500000 монет!'
        ];
		if(!$perc) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Вы не ввели шанс на победу!'
        ];
		if(!$sum) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Вы не ввели сумму ставки!'
        ];
		if($perc < 2) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Вы ввели неверный шанс!'
        ];
		if($perc > 99) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Вы ввели неверный шанс!'
        ];
		
		$chance = round(100 - ($perc),2);
		$vip = round(99/$chance,2);
		$rand = rand(0, 10000);
		$generate = $rand / 100;
		
		if($sum == floor($sum*$vip)) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Ваша ставка равна выигрышу!'
        ];
		
		$win = 0;
		$win_sum = 0;
		$hash = bin2hex(random_bytes(16));
		
		if($perc <= $generate) {
			$win = 1;
			$win_sum += floor($sum*$vip)-$sum;
		} else {
			$win = 0;
			$win_sum -= floor($sum);
		}
		
		Dice::create([
			'user_id' => $user->id,
			'sum' => $sum,
			'perc' => $chance,
			'vip' => $vip,
			'num' => $generate,
			'win' => $win,
			'win_sum' => $win_sum,
			'hash' => $hash,
			'is_fake' => 1
		]);
		
		$username = $user->username;
		
		if($user->is_moder) $username = '<span class="mod-ico"></span>'.$username;
		if($user->is_youtuber) $username = '<span class="yt-ico"></span>'.$username;
		if($user->is_vip) $username = '<span class="vip-ico"></span>'.$username;
		
		$lastGame = Dice::orderBy('id', 'desc')->first();
		$betsSum = Dice::orderBy('id', 'desc')->sum('sum');
		$betsCount = ($lastGame ? $lastGame->id : 0);
		$betsSumToday = ($betsSum ? $betsSum : 0);
		
		$this->redis->publish('dice', json_encode([
            'username' => $username,
			'sum' => $sum,
			'num' => $generate,
			'vip' => $vip,
			'perc' => $chance,
			'win' => $win,
			'win_sum' => $win_sum,
			'betsSum' => $betsSumToday,
			'betsCount' => $betsCount,
			'hash' => $hash
        ]));
		
		Dice::where('updated_at', '<', Carbon::today())->delete();
		
		return [
            'success' => true,
            'fake' => $this->settings->fake,
            'msg' => '[Dice] Ставка сделана!'
        ];
	}
	
	public function roundToTheNearestAnything($value, $roundTo) {
		$mod = $value%$roundTo;
		return $value+($mod<($roundTo/2)?-$mod:$roundTo-$mod);
	}
	
	public function adminBet(Request $r) {
		$user = User::where('user_id', $r->get('user'))->first();
		
		$perc = preg_replace('/[^0-9.]/', '', $r->get('perc'));
		$perc = round(100 - ($perc),2);
        $sum = preg_replace('/[^0-9.]/', '', floor($r->get('sum')));
		
		if($sum < 10) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Минимальная сумма ставки 10 монет!'
        ];
		if($sum > 500000) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Минимальная сумма ставки 500000 монет!'
        ];
		if(!$perc) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Вы не ввели шанс на победу!'
        ];
		if(!$sum) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Вы не ввели сумму ставки!'
        ];
		if($perc < 1) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Вы ввели неверный шанс!'
        ];
		if($perc > 98) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Вы ввели неверный шанс!'
        ];
		
		$chance = round(100 - ($perc),2);
		$vip = round(99/$chance,2);
		$rand = rand(0, 10000);
		$generate = $rand / 100;
		
		if($sum == floor($sum*$vip)) return [
            'success' => false,
			'type' => 'error',
            'msg' => '[Dice] Ваша ставка равна выигрышу!'
        ];
		
		$win = 0;
		$win_sum = 0;
		$hash = bin2hex(random_bytes(16));
		
		if($perc <= $generate) {
			$win = 1;
			$win_sum += floor($sum*$vip)-$sum;
		} else {
			$win = 0;
			$win_sum -= floor($sum);
		}
		
		Dice::create([
			'user_id' => $user->id,
			'sum' => $sum,
			'perc' => $chance,
			'vip' => $vip,
			'num' => $generate,
			'win' => $win,
			'win_sum' => $win_sum,
			'hash' => $hash,
			'is_fake' => 1
		]);
		
		$username = $user->username;
		
		if($user->is_moder) $username = '<span class="mod-ico"></span>'.$username;
		if($user->is_youtuber) $username = '<span class="yt-ico"></span>'.$username;
		if($user->is_vip) $username = '<span class="vip-ico"></span>'.$username;
		
		$lastGame = Dice::orderBy('id', 'desc')->first();
		$betsSum = Dice::orderBy('id', 'desc')->sum('sum');
		$betsCount = ($lastGame ? $lastGame->id : 0);
		$betsSumToday = ($betsSum ? $betsSum : 0);
		
		$this->redis->publish('dice', json_encode([
            'username' => $username,
			'sum' => $sum,
			'num' => $generate,
			'vip' => $vip,
			'perc' => $chance,
			'win' => $win,
			'win_sum' => $win_sum,
			'betsSum' => $betsSumToday,
			'betsCount' => $betsCount,
			'hash' => $hash
        ]));
		
		Dice::where('updated_at', '<', Carbon::today())->delete();
		
		return [
            'success' => true,
			'type' => 'success',
            'msg' => '[Dice] Ставка сделана!'
        ];
	}
	
	private function getUser() {
        $user = User::where('fake', 1)->inRandomOrder()->first();
		if($user->time != 0) {
			$now = Carbon::now()->format('H');
			if($now < 06) $time = 4;
			if($now >= 06 && $now < 12) $time = 1;
			if($now >= 12 && $now < 18) $time = 2;
			if($now >= 18) $time = 3;
        	$user = User::where(['fake' => 1, 'time' => $time])->inRandomOrder()->first();
		}
        return $user;
    }
}