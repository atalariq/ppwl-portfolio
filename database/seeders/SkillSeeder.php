<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'PHP', 'category' => 'Languages'],
            ['name' => 'JavaScript', 'category' => 'Languages'],
            ['name' => 'TypeScript', 'category' => 'Languages'],
            ['name' => 'Go', 'category' => 'Languages'],
            ['name' => 'Python', 'category' => 'Languages'],
            ['name' => 'SQL', 'category' => 'Languages'],
            ['name' => 'HTML', 'category' => 'Languages'],
            ['name' => 'CSS', 'category' => 'Languages'],
            ['name' => 'Laravel', 'category' => 'Frameworks'],
            ['name' => 'React', 'category' => 'Frameworks'],
            ['name' => 'Next.js', 'category' => 'Frameworks'],
            ['name' => 'Astro', 'category' => 'Frameworks'],
            ['name' => 'React Native', 'category' => 'Frameworks'],
            ['name' => 'Expo', 'category' => 'Frameworks'],
            ['name' => 'Tailwind CSS', 'category' => 'Frameworks'],
            ['name' => 'Bootstrap', 'category' => 'Frameworks'],
            ['name' => 'MySQL', 'category' => 'Data'],
            ['name' => 'MariaDB', 'category' => 'Data'],
            ['name' => 'PostgreSQL', 'category' => 'Data'],
            ['name' => 'Firestore', 'category' => 'Data'],
            ['name' => 'Firebase', 'category' => 'Data'],
            ['name' => 'Prisma', 'category' => 'Data'],
            ['name' => 'Git', 'category' => 'Tools'],
            ['name' => 'Docker', 'category' => 'Tools'],
            ['name' => 'Linux', 'category' => 'Tools'],
            ['name' => 'Nix', 'category' => 'Tools'],
            ['name' => 'Playwright', 'category' => 'Tools'],
            ['name' => 'Typst', 'category' => 'Tools'],
            ['name' => 'Neovim', 'category' => 'Tools'],
            ['name' => 'Gemini API', 'category' => 'Platforms'],
            ['name' => 'Fly.io', 'category' => 'Platforms'],
            ['name' => 'Cloudflare Pages', 'category' => 'Platforms'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                $skill,
            );
        }
    }
}
