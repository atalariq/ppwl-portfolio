<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TechnologyController extends Controller
{
    public function index()
    {
        return view('post.technologies.index', [
            'technologies' => Technology::withCount('projects')->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('post.technologies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Technology::class)],
        ]);

        Technology::create($request->only('name'));

        return redirect()->route('post.technologies.index')->with('success', 'Teknologi berhasil ditambahkan.');
    }

    public function edit(Technology $technology)
    {
        return view('post.technologies.edit', ['technology' => $technology]);
    }

    public function update(Request $request, Technology $technology)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Technology::class)->ignore($technology->id)],
        ]);

        $technology->update($request->only('name'));

        return redirect()->route('post.technologies.index')->with('success', 'Teknologi berhasil diperbarui.');
    }

    public function destroy(Technology $technology)
    {
        $technology->delete();

        return redirect()->route('post.technologies.index')->with('success', 'Teknologi berhasil dihapus.');
    }
}
