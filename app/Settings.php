<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

	protected $fillable = ['domain', 'sitename', 'title', 'desc', 'keys', 'vk_key', 'vk_secret', 'vk_url', 'fake', 'mrh_ID', 'mrh_secret1', 'mrh_secret2', 'fk_api', 'fk_wallet', 'chat_dep', 'jackpot_commission', 'pvp_commission', 'pvp_min', 'pvp_max', 'random_api', 'roulette_timer', 'double_min_bet', 'double_max_bet', 'double_fake_min', 'double_fake_max', 'dice_fake_min', 'double_commission', 'battle_timer', 'battle_min_bet', 'battle_max_bet', 'battle_commission', 'dep_perc', 'dep_code', 'crash_min_bet', 'crash_max_bet', 'crash_timer', 'sendwith', 'min_sendwith', 'max_sendwith', 'ref_invite', 'withpromo'];

    protected $hidden = ['created_at', 'updated_at'];

}
