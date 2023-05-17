<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response(Like);
    }

    public function store(Request $request)
    {
        $users = User::orderBy('UserName')->getQuery();
        $likes = Like::getQuery();

        if ($request->input('Predicate') == 'liked') {
            $likes = $likes->where('SourceUserId', $likesParams['UserId']);
            $users = $likes->select('TargetUser.*');
        }

        if ($likesParams['Predicate'] == 'likedBy') {
            $likes = $likes->where('TargetUserId', $likesParams['UserId']);
            $users = $likes->select('SourceUser.*');
        }

        $likedUsers = $users->select([
            'UserName',
            'KnownAs',
            DB::raw('(YEAR(NOW()) - YEAR(DateOfBirth)) - (DATE_FORMAT(NOW(), "%m%d") < DATE_FORMAT(DateOfBirth, "%m%d")) as Age'),
            DB::raw('(SELECT Url FROM photos WHERE user_id = users.id AND isMain = 1 LIMIT 1) as PhotoUrl'),
            'City',
            'Id'
        ])->get();
    }
}
