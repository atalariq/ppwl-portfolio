<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
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
                'images' => 'Maksimal 10 gambar per project. Saat ini tersimpan '.$existing.' gambar.',
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

        return back()->with('success', 'Gambar gallery berhasil diunggah.');
    }

    public function update(Request $request, Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);

        $image->update($validated);

        return back()->with('success', 'Caption gambar diperbarui.');
    }

    public function destroy(Project $project, ProjectImage $image)
    {
        abort_if($image->project_id !== $project->id, 404);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar gallery dihapus.');
    }
}
