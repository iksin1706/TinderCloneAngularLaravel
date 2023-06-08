<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Blockade;
use App\Services\TokenService;
use Illuminate\Auth\Events\Registered;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Photo;


class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create(new UserResource($request));

        $token = Auth::login($user);

        return response()->json([
            'username' => $user->username,
            'token' => $token,
            'knownAs' => $user->know_as,
            'gender' => $user->gender
        ], 201);
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

        $mainPhoto = $user->photos->where('is_main', true)->first();

        $photoUrl = $mainPhoto ? $mainPhoto->url : null;

        return response()->json([
            'username' => $user->username,
            'token' => $token,
            'type' => 'bearer',
            'knownAs' => $user->known_as,
            'gender' => $user->gender,
            'photoUrl' => $photoUrl
        ], 201);
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
        $mainPhoto = $user->photos()->where('is_main', true)->first();
        return response()->json([
            'username' => $user->username,
            'token' => Auth::refresh(),
            'type' => 'bearer',
            'knownAs' => $user->know_as,
            'gender' => $user->gender,
            'photoUrl' => $mainPhoto,
        ]);
    }
}
