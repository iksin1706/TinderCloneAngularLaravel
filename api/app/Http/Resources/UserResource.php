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
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'dateOfBirth' => $this->date_of_birth,
            'knownAs' => $this->known_as,
            'gender' => $this->gender,
            'city' => $this->city,
            'country' => $this->country,
            'age' => $this->age(),
            'introduction' => $this->introduction,
            'lookingFor' => $this->looking_for,
            'interests' => $this->interests,
            'password' => Hash::make($request->password)
        ];
    }
}
