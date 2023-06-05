<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Blockade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function usersWithRoles(){
        if (!Auth::payload()->get('role') == 'admin') return response('Only admin has access',403);
        $users = User::all();
        $users = collect($users)->map(function ($user) {
            $isBanned = $this->isBanned($user);
            return [
                'username' => $user->username,
                'id' => $user->id,
                'role' => $user->role->name,
                'isBlocked' => $isBanned
            ];
        });
        return response()->json($users);
    }

    public function isBanned(User $user){
        $ban = Blockade::where('user_id',$user->id)->orderBy('until','desc')->first();
        if(!$ban) return false;
        $banned_days = Carbon::now()->diffInDays($ban->until, false)+1;
        if ($banned_days>0) return true;
    }

    public function editRole(Request $request,$username){
        if (!Auth::payload()->get('role') == 'admin') return response('Only admin has access',403);
        $role=$request->input('role');
        $user = User::where('username',$username)->first();
        $user->role_id=Role::where('name',$role)->first()->id;
        $user->save();
        return response()->json($role);
    }



    
}
