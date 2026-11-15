<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StarProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $videoLink = $this->video_link;
        $videoId = null;

        if ($videoLink) {
            // Basic extraction logic covering common YouTube/FB formats
            if (preg_match('/embed\/([a-zA-Z0-9_-]+)/', $videoLink, $matches)) {
                $videoId = $matches[1];
            } elseif (preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $videoLink, $matches)) {
                $videoId = $matches[1];
            } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $videoLink, $matches)) {
                $videoId = $matches[1];
            } elseif (preg_match('/(?:videos|reel)\/(\d+)/', $videoLink, $matches)) {
                $videoId = $matches[1];
            }
        }

        return [
            'identity'    => $this->identity,
            'details'     => $this->details,
            'quote'       => $this->quote,
            'photo_path'  => $this->photo ? image_url(config('helper.uploadPath') . $this->photo) : null,
            'video_link'  => $this->video_link,
            'video_id'    => $videoId,
            'status'      => (int)$this->status === 1,
        ];
    }
}
