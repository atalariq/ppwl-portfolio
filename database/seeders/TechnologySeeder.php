<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            'PHP', 'JavaScript', 'TypeScript', 'Go', 'Python', 'HTML', 'CSS', 'Shell',
            'Laravel', 'React', 'Next.js', 'Astro', 'React Native', 'Expo', 'Express',
            'Hono', 'Tailwind CSS', 'Bootstrap',
            'MySQL', 'MariaDB', 'PostgreSQL', 'Firestore', 'Firebase', 'Prisma',
            'Docker', 'Nix', 'Playwright', 'Typst', 'Gemini API', 'Fly.io',
            'Cloudflare Pages',
        ];

        $slugs = collect($technologies)->map(fn (string $name) => Str::slug($name))->all();

        Technology::query()->whereNotIn('slug', $slugs)->delete();

        foreach ($technologies as $name) {
            Technology::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
