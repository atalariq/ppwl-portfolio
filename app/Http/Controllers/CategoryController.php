<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('projects')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class)],
        ]);

        Category::create($request->only('name'));

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class)->ignore($category->id)],
        ]);

        $category->update($request->only('name'));

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function quickUpdate(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class)->ignore($category->id)],
        ]);

        $category->update($request->only('name'));

        return back()->with('success', 'Category updated.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:delete',
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:categories,id',
        ]);

        Category::query()->whereIn('id', $validated['ids'])->delete();

        return back()->with('success', 'Selected categories deleted.');
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

            if ($name === '' || mb_strlen($name) > 100 || Category::query()->where('name', $name)->exists()) {
                $skipped++;

                continue;
            }

            Category::create(['name' => $name]);
            $created++;
        }

        return back()->with('success', "{$created} categories added, {$skipped} skipped.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
