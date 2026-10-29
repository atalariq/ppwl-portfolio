<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25], true) ? $perPage : 10;

        $query = Project::query();

        if ($request->filled('search')) {
            $query->search($request->string('search'));
        }

        $projects = $query->latest()->paginate($perPage)->withQueryString();

        return view('projects.index', ['projects' => $projects]);
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|max:200',
            'repo' => 'required|max:200',
            'description' => 'required|min:10',
        ]);

        Project::create($request->only(['title', 'repo', 'description']));

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('projects.show', ['project' => $project]);
    }

    public function edit(Project $project)
    {
        return view('projects.edit', ['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|min:5|max:200',
            'repo' => 'required|max:200',
            'description' => 'required|min:10',
        ]);

        $project->update($request->only(['title', 'repo', 'description']));

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
