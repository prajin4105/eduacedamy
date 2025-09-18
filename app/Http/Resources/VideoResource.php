<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'video_url' => $this->video_url ? url('storage/' . $this->video_url) : null,
            'thumbnail_url' => $this->thumbnail_url ? url('storage/' . $this->thumbnail_url) : null,
            'duration_in_seconds' => $this->duration_seconds,
            'sort_order' => $this->sort_order,
            'is_published' => $this->is_published,
        ];
    }
}
