<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserResource extends JsonResource
{
    public static $wrap = null;
    public function toArray($request)
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'date_of_birth' => $this->dateOfBirth,
            'known_as' => $this->knownAs,
            'gender' => $this->gender,
            'city' => $this->city,
            'country' => $this->country,
            'password' => Hash::make($request->password)
        ];
    }
}
