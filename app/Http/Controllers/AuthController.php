<?php namespace App\Http\Controllers;

use Socialite;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function login($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
		if (Input::get('error') == 'access_denied') {
            return redirect('/');
        }
        $user = json_decode(json_encode(Socialite::driver($provider)->user()));
        if(isset($user->returnUrl)) return redirect('/');
        $user = $user->user;
        $user = $this->createOrGetUser($user, $provider);
        Auth::login($user, true);
        return redirect()->intended('/');
    }

    public function createOrGetUser($user, $provider)
    {
        if ($provider == 'vkontakte') {
            $u = User::where('user_id', $user->id)->first();
            if ($u) {
                $username = $user->first_name.' '.$user->last_name;
                User::where('user_id', $user->id)->update([
                    'username' => $username,
                    'avatar' => $user->photo_max,
                    'affiliate_id' => strtolower($u->affiliate_id),
                    'referred_by' => $u->referred_by ? strtolower($u->referred_by) : null,
                    'ip' => request()->ip()
                ]);
                $user = $u;
            } else {
                $username = $user->first_name.' '.$user->last_name;
                $user = User::create([
                    'user_id' => $user->id,
                    'username' => $username,
                    'avatar' => $user->photo_max,
                    'affiliate_id' => strtolower(str_random(10)),
                    'ip' => request()->ip()
                ]);
            }
        }
        return $user;
    }

    public function logout()
    {
		Cache::flush();
        Auth::logout();
		Session::flush();
        return redirect()->intended('/');
    }
}