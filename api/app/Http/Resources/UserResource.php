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
            'introduction' => $this->introduction,
            'knownAs' => $this->known_as,
            'gender' => $this->gender,
            'city' => $this->city,
            'lookingFor' => $this->looking_for,
            'interests' => $this->interests,
            'age' => now()->diffInYears($this->date_of_birth),
            'country' => $this->country,
            'photos' =>  PhotoResource::collection($this->photos->sortByDesc('is_main')),
            'photoUrl' => $this->photos->first(function ($photo) {
                return $photo->is_main;
            })?->url,
            'likeStatus' => $this->like_status
            
        ];
    }
    public function addLikeStatus($request)
    {
        return [
            'additional_field' => 'Additional value',
        ];
    }
}
