<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\TokenService;
use Illuminate\Auth\Events\Registered;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {

        $request = $request->validated();

        $user = User::create([
            'username' => $request->name,
            'email' => $request->email,
            'knownAs' => $request->knowAs,
            'gender' => $request->gender,
            'date_of_birth' => $request->knowAs,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response()->json([
            'username' => $user->username,
            'token' => $token,
            'knownAs' => $user->knowAs,
            'gender' => $user->gender,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('username', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        $user = User::find($user->user_id);

        $mainPhoto = $user->photos()->where('isMain', true)->first();

        return response()->json([
            'username' => $user->username,
            'token' => $token,
            'type' => 'bearer',
            'knownAs' => $user->knowAs,
            'gender' => $user->gender,
            'photoUrl' => $mainPhoto
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        $user = User::find(Auth::user());
        $mainPhoto = $user->photos()->where('isMain', true)->first();
        return response()->json([
            'username' => $user->username,
            'token' => Auth::refresh(),
            'type' => 'bearer',
            'knownAs' => $user->knowAs,
            'gender' => $user->gender,
            'photoUrl' => $mainPhoto,
        ]);
    }
}
