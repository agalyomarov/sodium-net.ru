<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $table = 'profit';
	
	protected $fillable = ['game', 'sum'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
}