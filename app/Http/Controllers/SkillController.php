<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    public function index()
    {
        return view('post.skills.index', [
            'skills' => Skill::orderByDesc('level')->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('post.skills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Skill::class)],
            'level' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        Skill::create($request->only(['name', 'level']));

        return redirect()->route('post.skills.index')->with('success', 'Skill berhasil ditambahkan.');
    }

    public function edit(Skill $skill)
    {
        return view('post.skills.edit', ['skill' => $skill]);
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Skill::class)->ignore($skill->id)],
            'level' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $skill->update($request->only(['name', 'level']));

        return redirect()->route('post.skills.index')->with('success', 'Skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('post.skills.index')->with('success', 'Skill berhasil dihapus.');
    }
}
