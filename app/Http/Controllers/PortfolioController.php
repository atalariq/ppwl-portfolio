<?php

namespace App\Http\Controllers;

use App\Models\AboutProfile;
use App\Models\Contact;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Support\PortfolioCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function home(): View
    {
        $featuredProjects = Cache::remember(PortfolioCache::FEATURED, 1800, fn () => Project::query()
            ->published()
            ->featured()
            ->with(['category', 'technologies', 'images'])
            ->orderByDesc('project_date')
            ->orderByDesc('id')
            ->take(5)
            ->get());

        return view('home', [
            'profile' => AboutProfile::current(),
            'featuredProjects' => $featuredProjects,
        ]);
    }

    public function about(): View
    {
        $skills = Cache::remember(PortfolioCache::SKILLS, 3600, fn () => Skill::query()->ordered()->get());

        return view('about', [
            'profile' => AboutProfile::current(),
            'contacts' => Contact::query()->ordered()->get(),
            'skillGroups' => $skills->groupBy('category'),
        ]);
    }

    public function experience(): View
    {
        return view('portfolio.experience', [
            'experiences' => Cache::remember(PortfolioCache::EXPERIENCES, 3600, fn () => Experience::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()),
        ]);
    }

    public function education(): View
    {
        return view('portfolio.education', [
            'educations' => Cache::remember(PortfolioCache::EDUCATIONS, 3600, fn () => Education::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()),
        ]);
    }
}
