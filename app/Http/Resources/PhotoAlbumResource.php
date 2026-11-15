<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\BengaliDateHelper;

class PhotoAlbumResource extends JsonResource
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
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'description' => $this->description,
            'description_bn' => $this->description_bn,
            'photographer_name' => $this->photographer_name,
            'photographer_name_bn' => $this->photographer_name_bn,
            'feature_image' => $this->image ? image_url(config('helper.uploadPath') . $this->image) : null,
            'created_at' => $this->created_at,
            'created_at_bangla' => BengaliDateHelper::formatEnglishToBnDate($this->created_at),
        ];

        if ($this->relationLoaded('category')) {
            $data['category'] = [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ];
        }

        if ($this->relationLoaded('contents')) {
            $data['contents'] = PhotoResource::collection($this->contents);
        }

        return $data;
    }
}
