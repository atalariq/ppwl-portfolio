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
            'Laravel',
            'PHP',
            'Tailwind CSS',
            'Alpine.js',
            'MySQL',
            'Redis',
            'Docker',
            'Git',
        ];

        foreach ($technologies as $name) {
            Technology::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
