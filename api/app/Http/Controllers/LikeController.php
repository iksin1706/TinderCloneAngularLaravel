<?php

namespace App\Http\Controllers;

use App\Helpers\UserPointsHelper;
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

        $likedUsers = $users->get()->map(function ($user) {
            return [
                'userName' => $user->username,
                'knownAs' => $user->known_as,
                'age' => now()->diffInYears($user->date_of_birth),
                'photoUrl' => $user->photos->first(function ($photo) {
                    return $photo->is_main;
                })?->url,
                'city' => $user->city,
                'id' => $user->id
            ];
        });

        return response()->json($likedUsers);
    }

    public function store($username)
    {
        $sourceUser = Auth::user();
        $likedUser = User::findOrFail($username);

        if (strtolower($username) === strtolower($sourceUser->username)) {
            return response()->json(['error' => 'You cannot like yourself'], 400);
        }

        $userLike = Like::firstOrCreate([
            'source_user_id' => $sourceUser->id,
            'target_user_id' => $likedUser->id
        ]);

        $mutualLike = Like::where('target_user_id', $sourceUser->id)
            ->where('source_user_id', $likedUser->id)
            ->first();

        if ($mutualLike) {
            $mutualLike->is_mutual = true;
            $mutualLike->save();
            $userLike->is_mutual = true;
        } else {
            $userLike->is_mutual = false;
        }

        if ($userLike->save()) {
            UserPointsHelper::CalculateAndUpdateUserPoints($likedUser);
            return response()->json(['message' => 'User liked successfully', 'isMatch' => $userLike->is_mutual], 200);
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
            UserPointsHelper::CalculateAndUpdateUserPoints($likedUser);
            return response()->json(['message' => 'User unliked successfully', 'wasMatch' => $isMutual], 200);
        }
        return response()->json(['error' => 'Failed to unlike user'], 400);
    }
}
