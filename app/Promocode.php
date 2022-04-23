<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model {

    protected $table = 'promocode';

    protected $fillable = ['user_id', 'code', 'limit', 'amount', 'count_use'];

    protected $hidden = ['created_at', 'updated_at'];
	
	static function getUserId($code) {
		$promo = self::select('user_id')->where('code', $code)->first();
		return $promo;
	}

}