<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        return view('admin.skills.index', [
            'skills' => Skill::query()->ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.skills.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Skill::class)],
            'category' => ['required', 'string', 'max:50'],
        ]);

        Skill::create($request->only(['name', 'category']));

        return redirect()->route('admin.skills.index')->with('success', 'Skill created.');
    }

    public function edit(Skill $skill): View
    {
        return view('admin.skills.edit', ['skill' => $skill]);
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Skill::class)->ignore($skill->id)],
            'category' => ['required', 'string', 'max:50'],
        ]);

        $skill->update($request->only(['name', 'category']));

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated.');
    }

    public function quickUpdate(Request $request, Skill $skill): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Skill::class)->ignore($skill->id)],
            'category' => ['required', 'string', 'max:50'],
        ]);

        $skill->update($request->only(['name', 'category']));

        return back()->with('success', 'Skill updated.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:skills,id',
        ]);

        Skill::query()->whereIn('id', $validated['ids'])->delete();

        return back()->with('success', 'Selected skills deleted.');
    }

    public function bulkStore(Request $request): RedirectResponse
    {
        $request->validate([
            'entries' => 'required|string',
        ]);

        $created = 0;
        $skipped = 0;

        foreach (preg_split('/\r\n|\r|\n/', $request->string('entries')->toString()) as $line) {
            $line = trim($line);

            if ($line === '') {
                $skipped++;

                continue;
            }

            [$name, $category] = array_pad(array_map('trim', explode(',', $line, 2)), 2, 'Tools');
            $category = $category === '' ? 'Tools' : $category;

            if ($name === '' || mb_strlen($name) > 100 || mb_strlen($category) > 50 || Skill::query()->where('name', $name)->exists()) {
                $skipped++;

                continue;
            }

            Skill::create(['name' => $name, 'category' => $category]);
            $created++;
        }

        return back()->with('success', "{$created} skills added, {$skipped} skipped.");
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted.');
    }
}
