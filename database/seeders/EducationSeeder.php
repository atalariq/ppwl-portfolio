<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $educations = [
            [
                'institution' => 'Gadjah Mada University, Yogyakarta, Indonesia',
                'program' => 'Bachelor of Applied Science, Technology of Software Engineering',
                'period' => 'Aug 2025 - Present',
                'details' => [
                    'Relevant Coursework: Web Programming I & II',
                    'GPA: 3.88/4.00',
                ],
                'sort_order' => 1,
            ],
        ];

        foreach ($educations as $education) {
            Education::updateOrCreate(
                [
                    'institution' => $education['institution'],
                    'program' => $education['program'],
                ],
                $education,
            );
        }
    }
}
