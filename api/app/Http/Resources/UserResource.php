<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'known_as' => $this->known_as,
            'created_at' => $this->created_at,
            'last_active' => $this->last_active,
            'gender' => $this->gender,
            'introduction' => $this->introduction,
            'looking_for' => $this->looking_for,
            'interests' => $this->interests,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }
}
