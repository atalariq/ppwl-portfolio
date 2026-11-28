<?php

namespace App\Models;

use App\Support\PortfolioCache;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['role', 'organization', 'period', 'description', 'sort_order'];

    protected static function booted(): void
    {
        static::saved(fn () => PortfolioCache::forget(PortfolioCache::EXPERIENCES));
        static::deleted(fn () => PortfolioCache::forget(PortfolioCache::EXPERIENCES));
    }
}
