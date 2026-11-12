<?php

namespace Database\Seeders;

use App\Models\AboutProfile;
use Illuminate\Database\Seeder;

class AboutProfileSeeder extends Seeder
{
    public function run(): void
    {
        AboutProfile::query()->updateOrCreate(
            ['id' => 1],
            [
                'full_name' => 'Atalariq Barra Hadinugraha',
                'role' => 'Software Engineering Student at UGM',
                'summary' => 'I build reliable web systems, explore the command line, and turn everyday problems into small, useful tools.',
                'bio' => "I am a Software Engineering student at Universitas Gadjah Mada. My technical journey started when I tried installing Linux, accidentally wiped Windows, and had to learn the command line to fix my machine.\n\nToday I work across backend and frontend: Laravel applications, Go APIs, small JavaScript tools, and the occasional design system. I care about reliable systems, clear interfaces, and documentation that helps the next person move faster.\n\nOutside coursework I maintain my own portfolio, digital garden, and dotfiles. I also enjoy teaching others through practical notes and reproducible examples.",
                'location' => 'Sleman, Yogyakarta, Indonesia',
                'website_url' => 'https://atalariq.dev',
                'github_url' => 'https://github.com/atalariq',
                'linkedin_url' => 'https://linkedin.com/in/atalariq',
            ],
        );
    }
}
