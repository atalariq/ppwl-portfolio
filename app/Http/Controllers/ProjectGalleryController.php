<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Support\PortfolioCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProjectGalleryController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $existing = $project->images()->count();
        $incoming = count($validated['images']);

        if ($existing + $incoming > 10) {
            throw ValidationException::withMessages([
                'images' => 'A project can have at most 10 gallery images. Currently saved: '.$existing.'.',
            ]);
        }

        $sortOrder = (int) $project->images()->max('sort_order');

        foreach ($validated['images'] as $file) {
            $sortOrder++;

            $project->images()->create([
                'image' => $file->store('projects/gallery', 'public'),
                'sort_order' => $sortOrder,
            ]);
        }

        PortfolioCache::forget(PortfolioCache::FEATURED);

        return back()->with('success', 'Gallery images uploaded.');
    }

    public function update(Request $request, Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $image->update($validated);

        PortfolioCache::forget(PortfolioCache::FEATURED);

        return back()->with('success', 'Image caption updated.');
    }

    public function destroy(Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        PortfolioCache::forget(PortfolioCache::FEATURED);

        return back()->with('success', 'Gallery image deleted.');
    }
}
