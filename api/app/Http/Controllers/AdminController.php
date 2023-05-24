<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
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
            return [
                'username' => $user->username,
                'id' => $user->id,
                'role' => $user->role->name
            ];
        });
        return response()->json($users);
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
