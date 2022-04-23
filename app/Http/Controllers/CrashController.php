<?php

namespace App\Http\Controllers;

use App\Profit;
use App\Crash;
use App\CrashBets;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Settings;
use DB;

class CrashController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Dont' touch it. He's angel!
        $this->pdo = DB::connection()->getPdo();
        $this->pdo->exec('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
        //
        $this->game = Crash::orderBy('id', 'desc')->first();
        $this->config = Settings::first();
    }

    public function index()
    {
		if(is_null($this->game)) $this->game = Crash::create([
			'secret' => $this->getSecret()
 		]);
        $game = [
            'hash' => $this->game->secret,
            'price' => CrashBets::where('round_id', $this->game->id)->sum('price'),
            'bets' => $this->getBets(),
        ];
        $gameStatus = $this->game->status == 0 ? 'false' : 'true';
        $bet = ($this->user) ? CrashBets::where('user_id', $this->user->id)->where('round_id', $this->game->id)->where('status', '<>', 2)->orderBy('id', 'desc')->first() : null;
        $history = $this->getHistory();
        return view('pages.crash', compact('game', 'bet', 'history', 'gameStatus'));
    }

    private function getBets()
    {
        $list = CrashBets::where('round_id',  $this->game->id)->orderBy('id', 'desc')->get();
        $bets = [];
        foreach($list as $bet)
        {
            $user = User::where('id', $bet->user_id)->first();
            if(!is_null($user)) $bets[] = [
                'user' => [
                    'username' => $user->username,
                    'avatar' => $user->avatar
                ],
                'price' => $bet->price,
                'withdraw' => number_format($bet->withdraw, 2, '.', '.'),
                'color' => $this->getNumberColor($bet->withdraw),
                'won' => $bet->won,
                'status' => $bet->status
            ];
        }

        return $bets;
    }

    private function getNumberColor($n)
    {
        if($n > 6.49) return '#F3752B';
        if($n > 3.99) return '#48A9A6';
        if($n > 2.49) return '#8A4FFF';
        if($n > 1.29) return '#4361EE';
        return '#ae435c';
    }

    public function createBet(Request $r)
    {
        // if($this->user->is_admin < 1) return [
        //     'success' => false,
        //     'msg' => 'В разработке!'
        // ];
        if($this->game->status > 0) return [
            'success' => false,
            'msg' => 'Ставки в этот раунд закрыты!'
        ];

        if(floatval($r->get('bet')) < $this->config->crash_min_bet) return [
            'success' => false,
            'msg' => 'Минимальная сумма ставки - ' . $this->config->crash_min_bet
        ];

        if($this->config->crash_max_bet > 0 && $this->config->crash_max_bet < floatval($r->get('bet'))) return [
            'success' => false,
            'msg' => 'Максимальная сумма ставки - ' . $this->config->crash_max_bet
        ];

        if($this->user->balance < floatval($r->get('bet'))) return [
            'success' => false,
            'msg' => 'Недостаточно баланса!'
        ];

        DB::beginTransaction();

        try {
            $bet = DB::table('crash_bets')
                        ->where('user_id', $this->user->id)
                        ->where('round_id', $this->game->id)
                        ->first();

            if(!is_null($bet))
            {
                DB::rollback();
                return [
                    'success' => false,
                    'msg' => 'Вы уже сделали ставку в этом раунде!'
                ];
            }

            DB::table('users')->where('id', $this->user->id)->update([
                'balance' => number_format($this->user->balance-floatval($r->get('bet')), 0, '.', '')
            ]);

            DB::table('crash_bets')->insert([
                'user_id' => $this->user->id,
                'round_id' => $this->game->id,
                'price' => floatval($r->get('bet')),
                'withdraw' => floatval($r->get('withdraw'))
            ]);

            DB::commit();

            // success commit
            $this->redis->publish('crash', json_encode([
                'type' => 'bet',
                'bets' => $this->getBets(),
                'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
            ]));


            $this->user = User::find($this->user->id);
            $this->redis->publish('updateBalance', json_encode([
                'id'    => $this->user->id,
                'balance' => $this->user->balance
            ]));
			$this->newBank($this->getBank());
            return [
                'success' => true,
                'msg' => 'Ваша ставка одобрена!',
                'bet' => floatval($r->get('bet')),
                'balance' => $this->user->balance,
            ];
        } catch(Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'msg' => 'Что-то пошло не так...'
            ];
        }
    }

	public function roundToTheNearestAnything($value, $roundTo) {
		$mod = $value%$roundTo;
		return $value+($mod<($roundTo/2)?-$mod:$roundTo-$mod);
	}

	public function random_float($min, $max, $includeMax) {
		return $min + \mt_rand(0, (\mt_getrandmax() - ($includeMax ? 0 : 1))) / \mt_getrandmax() * ($max - $min);
	}

	private function getUser() {
        $user = User::where('fake', 1)->inRandomOrder()->first();
        return $user;
    }

    public function addBetFake() {
		$user = $this->getUser();
		$o = [5, 10, 15];
		$ar_o = array_rand($o, 2);
		$sum = $this->roundToTheNearestAnything(mt_rand($this->settings->double_fake_min, $this->settings->double_fake_max), $o[$ar_o[0]]);
		$withdraw = 1.15; //round($this->random_float(1.15, 25, true), 2)
		$countBet = CrashBets::where(['user_id' => $user->id, 'round_id' => $this->game->id])->count();

        if($this->game->status > 0) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Crash] Ставки в этот раунд закрыты!'
        ];
		if($countBet == 5) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Crash] Этот пользователь уже задействован!'
        ];
        if(floatval($sum) < $this->config->crash_min_bet) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Crash] Минимальная сумма ставки - ' . $this->config->crash_min_bet
        ];

        if($this->config->crash_max_bet > 0 && $this->config->crash_max_bet < floatval($sum)) return [
            'success' => false,
            'fake' => $this->settings->fake,
            'msg' => '[Crash] Максимальная сумма ставки - ' . $this->config->crash_max_bet
        ];

        DB::beginTransaction();

        try {
            $bet = DB::table('crash_bets')
                        ->where('user_id', $user->id)
                        ->where('round_id', $this->game->id)
                        ->first();

            if(!is_null($bet))
            {
                DB::rollback();
                return [
                    'success' => false,
					'fake' => $this->settings->fake,
                    'msg' => '[Crash] Вы уже сделали ставку в этом раунде!'
                ];
            }

            DB::table('crash_bets')->insert([
                'user_id' => $user->id,
                'round_id' => $this->game->id,
                'price' => floatval($sum),
                'withdraw' => floatval($withdraw),
                'fake' => 1
            ]);

            DB::commit();

            // success commit
            $this->redis->publish('crash', json_encode([
                'type' => 'bet',
                'bets' => $this->getBets(),
                'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
            ]));
			$this->newBank($this->getBank());
            return [
                'success' => true,
				'fake' => $this->settings->fake,
                'msg' => '[Crash] Ваша ставка одобрена!'
            ];
        } catch(Exception $e) {
            DB::rollback();
            return [
                'success' => false,
				'fake' => $this->settings->fake,
                'msg' => '[Crash] Что-то пошло не так...'
            ];
        }
    }

    public function startSlider()
    {
        if($this->game->status == 1) return $this->game->multiplier;
        $this->game->status = 1;
        $this->game->save();

        $list = [2.28, 13.37];

        $this->game->multiplier = $this->getFloat();
        $this->game->save(); // да да да, двойное сохранение, я знаю йопта, просто тут у меня нет транзакции!!!

        // тут невзъебенные вычисления
        return $this->game->multiplier;
    }

    public function getFloat() {
		$profit = Profit::where('created_at', '>=', Carbon::today())->sum('sum');
        $betsPrice = CrashBets::where('round_id', $this->game->id)->sum('price');
        if($profit != 0) $percent = ($betsPrice/abs($profit))*100; else $percent = 0;

        // get last one
        $lastZero = Crash::where('multiplier', 1)->orderBy('id', 'desc')->first();
        if((is_null($lastZero) || ($this->game->id-$lastZero->id) >= mt_rand(2, 4)) && $percent >= 20) return 1;

        $list = [];
        for($i = 0; $i < 50; $i++) $list[] = 1;
        for($i = 0; $i < 25; $i++) $list[] = 2;
        for($i = 0; $i < 10; $i++) $list[] = 3;
        for($i = 0; $i < 9; $i++) $list[] = 4;
        for($i = 0; $i < 3; $i++) $list[] = 5;
        for($i = 0; $i < 2; $i++) $list[] = 10;
        for($i = 0; $i < 1; $i++) $list[] = 100;
        shuffle($list);

		if($this->game->multiplier) return $this->game->multiplier;
        $m = $list[mt_rand(0, count($list)-1)];

        if($m > 1) $m = mt_rand(1, $m);
		if($profit < ($profit + ($profit/2) * 1.2) && mt_rand(1, 10) > 5) return '1.'.mt_rand(0,4).mt_rand(0,9);
        if($m == 1 && $profit < ($profit + ($profit/2) * 1.2)) return $list[0].'.0'.mt_rand(0,9);

        return $m.'.'.mt_rand(0,9).mt_rand(1,9);
    }

    private function isTrue($chance)
    {
        $list = [];
        for($i = 0; $i < $chance; $i++) $list[] = true;
        for($i = 0; $i < (100-$chance); $i++) $list[] = false;
        shuffle($list);
        return $list[mt_rand(0, count($list)-1)];
    }

    public function Cashout()
    {
        if($this->game->status == 0) return [
            'success' => false,
            'msg' => 'Дождитесь начала раунда!'
        ];

        if($this->game->status == 3) return [
            'success' => false,
            'msg' => 'Этот раунд уже закрыт!'
        ];

        $bet = CrashBets::where('user_id', $this->user->id)->where('round_id', $this->game->id)->first();
        if(is_null($bet)) return [
            'success' => false,
            'msg' => 'Вы не делали ставку в этом раунде!'
        ];

        if($bet->status == 1) return [
            'success' => false,
            'msg' => 'Вы уже вывели свою ставку!'
        ];

        DB::beginTransaction();

        try {
            $cashout = floatval($this->redis->get('cashout'));
            if($cashout == 0)
            {
                DB::rollback();
                return [
                    'success' => false,
                    'msg' => 'Вы не можете вывести ставку! Раунд еще не начался, либо уже закончился...'
                ];
            }

            $float = floatval($this->redis->get('float'));
            if($bet->withdraw > 0 && $bet->withdraw < $float && $bet->withdraw < $this->game->multiplier) $float = $bet->withdraw;
            if($float <= 0 && $bet->withdraw < $float && $bet->withdraw < $this->game->multiplier) $float = $bet->withdraw;
            if($float <= 0)
            {
                DB::rollback();
                return [
                    'success' => false,
                    'msg' => 'Что-то пошло не так! Умножитель равен нулю!'
                ];
            }

            DB::table('users')
                    ->where('id', $this->user->id)
                    ->update([
                        'balance' => number_format($this->user->balance+floor($bet->price*$float), 0, '.', '')
                    ]);

            DB::table('crash_bets')
                    ->where('id', $bet->id)
                    ->update([
                        'withdraw' => $float,
                        'won' => floor($bet->price*$float),
                        'status' => 1
                    ]);

            DB::commit();

            // success commit
            $this->redis->publish('crash', json_encode([
                'type' => 'bet',
                'bets' => $this->getBets(),
                'price' => CrashBets::where('round_id', $this->game->id)->sum('price')
            ]));

            $this->user = User::find($this->user->id);
            $this->redis->publish('updateBalance', json_encode([
                'id'    => $this->user->id,
                'balance' => $this->user->balance
            ]));

            return [
                'success' => true,
                'msg' => 'Поздравляем! Вы умножили свою ставку на x'. $float .' и получили '. floor($bet->price*$float) .' монет!',
                'balance' => $this->user->balance,
                'won_sum' => floor($bet->price*$float),
                'float' => $float
            ];

        } catch(Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'msg' => 'Что-то пошло нет так...'
            ];
        }
    }

    public function newGame()
    {
        $this->game->status = 2;
        $this->game->save();

        $bets = CrashBets::where('round_id', $this->game->id)
                        ->where('withdraw', '>', 0)
                        ->where('status', 0)
                        ->get();

        DB::beginTransaction();
        try {
            foreach($bets as $bet)
            {
                $user = DB::table('users')->where('fake', 0)->where('id', $bet->user_id)->first();
                if(!is_null($user) && $bet->withdraw < $this->game->multiplier)
                {
                    DB::table('users')->where('fake', 0)->where('id', $bet->user_id)->update([
                        'balance' => $user->balance+floor($bet->price*$bet->withdraw)
                    ]);
                }
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
        }

        $bets = CrashBets::where('fake', 0)->where('round_id', $this->game->id)->get();
        $total = 0;
        foreach($bets as $bet) if($bet->status == 1) $total -= $bet->won-$bet->price; else $total += $bet->price;
        $this->game->profit = $total;
        $this->game->save();

		Profit::create([
			'game' => 'crash',
			'sum' => $total
		]);

        $this->game = Crash::create([
            'secret' => $this->getSecret()
        ]);

        $this->redis->publish('crash', json_encode([
            'type' => 'game',
            'hash' => $this->game->secret,
            'history' => $this->getHistory()
        ]));
		$this->newBank(0);
        return [
            'success' => true,
            'id' => $this->game->id
        ];
    }

    private function getHistory()
    {
        $list = Crash::select('multiplier', 'secret')->where('status', 2)->orderBy('id', 'desc')->limit(14)->get();
        for($i = 0; $i < count($list); $i++) $list[$i]->color = $this->getColor($list[$i]->multiplier);
        return $list;
    }

    private function getColor($float)
    {
        return $this->getNumberColor($float);
    }

    private function getSecret()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $str = '';
        for($i = 0; $i < 22; $i++) $str.=$chars[mt_rand(0, mb_strlen($chars)-1)];
        $game = Crash::where('secret', $str)->first();
        if($game) return $this->getSecret();
        return $str;
    }

    public function init()
    {
        return response()->json([
            'id' => $this->game->id,
            'status' => $this->game->status,
            'timer' => $this->settings->crash_timer
        ]);
    }

    public function lastBet()
    {
        $bet = CrashBets::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
        return (is_null($bet)) ? 0 : $bet->price;
    }

    public function getBank()
    {
        $crash = CrashBets::where('round_id', $this->game->id)->sum('price');
        return $crash ? $crash : 0;
    }

	public function newBank($sum) {
		$this->redis->publish('updateBank', json_encode([
            'game'    => 'crash',
            'sum' => $sum
        ]));
	}

	public function gotThis(Request $r) {
		$multiplier = $r->get('multiplier');

		if($this->game->status > 1) return [
			'msg'       => 'Игра началась, вы не можете подкрутить!',
			'type'      => 'error'
		];

		if(!$this->game->id) return [
			'msg'       => 'Не удалось получить номер игры!',
			'type'      => 'error'
		];

		if(!$multiplier) return [
			'msg'       => 'Не удалось получить множитель!',
			'type'      => 'error'
		];

		Crash::where('id', $this->game->id)->update([
			'multiplier'      => $multiplier
		]);

		return [
			'msg'       => 'Вы подкрутили на x'.$multiplier.'!',
			'type'      => 'success'
		];
	}
}