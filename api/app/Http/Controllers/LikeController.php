<?php

namespace App\Http\Controllers;

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

    public function index(Request $request)
    {

        $userId = Auth::user()->id;
        $predicate = $request->input('predicate');
        
        $users = User::orderBy('username');
        
        if ($predicate == 'liked') {
            $users->whereIn('id', function ($query) use ($userId) {
                $query->select('target_user_id')
                    ->from('likes')
                    ->where('source_user_id', $userId);
            });
        }
        
        if ($predicate == 'likedBy') {
            $users->whereIn('id', function ($query) use ($userId) {
                $query->select('source_user_id')
                    ->from('likes')
                    ->where('target_user_id', $userId);
            });
        }
        
        $likedUsers = $users->get()->map(function ($user) {
            return [
                'username' => $user->username,
                'knownAs' => $user->known_as,
                'age' => now()->diffInYears($user->date_of_birth),
                'photoUrl' => $user->Photos->first(function ($photo) {
                    return $photo->is_main;
                })->url,
                'city' => $user->city,
                'id' => $user->id
            ];
        });

        return response()->json($likedUsers);
        
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
            return response()->json(['error' => 'You already like this user'], 400);
        }
        
        $userLike = new Like();
        $userLike->source_user_id = $sourceUser->id;
        $userLike->target_user_id = $likedUser->id;
        Like::create($userLike->toArray());
        
        if ($sourceUser->save()) {
            return response()->json(['message' => 'User liked successfully'], 200);
        }
        
        return response()->json(['error' => 'Failed to like user'], 400);
    }
}
