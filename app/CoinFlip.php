<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CoinFlip extends Model{

    protected $table = 'flip';

    protected $fillable = ['winner_id', 'winner_ticket', 'user1', 'user2', 'from1', 'to1', 'from2', 'to2', 'price', 'win_sum', 'hash'];

    protected $hidden = ['created_at', 'updated_at'];

}