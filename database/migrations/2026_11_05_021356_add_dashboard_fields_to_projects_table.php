<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('slug', 220)->nullable()->unique()->after('title');
            $table->string('status', 20)->default('draft')->index()->after('description');
            $table->string('thumbnail', 255)->nullable()->after('status');
        });

        $used = [];

        foreach (DB::table('projects')->orderBy('id')->get(['id', 'title']) as $project) {
            $base = Str::slug($project->title) ?: 'project-'.$project->id;
            $slug = $base;
            $suffix = 2;

            while (in_array($slug, $used, true)) {
                $slug = $base.'-'.$suffix;
                $suffix++;
            }

            DB::table('projects')->where('id', $project->id)->update(['slug' => $slug]);
            $used[] = $slug;
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'slug', 'status', 'thumbnail']);
        });
    }
};
