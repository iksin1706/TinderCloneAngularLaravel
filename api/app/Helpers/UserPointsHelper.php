<?php

namespace App\Helpers;

use App\Models\DefaultPoint;
use App\Models\User;
use Illuminate\Support\Str;

class UserPointsHelper
{
    public static function calculateAndUpdateUserPoints(User $user)
    {
        $points = 0;
        if ($user->introduction && Str::length($user->introduction)>20) $points += DefaultPoint::where('what_for', 'introduction')->first()->points;
        if ($user->looking_for && Str::length($user->looking_for)>20) $points += DefaultPoint::where('what_for', 'looking_for')->first()->points;
        if ($user->interests && Str::length($user->interests)>20) $points += DefaultPoint::where('what_for', 'interests')->first()->points;


        $points += (($user->photos->count()) * (DefaultPoint::where('what_for', 'photos')->first()->points));
        
        $points += $user->likedByUsers->count() * DefaultPoint::where('what_for', 'likes')->first()->points;


        $user->update(['points' => $points]);
    }
}
