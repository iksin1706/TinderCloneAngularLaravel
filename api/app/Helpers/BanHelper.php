<?php

namespace App\Helpers;

use App\Models\Blockade;
use App\Models\DefaultPoint;
use App\Models\User;
use Carbon\Carbon;

class BanHelper 
{
    public static function getBannedDays(User $user){
        $ban = Blockade::where('user_id',$user->id)->orderBy('until','desc')->first();
        if(!$ban) return 0;
        $banned_days = Carbon::now()->diffInDays($ban->until, false)+1;
        if ($banned_days>0) return $banned_days;
    }
}
?>