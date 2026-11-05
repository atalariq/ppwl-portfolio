<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            [
                'role' => 'Hacker (Backend)',
                'organization' => 'Google Developer Group of Campus UGM',
                'period' => 'Dec 2025 - Present',
                'description' => 'Membangun Menu Catalog API dan berkontribusi pada beberapa proyek komunitas.',
                'sort_order' => 1,
            ],
            [
                'role' => 'Web Maintainer',
                'organization' => 'Freelance',
                'period' => '2024 - Present',
                'description' => 'Mengelola WordPress dan CMS, dengan fokus pada keandalan situs dan optimasi kecepatan.',
                'sort_order' => 2,
            ],
        ];

        foreach ($experiences as $experience) {
            Experience::updateOrCreate(
                [
                    'role' => $experience['role'],
                    'organization' => $experience['organization'],
                ],
                $experience,
            );
        }
    }
}
