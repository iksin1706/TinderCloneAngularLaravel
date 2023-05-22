<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'age' => $this->age(),
            
            'known_as' => $this->known_as,
            'gender' => $this->gender,
            'city' => $this->city,
            'country' => $this->country,
        ];
    }
}
