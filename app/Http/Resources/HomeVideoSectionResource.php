<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeVideoSectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $videoId = null;
        $code = $this->embed_code;

        if ($this->type == 1) {
            // YouTube
            if (preg_match('/embed\/([a-zA-Z0-9_-]+)/', $code, $matches)) {
                $videoId = $matches[1];
            } elseif (preg_match('/[?&]v=([a-zA-Z0-9_-]+)/', $code, $matches)) {
                $videoId = $matches[1];
            } elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $code, $matches)) {
                $videoId = $matches[1];
            }
        } else {
            // Facebook
            $decoded = urldecode($code);
            if (preg_match('/(?:videos|reel)\/(\d+)/', $decoded, $matches)) {
                $videoId = $matches[1];
            } elseif (preg_match('/[?&]v=(\d+)/', $decoded, $matches)) {
                $videoId = $matches[1];
            }
        }

        return [
            'id'          => $this->id,
            'heading'     => $this->heading,
            'embed_code'  => $this->embed_code,
            'video_id'    => $videoId,
            'type'        => $this->type == 1 ? 'youtube' : 'facebook',
            'is_live'     => (int)$this->is_live === 1,
            'status'      => (int)$this->status === 1,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
