<?php

namespace App\Models;

use App\Support\PortfolioCache;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = ['institution', 'program', 'period', 'details', 'sort_order'];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => PortfolioCache::forget(PortfolioCache::EDUCATIONS));
        static::deleted(fn () => PortfolioCache::forget(PortfolioCache::EDUCATIONS));
    }
}
