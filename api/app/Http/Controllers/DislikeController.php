<?php

namespace App\Http\Controllers;

use App\Helpers\UserPointsHelper;
use App\Models\Dislike;
use App\Models\Like;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DislikeController extends Controller
{
    public function store($username)
    {
        $sourceUser = Auth::user();
        $likedUser = User::findOrFail($username);

        if (strtolower($username) === strtolower($sourceUser->username)) {
            return response()->json(['error' => 'You cannot dislike yourself'], 400);
        }

        try {
            $userDislike = Dislike::firstOrCreate([
                'source_user_id' => $sourceUser->id,
                'target_user_id' => $likedUser->id
            ]);

            if ($userDislike) {
                return response('You already dislike this user', 400);
            }

            if ($userDislike->save()) {
                UserPointsHelper::CalculateAndUpdateUserPoints($likedUser);
                return response()->json(['message' => 'User disliked successfully'], 200);
            }
        } catch (\Exception $e) {
            return response('Failed to dislike user', 400);
        }

        return response('Failed to dislike user', 400);
    }

    public function resetDislikes()
    {
        $sourceUser = Auth::user();
        if(Like::where('source_user_id', $sourceUser->id)->delete())         
            return response()->json(['message' => 'Dislikes reset successfully'], 200);

        return response()->json(['error' => 'Failed to reset dislikes'], 400);
    }
}
