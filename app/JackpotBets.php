<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class JackpotBets extends Model
{
    protected $table = 'jackpot_bets';
    
    protected $fillable = ['game_id', 'room', 'user_id', 'sum', 'from', 'to', 'color', 'winner_id'];
    
    protected $hidden = ['created_at'];
	
	public function user() {
        return $this->belongsTo('App\User');
    }
}
