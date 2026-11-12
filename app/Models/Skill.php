<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public const CATEGORIES = ['Languages', 'Frameworks', 'Data', 'Tools', 'Platforms'];

    protected $fillable = ['name', 'category'];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw("CASE category WHEN 'Languages' THEN 1 WHEN 'Frameworks' THEN 2 WHEN 'Data' THEN 3 WHEN 'Tools' THEN 4 WHEN 'Platforms' THEN 5 ELSE 6 END")
            ->orderBy('name');
    }
}
