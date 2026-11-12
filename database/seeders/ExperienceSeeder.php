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
                'description' => 'Building the Menu Catalog API and contributing to several community projects.',
                'sort_order' => 1,
            ],
            [
                'role' => 'Web Maintainer',
                'organization' => 'Freelance',
                'period' => '2024 - Present',
                'description' => 'Maintaining WordPress and CMS sites with a focus on reliability and speed.',
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
