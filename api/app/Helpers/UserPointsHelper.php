<?php

namespace App\Helpers;

use App\Models\DefaultPoint;
use App\Models\User;

class UserPointsHelper 
{
    public static function CalculateAndUpdateUserPoints(User $user){
        $points=0;
        if($user->introduction)$points+=DefaultPoint::where('what_for','introduction')->first()->points;
        if($user->looking_for)$points+=DefaultPoint::where('what_for','looking_for')->first()->points;
        if($user->interests)$points+=DefaultPoint::where('what_for','interests')->first()->points;

        $points += $user->photos->count() * DefaultPoint::where('what_for', 'next_photo')->first()->points;
        
        $points += $user->likedByUsers->count() * DefaultPoint::where('what_for', 'likes')->first()->points;
        
        
        $user->update(['points'=>$points]);
    }
}
?>