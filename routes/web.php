<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'name' => 'Atalariq Barra Hadinugraha',
        'role' => 'Software Engineering Student at UGM',
        'summary' => 'Technical journey started from fixing a wiped Windows with Linux CLI. Focus on site reliability, speed optimization, and clean publishing workflows.',
    ]);
})->name('home');

Route::get('/about', function () {
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
})->name('about');

Route::get('/education', function () {
    return view('education', [
        'educations' => [
            [
                'institution' => 'Gadjah Mada University, Yogyakarta, Indonesia',
                'program' => 'Bachelor of Applied Science, Technology of Software Engineering',
                'period' => 'Aug 2025 — Present',
                'details' => ['Relevant Coursework: Web Programming I & II', 'GPA: 3.88/4.00'],
            ],
        ],
        'organizations' => [
            [
                'name' => 'Google Developer Group of Campus UGM',
                'role' => 'Hacker (Backend)',
                'period' => 'Dec 2025 — Present',
                'details' => ['Menu Catalog API (atalariq/menu-api)', 'Sift - AI Recruitment Screening & Talent Matching Platform', 'Sipilah - Circular Resilience App', 'Nyalara - Carbon Footprint Tracker App'],
            ],
        ],
    ]);
})->name('education');

Route::resource('projects', ProjectController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->middleware('auth');

Route::resource('projects', ProjectController::class)->only(['index', 'show']);

Route::controller(LoginRegisterController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/store', 'store')->name('store');
        Route::get('/login', 'login')->name('login');
        Route::post('/authenticate', 'authenticate')->name('authenticate');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::post('/logout', 'logout')->name('logout');
    });
});
