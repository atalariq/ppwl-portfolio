<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function publicIndex(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25], true) ? $perPage : 10;

        $query = Project::query()->published()->with('category');

        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        $projects = $query->latest()->paginate($perPage)->withQueryString();

        return view('portfolio.projects', ['projects' => $projects]);
    }

    public function index(Request $request)
    {
        $query = Project::query()->with('category')->withCount('images');

        if ($request->filled('status') && in_array($request->string('status')->toString(), ['published', 'draft'], true)) {
            $query->where('status', $request->string('status'));
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('post.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        return view('post.projects.create', [
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:200',
            'repo' => 'required|max:200',
            'description' => 'required|min:10',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $project = Project::create($validated);
        $project->technologies()->sync($request->input('technologies', []));

        return redirect()->route('post.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $canPreview = auth()->check() && auth()->user()->isAdmin();

        if ($project->status !== 'published' && ! $canPreview) {
            abort(404);
        }

        return view('portfolio.project', [
            'project' => $project->load(['category', 'images', 'technologies']),
        ]);
    }

    public function edit(Project $project)
    {
        return view('post.projects.edit', [
            'project' => $project->load(['images', 'technologies']),
            'categories' => Category::orderBy('name')->get(),
            'technologies' => Technology::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:200',
            'repo' => 'required|max:200',
            'description' => 'required|min:10',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:published,draft',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=400,min_height=300',
            'technologies' => 'nullable|array',
            'technologies.*' => 'exists:technologies,id',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $project->update($validated);
        $project->technologies()->sync($request->input('technologies', []));

        return redirect()->route('post.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }

        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $project->technologies()->detach();
        $project->delete();

        return redirect()->route('post.projects.index')->with('success', 'Project deleted successfully.');
    }
}
