<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'description' => $this->description,
            'location' => $this->location,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'is_published' => $this->is_published,
            'is_favorite' => $this->when(! is_null($this->is_favorite), fn () => (bool) $this->is_favorite),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
