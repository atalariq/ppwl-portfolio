<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Atalariq Barra',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'username' => 'test',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
        );

        $this->call([
            CategorySeeder::class,
            TechnologySeeder::class,
            SkillSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            AboutProfileSeeder::class,
            ContactSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
