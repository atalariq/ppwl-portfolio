<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'name' => 'Atalariq Barra Hadinugraha',
            'role' => 'Software Engineering Student at UGM',
            'summary' => 'Technical journey started from fixing a wiped Windows with Linux CLI. Focus on site reliability, speed optimization, and clean publishing workflows.',
        ]);
    }

    public function about(): View
    {
        return view('about', [
            'email' => 'atalariq.dev@outlook.com',
            'contact' => 'wa.me/62859106987796',
            'skills' => [
                'Programming Languages' => 'JavaScript/TypeScript, PHP, Go',
                'Frameworks' => 'Laravel',
                'Tools' => 'Linux/CLI, Git & GitHub, Docker, Neovim',
                'Languages' => 'Bahasa Indonesia (Fluent), English (Intermediate)',
                'Interest' => 'Cyber Security, DevOps, Infrastructure, Optimization',
            ],
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
