<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class TokenService
{
    private $key;

    public function __construct()
    {
        $this->key = Config::get('app.token_key');
    }

    public function createToken($user)
    {
        $claims = [
            'sub' => $user->id,
            'name' => $user->name,
            // Add any additional claims as needed
        ];

        $token = JWT::encode($claims, $this->key, 'HS256');

        return $token;
    }

    public function validateToken($token)
    {
        try {
            JWT::decode($token, $this->key, ['HS256']);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function revokeToken($token)
    {
        // Token revocation logic here (if applicable)
    }
}
