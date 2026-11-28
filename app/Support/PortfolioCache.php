<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class PortfolioCache
{
    public const FEATURED = 'portfolio.featured';

    public const CATEGORIES = 'portfolio.categories';

    public const SKILLS = 'portfolio.skills';

    public const EXPERIENCES = 'portfolio.experiences';

    public const EDUCATIONS = 'portfolio.educations';

    public const KEYS = [
        self::FEATURED,
        self::CATEGORIES,
        self::SKILLS,
        self::EXPERIENCES,
        self::EDUCATIONS,
    ];

    public static function forget(string ...$keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
