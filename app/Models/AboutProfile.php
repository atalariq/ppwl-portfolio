<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutProfile extends Model
{
    protected $fillable = [
        'full_name',
        'role',
        'summary',
        'bio',
        'location',
        'website_url',
        'github_url',
        'linkedin_url',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'full_name' => 'Atalariq Barra Hadinugraha',
            'role' => 'Software Engineering Student at UGM',
        ]);
    }
}
