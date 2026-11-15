<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
        if (!in_array($request->route()?->getActionMethod(), ['getTag', 'getTagBySlug'])) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'alias' => $this->alias,
                'sorting' => $this->sorting,
                'photo' => $this->image ? image_url(config('helper.uploadPath') . $this->image) : null,
                'full_url' => rtrim(config('app.frontend_url'), '/') . '/topic/' . $this->alias,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => $this->alias,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'meta_description' => $this->meta_description,
            'sorting' => $this->sorting,
            'photo' => $this->image ? image_url(config('helper.uploadPath') . $this->image) : null,
            'full_url' => rtrim(config('app.frontend_url'), '/') . '/topic/' . $this->alias,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contents' => BnContentResource::collection($this->whenLoaded('contents')),
        ];
    }
}
