<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'users';

    protected $fillable = [
        'username', 'avatar', 'user_id', 'balance', 'ip', 'is_admin', 'is_moder', 'is_youtuber', 'superadmin', 'banchat', 'fake', 'ban', 'affiliate_id', 'referred_by', 'ref_money', 'ref_money_history'
    ];
	
	static function getUser($id) {
		$user = self::select('username', 'avatar', 'id')->where('id', $id)->first();
		return $user;
	}
}
