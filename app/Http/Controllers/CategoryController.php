<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('post.categories.index', [
            'categories' => Category::withCount('projects')->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('post.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class)],
        ]);

        Category::create($request->only('name'));

        return redirect()->route('post.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('post.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique(Category::class)->ignore($category->id)],
        ]);

        $category->update($request->only('name'));

        return redirect()->route('post.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('post.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
