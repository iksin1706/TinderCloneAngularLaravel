<?php

namespace App\Http\Controllers;

use App\Helpers\BanHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
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

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'known_as' => $request->knownAs,
            'gender' => $request->gender,
            'date_of_birth' => $request->dateOfBirth,
            'city' => $request->city,
            'country' => $request->country,
            'password' => Hash::make($request->password),
            'role_id' => 1
        ]);

        $token = Auth::login($user);

        return response()->json([
            'username' => $user->username,
            'token' => $token,
            'knownAs' => $user->know_as,
            'gender' => $user->gender,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->only('username', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }


        $user = User::find(Auth::user()->id);
        $banned_days = BanHelper::isBanned($user);
        if ($banned_days>0)
            return response(
                'Jestes zablokowany na ' . $banned_days . ' dni. Skontaktuj się z administratorem.',
                Response::HTTP_FORBIDDEN
            );

        $mainPhoto = $user->photos()->where('is_main', true)->first();

        if ($mainPhoto) {
            $photoUrl = $mainPhoto->url;
        } else {
            $photoUrl = null;
        }

        return response()->json([
            'username' => $user->username,
            'token' => $token,
            'type' => 'bearer',
            'knownAs' => $user->known_as,
            'gender' => $user->gender,
            'photoUrl' => $photoUrl,
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
            'roleId' => $user->role_id
        ]);
    }
}
