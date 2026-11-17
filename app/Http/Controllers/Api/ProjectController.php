<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 9);
        $perPage = in_array($perPage, [9, 18, 27], true) ? $perPage : 9;
        $sort = $request->string('sort')->toString();

        $projects = Project::query()
            ->published()
            ->with(['category', 'technologies'])
            ->when($request->filled('search'), fn ($query) => $query->search($request->string('search')->toString()))
            ->when($request->filled('category'), fn ($query) => $query->categorySlug($request->string('category')->toString()))
            ->when($request->filled('technology'), fn ($query) => $query->technologySlug($request->string('technology')->toString()))
            ->sorted($sort)
            ->paginate($perPage)
            ->withQueryString();

        return ProjectResource::collection($projects);
    }

    public function show(Project $project): ProjectResource
    {
        abort_unless($project->status === 'published', 404);

        return new ProjectResource($project->load(['category', 'technologies']));
    }
}
