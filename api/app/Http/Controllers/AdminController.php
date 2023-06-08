<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Helpers\BanHelper;
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

    public function index(){
        
        if (Auth::payload()->get('role') !== 'admin') return response('Only admin has access',403);

        $users = User::all()->map(function ($user) {
            $isBanned = BanHelper::isBanned($user);
    
            return [
                'username' => $user->username,
                'email' => $user->email,
                'id' => $user->id,
                'role' => $user->role->name,
                'isBlocked' => $isBanned,
            ];
        });
        return response()->json($users);
    }

    public function update(UpdateRoleRequest $request, $username)
    {
        $role = $request->input('role');
        $user = User::where('username', $username)->firstOrFail();
        $user->role_id = Role::where('name', $role)->id;
        $user->save();
    
        return response()->json($role);
    }
}
