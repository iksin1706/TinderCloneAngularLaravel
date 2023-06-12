<?php

namespace App\Helpers;

use App\Models\DefaultPoint;
use App\Models\Dislike;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserLikeStatusHelper 
{
    public static function getLikeStatuses($users,$userId) {
        $dislikedUserIds = Dislike::where('source_user_id', $userId)->pluck('target_user_id');
        $likedUserIds = Like::where('source_user_id', $userId)->pluck('target_user_id');

        $users = $users->map(function ($user) use ($likedUserIds, $dislikedUserIds) {
            if ($dislikedUserIds->contains($user['id'])) {
                $user['like_status'] = 'disliked';
            } elseif ($likedUserIds->contains($user['id'])) {
                $user['like_status'] = 'liked';
            } else {
                $user['like_status'] = 'none';
            }
            return $user;
        });
        return $users;
    }
}
?>