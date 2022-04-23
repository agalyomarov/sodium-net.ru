<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use App\Withdraw;
use App\SuccessPay;
use App\Promocode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redis;

class ChatController extends Controller
{
    const CHAT_CHANNEL = 'chat.message';
    const NEW_MSG_CHANNEL = 'new.msg';
    const CLEAR = 'chat.clear';
    const DELETE_MSG_CHANNEL = 'del.msg';
	const BAN_CHANNEL = 'ban.msg';

    public function __construct()
    {
        parent::__construct();
        $this->redis = Redis::connection();
    }
    
    public static function chat()
    {
        $redis = Redis::connection();

        $value = $redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $returnValue = NULL;
        $value = array_reverse($value);

        foreach ($value as $key => $newchat[$i]) {
            if ($i > 20) {
                break;
            }
            $value2[$i] = json_decode($newchat[$i], true);

            $value2[$i]['username'] = htmlspecialchars($value2[$i]['username']);

            $returnValue[$i] = [
                'user_id' => $value2[$i]['user_id'],
                'avatar' => $value2[$i]['avatar'],
                'time' => $value2[$i]['time'],
                'time2' => $value2[$i]['time2'],
                'ban' => $value2[$i]['ban'],
                'messages' => $value2[$i]['messages'],
                'username' => $value2[$i]['username'],
                'youtuber' => $value2[$i]['youtuber'],
                'moder' => $value2[$i]['moder'],
                'admin' => $value2[$i]['admin']];

            $i++;

        }

       if(!is_null($returnValue)) return array_reverse($returnValue);
    }


    public function __destruct()
    {
        $this->redis->disconnect();
    }
	
	public function unban() {
		$users = User::where('banchat', '!=', NULL)->orderBy('banchat', 'asc')->get();
		if($users == '[]') return response()->json(['msg' => 'Пользователи не найдены!', 'status' => 'error']);
		foreach($users as $usr) {
			$nowtime = time();
			if($usr->banchat <= $nowtime) {
				User::where('user_id', $usr->user_id)->update(['banchat' => null]);
				$returnBan = ['user_id' => $usr->id, 'ban' => 2];
				$this->redis->publish(self::BAN_CHANNEL, json_encode($returnBan));
			}
		}
		return response()->json(['msg' => 'Пользователи разбарены', 'status' => 'success']);
	}

