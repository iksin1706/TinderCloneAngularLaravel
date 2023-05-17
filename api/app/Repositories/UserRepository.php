<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getUserByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    // Add other methods for user data access as needed
}
