<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BnCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includes = explode(',', $request->include ?? '');

        // Check if we are in a single resource context
        if (!in_array($request->route()->getActionMethod(), ['getCategory', 'getCategoryBySlug'])) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'name_bn' => $this->name_bn,
                'alias' => $this->alias,
                'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
                'full_url' => rtrim(config('app.frontend_url'), '/') . '/' . $this->alias,
                'subcategories' => BnSubcategoryResource::collection($this->whenLoaded('subcategories')),
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
            'full_url' => rtrim(config('app.frontend_url'), '/') . '/' . $this->alias,
            'subcategories' => BnSubcategoryResource::collection($this->whenLoaded('subcategories')),
            'contents' => BnContentResource::collection($this->whenLoaded('contents')),
            'position_contents' => $this->when(isset($this->position_contents), function () {
                return BnContentResource::collection($this->position_contents);
            }),
        ];
    }
}
