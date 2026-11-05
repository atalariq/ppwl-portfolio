<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function publicIndex(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25], true) ? $perPage : 10;

        $query = Project::query();

        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        $projects = $query->latest()->paginate($perPage)->withQueryString();

        return view('portfolio.projects', ['projects' => $projects]);
    }

    public function index()
    {
        $projects = Project::latest()->paginate(10);

        return view('post.projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        return view('post.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|max:200',
            'repo' => 'required|max:200',
            'description' => 'required|min:10',
        ]);

        Project::create($request->only(['title', 'repo', 'description']));

        return redirect()->route('post.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('portfolio.project', ['project' => $project]);
    }

    public function edit(Project $project)
    {
        return view('post.projects.edit', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|min:5|max:200',
            'repo' => 'required|max:200',
            'description' => 'required|min:10',
        ]);

        $project->update($request->only(['title', 'repo', 'description']));

        return redirect()->route('post.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('post.projects.index')->with('success', 'Project deleted successfully.');
    }
}
