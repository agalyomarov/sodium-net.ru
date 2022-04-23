<?php

namespace App\Http\Controllers;

use Auth;
use Redis;
use App\User;
use App\Jackpot;
use App\Roulette;
use App\CoinFlip;
use App\Crash;
use App\CrashBets;
use App\Battle;
use App\Settings;
use App\Promocode;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            view()->share('u', $this->user);
			if($this->user) view()->share('promos', Promocode::where('user_id', $this->user->id)->where('count_use', '>', 0)->get());
			if($this->user) view()->share('ref', $this->getRef($this->user->affiliate_id));
            return $next($request);
        });
        Carbon::setLocale('ru');
		$this->settings = Settings::first();
        $this->redis = Redis::connection();
		view()->share('settings', $this->settings);
        view()->share('messages', $this->chatMessage());
		view()->share('maxPriceToday', $this->maxPriceToday());
        view()->share('maxPrice', $this->maxPrice());
        view()->share('gamesToday', $this->gamesToday());
        view()->share('gamesbank', $this->updateBank());
    }
	
	public function chatMessage() {
        $messages = ChatController::chat();
        return $messages;
    }
	
	public function getRef($affiliate_id) {
        $ref = User::where('referred_by', $affiliate_id)->count();
		if($ref < 10) {
			$lvl = 1;
			$perc = 0.5;
		} elseif($ref >= 10 && $ref < 100) {
			$lvl = 2;
			$perc = 0.7;
		} elseif($ref >= 100 && $ref < 500) {
			$lvl = 3;
			$perc = 1;
		} else {
			$lvl = 4;
			$perc = 1.5;
		}
		$data = [
			'count' => $ref,
			'lvl' => $lvl,
			'perc' => $perc
		];
        return $data;
    }
	
	public static function maxPriceToday() {
		$price = ($price = Jackpot::where('updated_at', '>=', Carbon::today())->max('price')) ? $price : 0;
        return $price;
    }
    
    public static function maxPrice() {
        $games = Jackpot::where('status', 3)->max('price');
		return $games;
    }
	
    public static function gamesToday() {
        $games = Jackpot::where('status', 3)->where('updated_at', '>=', Carbon::today())->count();
		return $games;
    }
	
	public static function updateBank() {
		$jackpot_r1 = Jackpot::select('price')->where('room', 'small')->orderBy('id', 'desc')->first();
		$jackpot_r2 = Jackpot::select('price')->where('room', 'classic')->orderBy('id', 'desc')->first();
		$jackpot_r3 = Jackpot::select('price')->where('room', 'major')->orderBy('id', 'desc')->first();
		if(!is_null($jackpot_r1) && !is_null($jackpot_r2) && !is_null($jackpot_r3)) $jackpot = $jackpot_r1->price + $jackpot_r2->price + $jackpot_r3->price; else $jackpot = 0;
		$double = Roulette::select('price')->orderBy('id', 'desc')->first();
		if(!is_null($double)) $double = $double->price; else $double = 0;
		$pvp = CoinFlip::where('status', 0)->sum('price');
		if(!is_null($pvp)) $pvp = $pvp; else $pvp = 0;
		$battle = Battle::select('price')->orderBy('id', 'desc')->first();
		if(!is_null($battle)) $battle = $battle->price; else $battle = 0;
		$crash_id = Crash::select('id')->orderBy('id', 'desc')->first();
		if(!is_null($crash_id)) $crash = CrashBets::where('round_id', $crash_id->id)->sum('price'); else $crash = 0;
		
		$bank = [
			'jackpot' => $jackpot,
			'crash' => $crash,
			'double' => $double,
			'pvp' => $pvp,
			'battle' => $battle
		];
        return $bank;
    }
}
