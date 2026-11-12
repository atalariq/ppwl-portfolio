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
            $table->string('short_description', 255)->nullable()->after('slug');
            $table->renameColumn('repo', 'repository_url');
            $table->text('description')->nullable()->change();
            $table->date('project_date')->nullable()->after('status');
            $table->boolean('is_featured')->default(false)->after('project_date');
            $table->string('project_url', 255)->nullable()->after('is_featured');
        });

        foreach (DB::table('projects')->orderBy('id')->get() as $project) {
            $repositoryUrl = trim((string) $project->repository_url);

            if ($repositoryUrl !== '' && ! Str::startsWith($repositoryUrl, ['http://', 'https://'])) {
                $repositoryUrl = 'https://github.com/'.ltrim($repositoryUrl, '/');
            }

            DB::table('projects')->where('id', $project->id)->update([
                'repository_url' => $repositoryUrl !== '' ? $repositoryUrl : null,
                'short_description' => $project->short_description ?: Str::limit((string) $project->description, 250),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('projects')->whereNull('description')->update(['description' => '']);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'project_date', 'is_featured', 'project_url']);
            $table->renameColumn('repository_url', 'repo');
            $table->mediumText('description')->nullable(false)->change();
        });

        foreach (DB::table('projects')->orderBy('id')->get() as $project) {
            $repository = Str::after((string) $project->repo, 'github.com/');

            DB::table('projects')->where('id', $project->id)->update([
                'repo' => $repository !== '' ? $repository : $project->repo,
            ]);
        }
    }
};
