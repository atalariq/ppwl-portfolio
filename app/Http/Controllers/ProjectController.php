<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function publicIndex(Request $request): View
    {
        $perPage = $request->integer('per_page', 9);
        $perPage = in_array($perPage, [9, 18, 27], true) ? $perPage : 9;
        $sort = $request->string('sort')->toString();

        $projects = Project::query()
            ->published()
            ->with(['category', 'technologies', 'images'])
            ->when($request->filled('search'), fn ($query) => $query->search($request->string('search')->toString()))
            ->when($request->filled('category'), fn ($query) => $query->categorySlug($request->string('category')->toString()))
            ->when($request->filled('technology'), fn ($query) => $query->technologySlug($request->string('technology')->toString()))
            ->sorted($sort)
            ->paginate($perPage)
            ->withQueryString();

        return view('portfolio.projects', [
            'projects' => $projects,
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with(['category', 'images'])
            ->withCount('images')
            ->when($request->filled('search'), fn ($query) => $query->search($request->string('search')->toString()))
            ->when($request->filled('status'), fn ($query) => $query->whereIn('status', ['published', 'draft'])->where('status', $request->string('status')->toString()))
            ->when($request->filled('category'), fn ($query) => $query->categorySlug($request->string('category')->toString()))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProject($request);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $technologyIds = $validated['technologies'] ?? [];
        unset($validated['technologies']);

        $project = Project::create($validated);
        $project->technologies()->sync($technologyIds);

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function show(Project $project): View
    {
        $canPreview = auth()->check() && auth()->user()->isAdmin();

        if ($project->status !== 'published' && ! $canPreview) {
            abort(404);
        }

        return view('portfolio.project', [
            'project' => $project->load(['category', 'images', 'technologies']),
        ]);
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project->load(['images', 'technologies']),
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $this->validateProject($request);

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $technologyIds = $validated['technologies'] ?? [];
        unset($validated['technologies']);

        $project->update($validated);
        $project->technologies()->sync($technologyIds);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function quickUpdate(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $project->update($validated);

        return back()->with('success', 'Project updated.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:publish,draft,feature,unfeature,category,delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:projects,id',
            'category_id' => 'nullable|required_if:action,category|exists:categories,id',
        ]);

        $projects = Project::query()->whereIn('id', $validated['ids'])->get();

        foreach ($projects as $project) {
            if ($validated['action'] === 'publish') {
                $project->update(['status' => 'published']);
            } elseif ($validated['action'] === 'draft') {
                $project->update(['status' => 'draft']);
            } elseif ($validated['action'] === 'feature') {
                $project->update(['is_featured' => true]);
            } elseif ($validated['action'] === 'unfeature') {
                $project->update(['is_featured' => false]);
            } elseif ($validated['action'] === 'category') {
                $project->update(['category_id' => $validated['category_id']]);
            } elseif ($validated['action'] === 'delete') {
                $this->deleteProject($project);
            }
        }

        $message = match ($validated['action']) {
            'publish' => 'Selected projects published.',
            'draft' => 'Selected projects moved to draft.',
            'feature' => 'Selected projects featured.',
            'unfeature' => 'Selected projects removed from featured.',
            'category' => 'Selected projects moved to the new category.',
            'delete' => 'Selected projects deleted.',
        };

        return back()->with('success', $message);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteProject($project);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }

    private function validateProject(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:200',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
            'project_date' => 'nullable|date',
            'is_featured' => 'nullable|boolean',
            'project_url' => 'nullable|url|max:255',
            'repository_url' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }

    private function deleteProject(Project $project): void
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $project->technologies()->detach();
        $project->delete();
    }
}
