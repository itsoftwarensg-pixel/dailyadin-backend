<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WriterResource extends JsonResource
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
        if (!in_array($request->route()?->getActionMethod(), ['getWriter', 'getWriterBySlug'])) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'alias' => $this->alias,
                'title' => $this->title,
                'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
                'full_url' => rtrim(config('app.frontend_url'), '/') . '/author/' . $this->alias,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'alias' => $this->alias,
            'title' => $this->title,
            'description' => $this->description,
            'photo' => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
            'full_url' => rtrim(config('app.frontend_url'), '/') . '/author/' . $this->alias,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'contents' => BnContentResource::collection($this->whenLoaded('contents')),
        ];
    }
}
