<?php

namespace App\Http\Controllers;

use App\Helpers\UserPointsHelper;
use App\Helpers\UserResponseHelper;
use App\Http\Requests\LikesIndexRequest;
use App\Models\Dislike;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Photo;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(LikesIndexRequest $request)
    {
        $request->validated();
        $userId = Auth::user()->id;
        $predicate = $request->input('predicate');

        $users = User::orderBy('username');

        if ($predicate == 'liked') {
            $users->whereHas('likedByUsers', function ($query) use ($userId) {
                $query->where('source_user_id', $userId);
            });
        }

        if ($predicate == 'likedBy') {
            $users->whereHas('likes', function ($query) use ($userId) {
                $query->where('target_user_id', $userId);
            });
        }

        if ($predicate == 'matches') {
            $users->whereHas('likes', function ($query) use ($userId) {
                $query->where('target_user_id', $userId)
                    ->where('is_mutual', true);
            });
        }

        return response()->json(UserResponseHelper::transformUsers($users->get()));
    }

    public function store($username)
    {
        $sourceUser = Auth::user();
        $likedUser = User::where('username', $username)->first();
       
        $sourceUser = User::find($sourceUser->id);
        if (!$likedUser) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        if (strtolower($username) === strtolower($sourceUser->username)) {
            return response()->json(['error' => 'You cannot like yourself'], 400);
        }

        $userLike = Like::where('source_user_id', $sourceUser->id)
            ->where('target_user_id', $likedUser->id)
            ->first();
        
        if ($userLike) {
            return response('You already like this user', 400);
        }
        
        $userLike = new Like();
        $userLike->source_user_id = $sourceUser->id;
        $userLike->target_user_id = $likedUser->id;
        
        $mutualLike = Like::where('target_user_id',$sourceUser->id)->where('source_user_id',$likedUser->id)->first();
        if($mutualLike) {
            $mutualLike->is_mutual=true;
            $userLike->is_mutual=true;
            $mutualLike->save();
        } else $userLike->is_mutual=false;

        Like::create($userLike->toArray());
        
        if ($sourceUser->save()) {   
            UserPointsHelper::calculateAndUpdateUserPoints($likedUser);
            return response()->json(['message' => 'User liked successfully','isMatch'=>$userLike->is_mutual], 200);
        }
        
        return response('Failed to like user', 400);
    }


    public function destroy($username)
    {
        $sourceUser = Auth::user();
        $likedUser = User::where('username', $username)->firstOrFail();


        $userLike = Like::where('source_user_id', $sourceUser->id)
            ->where('target_user_id', $likedUser->id)
            ->first();

        if (!$userLike) {
            return response()->json(['error' => 'User is not liked'], 400);
        }

        $isMutual = $userLike->is_mutual;

        if ($isMutual) {
            $mutualLike = Like::where('target_user_id', $sourceUser->id)
                ->where('source_user_id', $likedUser->id)
                ->first();

            if ($mutualLike) {
                $mutualLike->is_mutual = false;
                $mutualLike->save();
            }
        }

        if ($userLike->delete()) {
            UserPointsHelper::calculateAndUpdateUserPoints($likedUser);
            return response()->json(['message' => 'User unliked successfully', 'wasMatch' => $isMutual], 200);
        }
        return response()->json(['error' => 'Failed to unlike user'], 400);
    }
}
