<?php

namespace App\Helpers;

use App\Models\DefaultPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserResponseHelper 
{
    public static function transformUsers(Collection $users)
    {
        return $users->map(function ($user) {
            $userData = [
                'userName' => $user->username,
                'knownAs' => $user->known_as,
                'age' => $user->age(),
                'photoUrl' => optional($user->Photos->first(function ($photo) {
                    return $photo->is_main;
                }))->url,
                'city' => $user->city,
                'country' => $user->country,
                'id' => $user->id,
                'created' => $user->created_at,
                'photos' => $user->photos->sortByDesc('is_main')
            ];   
            return $userData;
        });
    }

    public static function transformDetailedUser(User $user){
        $user['photoUrl'] = $user->Photos->first(function ($photo) {
            return $photo->is_main;
        })?->url;
        $user['photos'] = $user->photos->sortByDesc('is_main');
        $user['lookingFor'] = $user['looking_for'];
        $user['age'] = $user->age();
        $user['knownAs'] = $user['known_as'];
        $user['userName'] = $user['username'];
        return $user;
    }
}
?>