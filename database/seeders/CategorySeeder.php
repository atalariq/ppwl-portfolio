<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $renames = [
            'web-application' => ['Web', 'web'],
            'backend-api' => ['API', 'api'],
            'command-line-tool' => ['Tool', 'tool'],
        ];

        foreach ($renames as $oldSlug => [$name, $slug]) {
            $old = Category::query()->where('slug', $oldSlug)->first();

            if (! $old) {
                continue;
            }

            $existing = Category::query()->where('slug', $slug)->whereKeyNot($old->id)->first();

            if ($existing) {
                $old->projects()->update(['category_id' => $existing->id]);
                $old->delete();
            } else {
                $old->update(['name' => $name, 'slug' => $slug]);
            }
        }

        foreach (['Web', 'API', 'Mobile', 'Design System', 'Tool'] as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }
    }
}
