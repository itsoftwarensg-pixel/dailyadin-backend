<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BnDivisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if we are in a single resource context
        if (!in_array($request->route()->getActionMethod(), ['getDivision', 'getDivisionBySlug'])) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'name_bn' => $this->name_bn,
                'alias' => $this->alias,
                'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
                'full_url' => rtrim(config('app.frontend_url'), '/') . '/country/' . $this->alias,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'alias' => $this->alias,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'description' => $this->description,
            'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
            'full_url' => rtrim(config('app.frontend_url'), '/') . '/country/' . $this->alias,
            'districts' => BnDistrictResource::collection($this->whenLoaded('districts')),
            'contents' => BnContentResource::collection($this->whenLoaded('contents')),
        ];
    }
}
