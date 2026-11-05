<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'Laravel', 'level' => 88],
            ['name' => 'PHP', 'level' => 85],
            ['name' => 'JavaScript', 'level' => 80],
            ['name' => 'Linux', 'level' => 78],
            ['name' => 'Docker', 'level' => 72],
            ['name' => 'Tailwind CSS', 'level' => 70],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                ['level' => $skill['level']],
            );
        }
    }
}
