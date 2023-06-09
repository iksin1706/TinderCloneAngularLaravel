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
        $targetUser = User::where('username', $username)->first();
       
        $sourceUser = User::find($sourceUser->id);
        if (!$targetUser) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        if (strtolower($username) === strtolower($sourceUser->username)) {
            return response()->json(['error' => 'You cannot like yourself'], 400);
        }
   
        $userLike = Dislike::where('source_user_id', $sourceUser->id)
            ->where('target_user_id', $targetUser->id)
            ->first();
        
        if ($userLike) {
            return response('You already like this user', 400);
        }
        
        $userLike = new Dislike();
        $userLike->source_user_id = $sourceUser->id;
        $userLike->target_user_id = $targetUser->id;

        Dislike::create($userLike->toArray());
        
        if ($sourceUser->save()) {   
            return response()->json(['message' => 'User liked successfully'], 200);
        }
        
        return response('Failed to like user', 400);
    }

    public function resetDislikes()
    {
        $sourceUser = Auth::user();
        if(Dislike::where('source_user_id', $sourceUser->id)->delete())         
            return response()->json(['message' => 'Dislikes reset successfully'], 200);

        return response()->json(['You have no dislikes to reset'], 400);
    }
}
