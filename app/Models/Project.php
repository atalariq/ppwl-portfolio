<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'category_id',
        'status',
        'project_date',
        'is_featured',
        'project_url',
        'repository_url',
        'thumbnail',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (blank($project->slug)) {
                $project->slug = static::uniqueSlug($project->title, $project->exists ? $project->id : null);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'project_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'project';
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                ->orWhere('short_description', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeCategorySlug(Builder $query, string $slug): Builder
    {
        return $query->whereHas('category', fn (Builder $query) => $query->where('slug', $slug));
    }

    public function scopeTechnologySlug(Builder $query, string $slug): Builder
    {
        return $query->whereHas('technologies', fn (Builder $query) => $query->where('slug', $slug));
    }

    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'oldest' => $query->orderBy('project_date')->orderBy('id'),
            'title' => $query->orderBy('title'),
            'project_date' => $query->orderByDesc('project_date')->orderByDesc('id'),
            default => $query->orderByDesc('project_date')->orderByDesc('id'),
        };
    }

    public function summary(): string
    {
        return $this->short_description
            ?: Str::limit(strip_tags((string) $this->description), 160);
    }

    public function thumbnailUrl(): string
    {
        $path = $this->thumbnail ?: $this->images->first()?->image;

        return $path
            ? asset('storage/'.$path)
            : asset('images/project-placeholder.svg');
    }

    public function repositoryLabel(): ?string
    {
        if (blank($this->repository_url)) {
            return null;
        }

        $host = parse_url($this->repository_url, PHP_URL_HOST);
        $path = trim((string) parse_url($this->repository_url, PHP_URL_PATH), '/');

        if ($host === 'github.com' && $path !== '') {
            return $path;
        }

        return $host ?: $this->repository_url;
    }

    public function projectDateLabel(): ?string
    {
        return $this->project_date?->format('M Y');
    }
}
