<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TechnologyController extends Controller
{
    public function index(): View
    {
        return view('admin.technologies.index', [
            'technologies' => Technology::withCount('projects')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.technologies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Technology::class)],
        ]);

        Technology::create($request->only('name'));

        return redirect()->route('admin.technologies.index')->with('success', 'Technology created.');
    }

    public function edit(Technology $technology): View
    {
        return view('admin.technologies.edit', ['technology' => $technology]);
    }

    public function update(Request $request, Technology $technology): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Technology::class)->ignore($technology->id)],
        ]);

        $technology->update($request->only('name'));

        return redirect()->route('admin.technologies.index')->with('success', 'Technology updated.');
    }

    public function quickUpdate(Request $request, Technology $technology): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Technology::class)->ignore($technology->id)],
        ]);

        $technology->update($request->only('name'));

        return back()->with('success', 'Technology updated.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:technologies,id',
        ]);

        Technology::query()->whereIn('id', $validated['ids'])->delete();

        return back()->with('success', 'Selected technologies deleted.');
    }

    public function bulkStore(Request $request): RedirectResponse
    {
        $request->validate([
            'names' => 'required|string',
        ]);

        $created = 0;
        $skipped = 0;

        foreach (preg_split('/\r\n|\r|\n/', $request->string('names')->toString()) as $line) {
            $name = trim($line);

            if ($name === '' || mb_strlen($name) > 100 || Technology::query()->where('name', $name)->exists()) {
                $skipped++;

                continue;
            }

            Technology::create(['name' => $name]);
            $created++;
        }

        return back()->with('success', "{$created} technologies added, {$skipped} skipped.");
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $technology->delete();

        return redirect()->route('admin.technologies.index')->with('success', 'Technology deleted.');
    }
}
