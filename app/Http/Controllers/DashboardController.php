<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\Technology;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard.index', [
            'totalProjects' => Project::count(),
            'publishedProjects' => Project::where('status', 'published')->count(),
            'draftProjects' => Project::where('status', 'draft')->count(),
            'totalCategories' => Category::count(),
            'totalTechnologies' => Technology::count(),
            'totalSkills' => Skill::count(),
            'totalGalleryImages' => ProjectImage::count(),
            'latestProjects' => Project::with(['category', 'images'])->latest()->take(5)->get(),
            'skillsByCategory' => Skill::query()
                ->selectRaw('category, count(*) as total')
                ->groupBy('category')
                ->orderBy('category')
                ->get(),
            'categories' => Category::withCount('projects')->orderByDesc('projects_count')->orderBy('name')->get(),
        ]);
    }
}
