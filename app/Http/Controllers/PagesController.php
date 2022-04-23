<?php namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Bonus;
use App\Dice;
use App\Sends;
use App\Jackpot;
use App\CoinFlip;
use App\Roulette;
use App\Battle;
use App\BonusLog;
use App\Payments;
use App\Promocode;
use App\SuccessPay;
use App\Withdraw;
use App\Settings;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function __construct(Request $r)
    {
        parent::__construct();
    }

    public function homePage()
    {
        return view('pages.home');
    }

    public function reviews() {
        return view('pages.reviews');
    }

    public function deposit() {
        return view('pages.deposit');
    }

    public function withdrawPage() {
        return view('pages.withdraw');
    }

    public function history() {
        $pays = SuccessPay::where('user', $this->user->user_id)->where('status', '>=', 1)->get();
        $withdraws = Withdraw::where('user_id', $this->user->id)->where('status', '>=', 0)->get();
        $active = Withdraw::where('user_id', $this->user->id)->where('status', 0)->get();
        return view('pages.history', compact('pays', 'withdraws', 'active'));
    }

	public function faq() {
		return view('pages.faq');
	}

	public function rules() {
		return view('pages.rules');
	}
	
	public function rank() {
		return view('pages.rank');
	}
	
	public function raffle() {
		return view('pages.raffle');
	}
	
	public function mines() {
		return view('pages.mines');
	}

	public function paySend() {
		return view('pages.paySend');
	}

    public function sendCreate(Request $r) {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
        $target = $r->get('target');
        $sum = $r->get('sum');
        $value = floor($sum*1.05);

        $with = Withdraw::where('user_id', $this->user->id)->where('status', 1)->sum('value');
        $user = User::where('user_id', $target)->first();

		if($value < 1) {
			return [
				'success' => false,
				'msg' => 'Вы ввели не правильное значение!',
				'type' => 'error'
			];
		}

		if(!$this->user->is_admin && !$this->user->is_youtuber) {
			if($with < $this->settings->sendwith) return [
				'success' => false,
				'msg' => 'Вы не сделали вывод в размере '.$this->settings->sendwith.' рублей!',
				'type' => 'error'
			];
		}

        if(!$user) return [
            'success' => false,
            'msg' => 'Пользователя с таким ID нет!',
            'type' => 'error'
        ];

        if($target == $this->user->user_id) return [
            'success' => false,
            'msg' => 'Вы не можете отправлять монеты себе!',
            'type' => 'error'
        ];

        if($value > $this->user->balance) return [
            'success' => false,
            'msg' => 'Вы не можете отправить сумму больше чем Ваш баланс!',
            'type' => 'error'
        ];

        if($value < $this->settings->min_sendwith) return [
            'success' => false,
            'msg' => 'Минимальная сумма перевода '.$this->settings->min_sendwith.' монет!',
            'type' => 'error'
        ];

        if($value > $this->settings->max_sendwith) return [
            'success' => false,
            'msg' => 'Максимальная сумма перевода '.$this->settings->max_sendwith.' монет!',
            'type' => 'error'
        ];

        if(!$value || !$target) return [
            'success' => false,
            'msg' => 'Вы не вели одно из значений!',
            'type' => 'error'
        ];

        $this->user->balance -= $value;
        $this->user->save();

        $user->balance += $sum;
        $user->save();

		Sends::create([
			'sender' => $this->user->id,
			'receiver' => $user->id,
			'sum' => $sum
		]);

        $this->redis->publish('updateBalance', json_encode([
            'id'      => $this->user->id,
            'balance' => $this->user->balance
        ]));

        $this->redis->publish('updateBalance', json_encode([
            'id'      => $user->id,
            'balance' => $user->balance
        ]));

        return [
            'success' => true,
            'msg' => 'Вы перевели '.$sum.' <i class="fas fa-coins"></i> пользователю '.$user->username.'!',
            'type' => 'success'
        ];
    }

	public function payHistory() {
		$pays = SuccessPay::where('user', $this->user->user_id)->where('status', '>=', 1)->get();
        $withdraws = Withdraw::where('user_id', $this->user->id)->where('status', '>', 0)->get();
		$active = Withdraw::where('user_id', $this->user->id)->where('status', 0)->get();
		return view('pages.payHistory', compact('pays', 'withdraws', 'active'));
	}

	public function profile() {
		if ( Auth::user() ){
		$ref = User::where('referred_by', $this->user->affiliate_id)->count();
		$lvl = 0;
		$perc = 0;
		$width = 0;
		$min = 0;
		$max = 0;
		if($ref < 10) {
			$lvl = 1;
			$perc = 0.5;
			$max = 10;
			$width = ($ref/$max)*100;
		} elseif($ref >= 10 && $ref < 100) {
			$lvl = 2;
			$perc = 0.7;
			$max = 100;
			$width = ($ref/$max)*100;
		} elseif($ref >= 100 && $ref < 500) {
			$lvl = 3;
			$perc = 1;
			$max = 500;
			$width = ($ref/$max)*100;
		} else {
			$lvl = 4;
			$perc = 1.5;
			$max = 500;
			$width = 100;
		}

        $promocode = Promocode::where('user_id', $this->user->id)->get();
		return view('pages.profile', compact('ref', 'perc', 'perc', 'width', 'lvl', 'promocode'));

	}else{

return redirect()->route('jackpot');

	}
}
	public function ref(){

		return redirect()->route('profile');

	}
	public function refActivate(Request $r) {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
        $code = strtolower($r->get('code'));

        if(!$code) return [
            'success' => false,
            'msg' => 'Вы не ввели код!',
            'type' => 'error'
        ];

        $refcode = User::where('affiliate_id', $code)->first();
        $promocode = Promocode::where('code', $code)->first();

        if(!$refcode && !$promocode) return [
            'success' => false,
            'msg' => 'Такого кода не существует!',
            'type' => 'error'
        ];

        if($refcode) {
            $money = $this->settings->ref_invite;
            if($code == $this->user->affiliate_id) return [
                'success' => false,
                'msg' => 'Вы не можете активировать свой код!',
                'type' => 'error'
            ];

            if($this->user->referred_by) return [
                'success' => false,
                'msg' => 'Вы уже активировали код!',
                'type' => 'error'
            ];

            $this->user->balance += $money;
            $this->user->referred_by = $code;
            $this->user->save();

            SuccessPay::insert([
                'user' => $this->user->user_id,
                'price' => $money,
                'code' => $code,
                'status' => 2,
            ]);
        }
        if($promocode) {
            $money = $promocode->amount;
            $check = SuccessPay::where('user', $this->user->user_id)->where('code', $code)->first();

            if($check) return [
                'success' => false,
                'msg' => 'Вы уже активировали код!',
                'type' => 'error'
            ];

            if($promocode->limit == 1 && $promocode->count_use <= 0) return [
                'success' => false,
                'msg' => 'Код больше не действителен!',
                'type' => 'error'
            ];

            if($promocode->user_id == $this->user->id) return [
                'success' => false,
                'msg' => 'Вы не можете активировать свой промокод!',
                'type' => 'error'
            ];

            $this->user->balance += $money;
            $this->user->save();

            if($promocode->limit == 1 && $promocode->count_use > 0){
                $promocode->count_use -= 1;
                $promocode->save();
            }

            SuccessPay::insert([
                'user' => $this->user->user_id,
                'price' => $money,
                'code' => $code,
                'status' => 3,
            ]);
        }

        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));

        return [
            'success' => true,
            'msg' => 'Код активирован!',
            'type' => 'success'
        ];
    }

	public function getMoney() {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
        $ref_money = floor($this->user->ref_money);
        if($ref_money < 0.99) return [
            'success' => false,
            'msg' => 'Вам нечего забирать!',
            'type' => 'error'
        ];
        $this->user->balance += $ref_money;
        $this->user->ref_money -= $ref_money;
        $this->user->save();

        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));

        return [
            'success' => true,
            'msg' => 'Вы забрали монеты!',
            'type' => 'success'
        ];
    }

	public function bonus() {
		$bonus = Bonus::get();
		if($bonus == '[]') {
			$line = 0;
			$check = 0;
		} else {
			$bonusLog = BonusLog::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
			$line = [];
			foreach($bonus as $b) {
				for($i = 0; $i < 100; $i++) {
					$line[] = [
						'id' => $i,
						'sum' => $b->sum,
						'color' => $b->color
					];
				}
			}
			shuffle($line);
			array_splice($line, 99);
			$winner = Bonus::where('status', 1)->inRandomOrder()->first();

			$win = [
				'id' => 'win',
				'sum' => $winner->sum,
				'color'  => $winner->color
			];

			$line[80] = $win;
			$check = 0;
			if($bonusLog) {
				if($bonusLog->remaining) {
					$nowtime = time();
					$time = $bonusLog->remaining;
					$lasttime = $nowtime - $time;
					if($time >= $nowtime) {
						$check = 1;
					}
				}
				$bonusLog->status = 2;
				$bonusLog->save();
			}
		}

		return view('pages.bonus', compact('line', 'check'));
	}

	public function getBonus(Request $r) {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
		$validator = \Validator::make($r->all(), [
            'recapcha' => 'required|captcha',
        ]);

		if($validator->fails()) {
            return [
				'success' => false,
				'msg' => 'Вы не прошли проверку на я не робот!',
				'type' => 'error'
			];
        }
		$bonus = Bonus::get();
		$bonusLog = BonusLog::where('user_id', $this->user->id)->orderBy('id', 'desc')->first();
		$vk_ckeck = $this->groupIsMember($this->user->user_id);

		if($vk_ckeck == 0) {
            return [
                'success' => false,
                'msg' => 'Вы не состоите в нашей группе!',
                'type' => 'error'
            ];
        }

        if($vk_ckeck == NULL) {
            return [
                'success' => false,
                'msg' => 'Выдача бонусов временно не работает!',
                'type' => 'error'
            ];
        }

		if($bonusLog) {
			if($bonusLog->remaining) {
                $nowtime = time();
                $time = $bonusLog->remaining;
                $lasttime = $nowtime - $time;
                if($time >= $nowtime) {
                    return [
                        'success' => false,
                        'msg' => 'Следующий бонус Вы сможете получить: '.date("d.m.Y H:i:s", $time),
                        'type' => 'error'
                    ];
                }
            }
            $bonusLog->status = 2;
            $bonusLog->save();
		}

		$line = [];
        foreach($bonus as $b) {
            for($i = 0; $i < 100; $i++) {
				$line[] = [
					'id' => $i,
					'sum' => $b->sum,
					'color' => $b->color
				];
            }
        }
        shuffle($line);
        array_splice($line, 99);
		$winner = Bonus::where('status', 1)->inRandomOrder()->first();

		$win = [
			'sum' => $winner->sum,
			'color'  => $winner->color
		];

        $line[80] = $win;
		$remaining = Carbon::now()->addDay(1)->getTimestamp();

		BonusLog::create([
            'user_id' => $this->user->id,
            'sum' => $win['sum'],
            'remaining' => $remaining,
            'status' => 1
        ]);

		$this->user->balance += $win['sum'];
        $this->user->save();

		$this->redis->publish('updateBalanceAfter', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));

		$this->redis->publish('bonus', json_encode([
			'user_id'    => $this->user->id,
			'line'       => $line,
			'ml'		 => 6742
		]));

		$this->redis->publish('bonus_win', json_encode([
			'user'  => $this->user->id,
			'msg'   => 'Вы получили бонус в размере '.$win['sum'].' '.trans_choice('монету|монеты|монет', $win['sum']).'!',
			'type'  => 'success'
		]));

		return [
            'success' => true,
            'msg' => 'Открываем!',
            'type' => 'success'
        ];
	}

	private function groupIsMember($id) {
        $user_id = $id;
        $vk_url = $this->settings->vk_url;
        if(!$vk_url) $group = NULL;
        $old_url = ($vk_url);
        $url = explode('/', trim($old_url,'/'));
        $url_parse = array_pop($url);
        $url_last = preg_replace('/&?club+/i', '', $url_parse);
        $runfile = 'https://api.vk.com/method/groups.isMember?v=5.21&group_id='.$url_last.'&user_id='.$user_id.'&access_token='.$this->settings->vk_secret;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $runfile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $group = curl_exec($ch);
        curl_close($ch);
        $group = json_decode($group, true);

        if(isset($group['error'])) {
            $group = NULL;
        } else {
            $group = $group['response']; // Получаем массив комментариев
        }
        return $group;
    }

	public function withdraw_cancel($id) {
		if(\Cache::has('bet.user.' . $this->user->id)) return redirect()->route('payhistory')->with('error', 'Подождите перед предыдущим действием!');
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
        $withdraw = Withdraw::where('id', $id)->first();

		if($withdraw->status > 0) return redirect()->route('payhistory')->with('error', 'Вы не можете отменить данный вывод!');
		if($withdraw->user_id != $this->user->id) return redirect()->route('payhistory')->with('error', 'Вы не можете отменить вывод другого пользователя!');

		$this->user->balance += $withdraw->sum;
        $this->user->save();
        $withdraw->status = 2;
        $withdraw->save();

		return redirect()->route('payhistory')->with('success', 'Вы отменили вывод на '.$withdraw->value.'р.');
	}

    public function pay(Request $r) {
        $sum = $r->get('num');
        $dep_code = $r->get('dep_code');
        
		if(!$sum) return \Redirect::back();
		if(is_null($dep_code)) $dep_code = null;
       
        
        Settings::where('id', 1)->update([
            'order_id' => $this->settings->order_id+1
        ]);
            
        return Redirect('https://www.free-kassa.ru/merchant/cash.php?m='.$this->settings->mrh_ID.'&oa='.$r->get('num').'&o='.$this->settings->order_id.'&us_uid='.$this->user->user_id.'&us_code='.$dep_code.'&s='.md5($this->settings->mrh_ID.':'.$sum.':'.$this->settings->mrh_secret1.':'.$this->settings->order_id));

	}

	public function result(Request $r) {
        $ip = false;
        if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $this->getIp($_SERVER['HTTP_X_REAL_IP']);
        } else {
            $ip = $this->getIp($_SERVER['REMOTE_ADDR']);
        }
        if(!$ip) return redirect()->route('jackpot')->with('error', 'Ошибка при проверке IP free-kassa!');

		$order = $this->chechOrder($r->get('MERCHANT_ORDER_ID'), $r->get('AMOUNT'));
		if($order['type'] == 'error') return ['msg' => $order['msg'], 'type' => 'error'];

		$dep_code = $r->get('us_code');

		/*CREATE PAY*/
        $pay = [
            'secret' => md5($this->settings->mrh_ID . ":" . $r->get('AMOUNT') . ":" . $this->settings->mrh_secret1 . ":" . $this->settings->order_id),
            'merchant_id' => $this->settings->mrh_ID,
            'order_id' => $r->get('MERCHANT_ORDER_ID'),
            'sum' => $r->get('AMOUNT'),
            'user_id' => $r->get('us_uid'),
            'dep_code' => $dep_code,
            'status' => 0
        ];
        Payments::insert($pay);

        $user = User::where('user_id', $r->get('us_uid'))->first();
        if(!$user) return ['msg' => 'User not found!', 'type' => 'error'];

		$sum = $r->get('AMOUNT');

		if(!is_null($dep_code) && $sum >= 100) {
			if($this->settings->dep_perc) if($dep_code == $this->settings->dep_code) $sum = $r->get('AMOUNT')+($r->get('AMOUNT')/100*$this->settings->dep_perc);
		}

		/* ADD Balance from user and partner */
        $sum = floor($sum*10);
        User::where('user_id', $user->user_id)->update([
            'balance' => $user->balance+$sum
        ]);
        /*REDIRECT*/

		SuccessPay::insert([
        	'user' => $user->user_id,
            'price' => $sum,
            'status' => 1,
       	]);

        Payments::where('order_id', $r->get('MERCHANT_ORDER_ID'))->update([
            'status' => 1
        ]);

        /* SUCCESS REDIRECT */
        return ['msg' => 'Your order #'.$r->get('MERCHANT_ORDER_ID').' has been paid successfully!', 'type' => 'success'];
	}

	private function chechOrder($id, $sum) {
		$merch = Payments::where('order_id', $id)->first();
		if(!$merch) return ['msg' => 'Order checked!', 'type' => 'success'];
		if($sum != $merch->sum) return ['msg' => 'You paid another order!', 'type' => 'error'];
		if($merch->order_id == $id && $merch->status == 1) return ['msg' => 'Order alredy paid!', 'type' => 'error'];

		return ['msg' => 'Order checked!', 'type' => 'success'];
	}

    /* CHECK FREE KASSA IP */
    function getIp($ip) {
        $list = ['136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98', '37.1.14.226', '136.243.38.108', '80.71.251.29'];
        for($i = 0; $i < count($list); $i++) {
            if($list[$i] == $ip) return true;
        }
        return false;
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

	public function success() {
		return redirect()->route('jackpot')->with('success', 'Ваш баланс успешно пополнен!');
	}

	public function fail() {
		return redirect()->route('jackpot')->with('error', 'Ошибка при пополнении баланса!');
	}

    public function withdraw(Request $r) {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
        $system = $r->get('system');
        $value = $r->get('value');
        $wallet = str_replace([' ', '+', '(', ')', '-'], '', $r->get('wallet'));
        $val = floor($value);

        $dep = SuccessPay::where('user', $this->user->user_id)->where('status', 1)->sum('price')/10;

        if($dep < 50) return [
            'success' => false,
            'msg' => 'Вам необходимо пополнить счет на 100 рублей для вывода средств!',
            'type' => 'error'
        ];

        if($system == 'qiwi') {
            $perc = 4;
            $com = 1;
        } elseif($system == 'webmoney') {
            $perc = 6;
            $com = 0;
        } elseif($system == 'yandex') {
            $perc = 0;
            $com = 0;
        } elseif($system == 'visa') {
            $perc = 4;
            $com = 50;
        }

        $valwithcom = round(($val-($val/100*$perc)+($com*10))/10);
        $valwithcom = round($val / 10);
        if($this->user->is_youtuber) $valwithcom = $val/10;

        if($system == 'qiwi' && $valwithcom < 100) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 100 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        } elseif($system == 'webmoney' && $valwithcom < 10) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 10 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        } elseif($system == 'yandex' && $valwithcom < 10) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 10 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        } elseif($system == 'visa' && $valwithcom < 1000) {
            return [
                'success' => false,
                'msg' => 'Минимальная сумма для вывода 1000 рублей с учетом комиссии!',
                'type' => 'error'
            ];
        }

        if($valwithcom > 5000) {
            return [
                'success' => false,
                'msg' => 'Максимальная сумма для вывода 5000 рублей! с учетом комиссии',
                'type' => 'error'
            ];
        }

        if($valwithcom == 0) return [
            'success' => false,
            'msg' => 'Не правильно введена сумма!',
            'type' => 'error'
        ];
        if(is_null($system) || is_null($val) || is_null($wallet)) return [
            'success' => false,
            'msg' => 'Вы не заполнили один из пунктов!',
            'type' => 'error'
        ];
        if($val > $this->user->balance) return [
            'success' => false,
            'msg' => 'Вы не можете вывести сумму больше чем Ваш баланс!',
            'type' => 'error'
        ];

        Withdraw::insert([
            'user_id' => $this->user->id,
            'value' => $valwithcom,
            'sum' => $val,
            'system' => $system,
            'wallet' => $wallet
        ]);

        $this->user->balance -= $val;
        $this->user->save();

        $this->redis->publish('updateBalance', json_encode([
            'id' => $this->user->id,
            'balance' => $this->user->balance
        ]));

        return [
            'success' => true,
            'msg' => 'Вы оставили заявку на вывод!',
            'type' => 'success'
        ];
    }

	public function fair() {
		return view('pages.fair');
	}

	public function fairGame($hash) {
		return view('pages.fairGame', compact('hash'));
	}

	public function fairCheck(Request $r) {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
		$hash = $r->get('hash');
		if(!$hash) return [
			'success' => false,
			'type' => 'error',
			'msg' => 'Поле не может быть пустым!'
		];
		$dice = Dice::where('hash', $hash)->first();
		$double = Roulette::where(['hash' => $hash, 'status' => 3])->first();
		$coin = CoinFlip::where(['hash' => $hash, 'status' => 1])->first();
		$jackpot = Jackpot::where(['hash' => $hash, 'status' => 3])->first();
		$battle = Battle::where(['hash' => $hash, 'status' => 3])->first();

		if(!is_null($dice)) {
			$info = [
				'id' => $dice->id,
				'number' => $dice->num
			];
		} elseif(!is_null($double)) {
			$info = [
				'id' => $double->id,
				'number' => $double->winner_num
			];
		} elseif(!is_null($jackpot)) {
			$info = [
				'id' => $jackpot->game_id,
				'number' => $jackpot->winner_ticket
			];
		} elseif(!is_null($coin)) {
			$info = [
				'id' => $coin->id,
				'number' => $coin->winner_ticket
			];
		} elseif(!is_null($battle)) {
			$info = [
				'id' => $battle->id,
				'number' => $battle->winner_ticket
			];
		} else {
			return [
				'success' => false,
				'type' => 'error',
				'msg' => 'Неверный хэш или раунд еще не сыгран!'
			];
		}
		return [
			'success' => true,
			'type' => 'success',
			'msg' => 'Хэш найден!',
			'round' => $info['id'],
			'number' => $info['number']
		];
	}

	public function unbanMe() {
		if(\Cache::has('bet.user.' . $this->user->id)) return response()->json(['msg' => 'Подождите перед предыдущим действием!', 'type' => 'error']);
        \Cache::put('bet.user.' . $this->user->id, '', 0.10);
		if(!$this->user->banchat) return [
			'success' => false,
			'type' => 'error',
			'msg' => 'Вы не забанены в чате!'
		];
		if($this->user->balance < 500) return [
			'success' => false,
			'type' => 'error',
			'msg' => 'У Вас недостаточно средств для разблокировки!'
		];

		$this->user->balance -= 500;
		$this->user->banchat = null;
		$this->user->save();

		$returnValue = ['user_id' => $this->user->id, 'ban' => 2];
		$this->redis->publish('ban.msg', json_encode($returnValue));

		$this->redis->publish('updateBalance', json_encode([
            'id'      => $this->user->id,
            'balance' => $this->user->balance
        ]));

		return [
			'success' => false,
			'type' => 'success',
			'msg' => 'Вы разблокированы в чате!'
		];
	}

	public function top() {
		return view('pages.top');
	}

	public function topAjax() {
		$top = [];
		$jackpot = \DB::table('users')
            ->select('users.id',
				'users.username',
                'users.avatar',
                'users.user_id',
            	\DB::raw('SUM(jackpot.winner_sum) as total')
            )
			->join('jackpot', function ($join) {
            	$join->on('jackpot.winner_id', '=', 'users.id')->where('jackpot.created_at', '>=', Carbon::today());
			})
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get();

		$double = \DB::table('users')
            ->select('users.id',
				'users.username',
                'users.avatar',
                'users.user_id',
            	\DB::raw('SUM(roulettebets.win_sum) as total')
            )
			->join('roulettebets', function ($join) {
            	$join->on('roulettebets.user_id', '=', 'users.id')->where('win', 1)->where('roulettebets.created_at', '>=', Carbon::today());
			})
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get();

		$flip = \DB::table('users')
            ->select('users.id',
				'users.username',
                'users.avatar',
                'users.user_id',
            	\DB::raw('SUM(flip.win_sum) as total')
            )
			->join('flip', function ($join) {
            	$join->on('flip.winner_id', '=', 'users.id')->where('flip.created_at', '>=', Carbon::today());
			})
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get();

		$battle = \DB::table('users')
            ->select('users.id',
				'users.username',
                'users.avatar',
                'users.user_id',
            	\DB::raw('SUM(battle_bets.win_sum) as total')
            )
			->join('battle_bets', function ($join) {
            	$join->on('battle_bets.user_id', '=', 'users.id')->where('win', 1)->where('battle_bets.created_at', '>=', Carbon::today());
			})
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get();

		$dice = \DB::table('users')
            ->select('users.id',
				'users.username',
                'users.avatar',
                'users.user_id',
            	\DB::raw('SUM(dice.win_sum) as total')
            )
			->join('dice', function ($join) {
            	$join->on('dice.user_id', '=', 'users.id')->where('win', 1)->where('dice.created_at', '>=', Carbon::today());
			})
            ->groupBy('users.id')
            ->orderBy('total', 'desc')
            ->limit(20)
            ->get();

		$top = [
			'jackpot' => $jackpot,
			'double' => $double,
			'dice' => $dice,
			'battle' => $battle,
			'flip' => $flip
		];

		return $top;
	}

	public function topPartners() {
		return view('pages.topPartners');
	}

	public function topPartnersAjax() {
		$users = User::select('username', 'ref_money_history')->where('ban', 0)->orderBy('ref_money_history', 'desc')->limit(20)->get();
		return $users;
	}

	public function createPromo(Request $r) {
		if(\Cache::has('action.user.' . $this->user->id)) {
			$this->redis->publish('message', json_encode([
                'user'  => $this->user->id,
                'msg'   => 'Подождите пожалуйста перед следующим действием!',
                'type'  => 'error'
            ]));
            return;
		}
        \Cache::put('action.user.' . $this->user->id, '', 0.10);

		$with = Withdraw::where('user_id', $this->user->id)->where('status', 1)->sum('value');
		if(!$this->user->is_admin && !$this->user->is_youtuber) {
			if($with < $this->settings->withpromo) return [
				'success' => false,
				'msg' => 'Вы не сделали вывод в размере '.$this->settings->withpromo.' рублей!',
				'type' => 'error'
			];
		}

		$name = strtolower(htmlspecialchars($r->get('name')));
		$reward = $r->get('reward');
		$count = $r->get('count');
		$check = Promocode::where('code', $name)->count();
		if(is_null($name) || is_null($reward) || is_null($count)) return ['success' => false, 'msg' => 'Вы не заполнили один из пунктов!', 'type' => 'error'];
		if(mb_strlen($name) < 3 || mb_strlen($name) > 10) return ['success' => false, 'msg' => 'Название может состоять от 3 до 10 символов!', 'type' => 'error'];
		if($reward < 1) return ['success' => false, 'msg' => 'Значение "Награда" не может быть меньше 1!', 'type' => 'error'];
		if($count < 1) return ['success' => false, 'msg' => 'Значение "Кол-во" не может быть меньше 1!', 'type' => 'error'];
		if($check > 0) return ['success' => false, 'msg' => 'Такой код уже существует!', 'type' => 'error'];
		if($this->user->balance < floor(($reward * $count)*1.10)) return ['success' => false, 'msg' => 'На балансе недостаточно средств для создания промокода!', 'type' => 'error'];

		$this->user->balance -= floor(($reward * $count)*1.10);
		$this->user->save();

        $this->redis->publish('updateBalance', json_encode([
            'id'    => $this->user->id,
            'balance' => $this->user->balance
        ]));

		Promocode::create([
			'user_id' => $this->user->id,
			'code' => $name,
			'limit' => 1,
			'amount' => $reward,
			'count_use' => $count
		]);

		return ['success' => true, 'msg' => 'Промокод создан!', 'type' => 'success'];
	}
}