    public function add_message(Request $request)
    {
        $val = \Validator::make($request->all(), [
            'messages' => 'required|string|max:255'
        ],[
            'required' => 'Сообщение не может быть пустым!',
            'string' => 'Сообщение должно быть строкой!',
            'max' => 'Максимальный размер сообщения 255 символов.',
        ]);
        $error = $val->errors();

        if($val->fails()){
            return response()->json(['message' => $error->first('messages'), 'status' => 'error']);
        }
        
        if($this->user->is_admin) $messages = $request->get('messages');
        else $messages = htmlspecialchars($request->get('messages'));
        if(\Cache::has('addmsg.user.' . $this->user->id)) return response()->json(['message' => 'Вы слишком часто отправляете сообщения!', 'status' => 'error']);
        \Cache::put('addmsg.user.' . $this->user->id, '', 0.05);
        $dep = SuccessPay::where('user', $this->user->user_id)->where('status', 1)->sum('price')/10;
        if(!$this->user->is_admin && !$this->user->is_moder && !$this->user->is_youtuber ) {
            if($this->settings->chat_dep != 0) if($dep < $this->settings->chat_dep) {
                return response()->json(['message' => 'Для того чтобы писать в чат, вам нужно пополнить счет на '.$this->settings->chat_dep.' руб!', 'status' => 'error']);
            }
        }
        
        $nowtime = time();
        $banchat = $this->user->banchat;
        if($banchat >= $nowtime) {
            return response()->json(['message' => 'Вы заблокированы до: '.date("d.m.Y H:i:s", $banchat), 'status' => 'error']);
        }
        
        $time = date('H:i', time());
        $moder = $this->user->is_moder;
        $youtuber = $this->user->is_youtuber;
        $admin = 0;
        $ban = $this->user->banchat;
        $user_id = $this->user->user_id;
        $username = htmlspecialchars($this->user->username);
        $avatar = $this->user->avatar;
        if($this->user->is_admin) {
            if(strpos($messages, '/admin') !== false) {
                $admin = 1;
                $messages = str_replace('/admin ', '', $messages);
            }
        }
        if ($admin) {
            $avatar = '/assets/images/no_avatar.jpg';
            $user_id = '';
        }

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
		
		if(substr_count(strtolower($messages), '$bal')) {
			$returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<div class="chat-transaction flex-row align-center"><div class="chat-transaction__icon"><span class="icon-balance"></span></div><div class="flex-column flex-column_align-start"><div class="chat-transaction__status">Мой баланс</div><span class="chat-message-transaction-link">'.$this->user->balance.' <i class="fas fa-coins"></i></span></div></div>', 'username' => $username, 'ban' => 0, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
			$this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
			$this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
			return response()->json(['message' => 'Вы показали свой баланс в чате!', 'status' => 'success']);
		}
		
		if(substr_count(strtolower($messages), '$promo ')) {
			$rep = str_replace('$promo ', '', $messages);
			$mes = explode(' ', $rep);
			$code = Promocode::where('code', $mes[0])->where('count_use', '>', 0)->first();
			if(!$code) return response()->json(['message' => 'Не удалось найти промокод!', 'status' => 'error']);
			if($code->user_id != $this->user->id) return response()->json(['message' => 'Это не Ваш промокод!', 'status' => 'error']);
			
			$returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<div class="chat-transaction flex-row align-center"><div class="chat-transaction__icon"><span class="icon-promo"></span></div><div class="flex-column flex-column_align-start"><div class="chat-transaction__status">Мой промокод:</div><span class="chat-message-transaction-link"><b>'.$code->code.'</b> на сумму '.$code->amount.' <i class="fas fa-coins"></i>, активаций - '.$code->count_use.'</span></div></div>', 'username' => $username, 'ban' => 0, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
			$this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
			$this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
			return response()->json(['message' => 'Вы поделились своим промокодом в чате!', 'status' => 'success']);
		}
		
        if($this->user->is_admin || $this->user->is_moder) {
            if (substr_count(strtolower($messages), '/clear')) {
                $this->redis->del(self::CHAT_CHANNEL);
                $this->redis->publish(self::CLEAR, 1);
                return response()->json(['message' => 'Вы очистили чат!', 'status' => 'success']);
            }
            if(substr_count(strtolower($messages), '/ban ')) {
                $admin = $this->user->is_admin;
                if ($admin) {
                    $avatar = '/assets/images/no_avatar.jpg';
                    $user_id = '';
                }
                $rep = str_replace("/ban ", "", $messages);
                $mes = explode(" ", $rep);
                $usr = User::where('user_id', $mes[0])->first();
                if(!$usr) return response()->json(['message' => 'Не удалось найти пользователя с таким ID', 'status' => 'error']);
                if($usr->user_id == $this->user->user_id) return response()->json(['message' => 'Вы не можете заблокировать себя!', 'status' => 'error']);
                if (!empty($mes[1])) {
                    User::where('user_id', $usr->user_id)->update(['banchat' => Carbon::now()->addMinutes($mes[1])->getTimestamp()]);
                } else {
                    return response()->json(['message' => 'Вы не ввели ID игрока или время бана', 'status' => 'error']);
                }
                $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #65a5e1;">Пользователь "'.$usr->username.'" заблокирован в чате на '.$mes[1].' мин.</span>', 'username' => $username, 'ban' => 0, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
				$returnBan = ['user_id' => $usr->id, 'ban' => 1];
                $this->redis->publish(self::BAN_CHANNEL, json_encode($returnBan));
                $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
                $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
                return response()->json(['message' => 'Вы успешно забанили игрока', 'status' => 'success']);
            }
            if(substr_count(strtolower($messages), '/unban')) {
                $admin = $this->user->is_admin;
                if ($admin) {
                    $avatar = '/assets/images/no_avatar.jpg';
                    $user_id = '';
                }
                $userid = str_replace("/unban ", "", $messages);
                $usr = User::where('user_id', $userid)->first();
                if(!$usr) return response()->json(['message' => 'Не удалось найти пользователя с таким ID!', 'status' => 'error']);
                if($usr->user_id == $this->user->user_id) return response()->json(['message' => 'Вы не можете разблокировать себя!', 'status' => 'error']);
                if (!empty($userid)) {
                    User::where('user_id', $usr->user_id)->update(['banchat' => null]);
                } else {
                    return response()->json(['message' => 'Вы не ввели ID игрока', 'status' => 'error']);
                }
                $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #65a5e1;">Пользователь "'.$usr->username.'" разблокирован в чате</span>', 'username' => $username, 'ban' => 0, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
				$returnBan = ['user_id' => $usr->id, 'ban' => 2];
                $this->redis->publish(self::BAN_CHANNEL, json_encode($returnBan));
                $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
                $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
                return response()->json(['message' => 'Вы успешно разбанили игрока', 'status' => 'success']);
            }
            if(substr_count(strtolower($messages), '/promo')) {
                $admin = $this->user->is_admin;
                if($admin) {
                    $avatar = '/assets/images/no_avatar.jpg';
                    $user_id = '';
                }
                $promoid = str_replace("/promo ", "", $messages);
                $promo = Promocode::where('id', $promoid)->first();
                if(!$promo) return response()->json(['message' => 'Промокод с таким ID не найден!', 'status' => 'error']);
                if(empty($promoid)) return response()->json(['message' => 'Вы не ввели ID промокода', 'status' => 'error']);
                $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => '<span style="color: #65a5e1;">Успей активировать промокод: <span style="color: #f44336;">'.$promo->code.'</span> и получить '.$promo->amount.' <i class="fas fa-coins"></i>, количество ограничено!</span>', 'username' => $username, 'ban' => 0, 'admin' => $admin, 'moder' => $moder, 'youtuber' => $youtuber];
                $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
                $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
                return response()->json(['message' => 'Вы успешно создали раздачу!', 'status' => 'success']);
            }
        } else {
            if(preg_match("/href|url|http|https|www|.ru|.com|.net|.info|csgo|winner|ru|xyz|com|net|info|.org/i", $messages)) {
                return response()->json(['message' => 'Ссылки запрещены!', 'status' => 'error']);
            }
            if(substr_count(str_replace(' ', '', $messages), $this->user->affiliate_id)) {
                return response()->json(['message' => 'Отправка промокодов запрещена!', 'status' => 'error']);
            }
        }
        $returnValue = ['user_id' => $user_id, 'avatar' => $avatar, 'time2' => Carbon::now()->getTimestamp(), 'time' => $time, 'messages' => $messages, 'username' => $username, 'ban' => $ban, 'admin' => $admin, 'moder' => $this->user->is_moder, 'youtuber' => $this->user->is_youtuber];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
        return response()->json(['message' => 'Ваше сообщение успешно отправлено!', 'status' => 'success']);
    }
    
    public function gift(){

        if ( request()->get('secret') !== 'jopkaEnigmana' ) return false;
        
        $time = date('H:i', time());
        $username = "Розыгрыш";
        $avatar = '/assets/images/no_avatar.jpg';
        $user_id = '';

        $winner = User::inRandomOrder();
        if ( !$winner->exists() ){
            $messages = "На этот раз мы не смогли найти победителя :(";
        }else{
        $winner = $winner->first();

        if ( \App\Payments::where('user_id', $winner->id)->where('status', 3)->sum('sum') < 0 ){
            $messages = "На этот раз мы не смогли найти победителя :(";
        }else{

        $winAmount = rand(1, 100);
        $winner->balance = round($winner->balance+$winAmount);
        $winner->save();
        $this->redis->publish('updateBalance', json_encode(['id'=>$winner->id,'balance'=>$winner->balance]));
        $messages = "В розыгрыше победил ".$winner->username." и он получил ".$winAmount." монет на баланс!";
        
        }
    }
	    $returnValue = [
            'user_id' => $user_id,
            'avatar' => $avatar,
            'time2' => Carbon::now()->getTimestamp(), 
            'time' => $time,
            'messages' => htmlspecialchars($messages),
            'username' => $username, 
            'ban' => 0, 
            'admin' => 0, 
            'moder' => 0, 
            'youtuber' => 0];
        $this->redis->rpush(self::CHAT_CHANNEL, json_encode($returnValue));
        $this->redis->publish(self::NEW_MSG_CHANNEL, json_encode($returnValue));
        return response()->json(['message' => true, 'status' => 'success']);
    }

    public function delete_message(Request $request) {
        $value = $this->redis->lrange(self::CHAT_CHANNEL, 0, -1);
        $i = 0;
        $json = json_encode($value);
        $json = json_decode($json);
        foreach ($json as $newchat) {
            $val = json_decode($newchat);

            if ($val->time2 == $request->get('messages')) {
                $this->redis->lrem(self::CHAT_CHANNEL, 1, json_encode($val));
                $this->redis->publish(self::DELETE_MSG_CHANNEL, json_encode($val));
            }
            $i++;
        }
        return response()->json(['message' => 'Сообщение удалено!', 'status' => 'success']);
    }
}
