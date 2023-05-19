<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'is_main' => $this->is_main,
            'public_id' => $this->public_id,
            'user_id' => $this->user_id,
        ];
    }
}