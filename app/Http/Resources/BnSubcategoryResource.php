<?php

namespace App\Http\Resources;

use App\Models\BnSubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BnSubcategoryResource extends JsonResource
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
        if (!in_array($request->route()->getActionMethod(), ['getSubcategory', 'getSubCategoryBySlug'])) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'alias' => $this->alias,
                'sorting' => $this->sorting,
                'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
                'full_url' => rtrim(config('app.frontend_url'), '/') . '/' . ($this->category->alias ?? 'news') . '/' . $this->alias,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => $this->alias,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'description' => $this->description,
            'sorting' => $this->sorting,
            'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
            'full_url' => rtrim(config('app.frontend_url'), '/') . '/' . ($this->category->alias ?? 'news') . '/' . $this->alias,
            'category' => $this->when(in_array('category', $includes), new BnCategoryResource($this->category)),
            'contents' => BnContentResource::collection($this->whenLoaded('contents')),
        ];
    }
}
