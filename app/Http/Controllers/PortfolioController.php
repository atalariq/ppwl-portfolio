<?php

namespace App\Http\Controllers;

use App\Models\AboutProfile;
use App\Models\Contact;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'profile' => AboutProfile::current(),
            'featuredProjects' => Project::query()
                ->published()
                ->featured()
                ->with(['category', 'technologies', 'images'])
                ->orderByDesc('project_date')
                ->orderByDesc('id')
                ->take(5)
                ->get(),
        ]);
    }

    public function about(): View
    {
        return view('about', [
            'profile' => AboutProfile::current(),
            'contacts' => Contact::query()->ordered()->get(),
            'skillGroups' => Skill::query()->ordered()->get()->groupBy('category'),
        ]);
    }

    public function experience(): View
    {
        return view('portfolio.experience', [
            'experiences' => Experience::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function education(): View
    {
        return view('portfolio.education', [
            'educations' => Education::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
