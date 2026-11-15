<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image ? image_url(config('helper.uploadPath') . $this->image) : null,
            'caption' => $this->caption,
            'photographer' => $this->photographer,
            'created_at' => $this->created_at,
        ];
    }
}
