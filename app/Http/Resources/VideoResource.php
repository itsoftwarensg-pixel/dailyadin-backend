<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\BengaliDateHelper;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'embed_code' => $this->embed_code,
            'description' => $this->description,
            'thumbnail' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
            'created_at' => $this->created_at,
            'created_at_bangla' => BengaliDateHelper::formatEnglishToBnDate($this->created_at),
        ];

        return $data;
    }
}
