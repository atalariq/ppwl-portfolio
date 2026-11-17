<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary(),
            'thumbnail' => $this->thumbnail ? asset('storage/'.$this->thumbnail) : null,
            'category' => $this->whenLoaded('category', fn () => $this->category?->name),
            'technologies' => $this->whenLoaded(
                'technologies',
                fn () => $this->technologies->pluck('name')->values(),
            ),
            'project_date' => $this->project_date?->toDateString(),
            'is_featured' => (bool) $this->is_featured,
            'url' => route('projects.show', $this),
            'project_url' => $this->project_url,
            'repository_url' => $this->repository_url,
        ];
    }
}
