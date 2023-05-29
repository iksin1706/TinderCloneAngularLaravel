<?php

use App\Models\DefaultPoint;
use App\Models\User;

class UserPointsHelper 
{
    public static function CalculateAndUpdateUserPoints(User $user){
        $points=0;
        if($user->description)$points+=DefaultPoint::where('what_for','introduction')->points;
        if($user->looking_for)$points+=DefaultPoint::where('what_for','looking_for')->points;
        if($user->interests)$points+=DefaultPoint::where('what_for','interests')->points;
        if($user->photos){
            $points+=DefaultPoint::where('what_for','main_photo')->points;
        }
        if($user->photos->length>1){
            $points+=DefaultPoint::where('what_for','photos')->points;
        }
        if($user->likedByUsers){
            $points+=DefaultPoint::where('what_for','likes')->points;
        }
        
        if($user->blockages){
            $points+=DefaultPoint::where('what_for','blockages')->points;
        }
        $user->update(['points',$points]);
    }
}
?>