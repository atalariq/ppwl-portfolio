<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectGalleryController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TechnologyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PortfolioController::class, 'home'])->name('home');
Route::get('/about', [PortfolioController::class, 'about'])->name('about');
Route::get('/experience', [PortfolioController::class, 'experience'])->name('experience');
Route::get('/education', [PortfolioController::class, 'education'])->name('education');

Route::get('/projects', [ProjectController::class, 'publicIndex'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/contact', [ContactMessageController::class, 'create'])->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('cache/clear', [DashboardController::class, 'clearCache'])->name('cache.clear');

    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('projects/bulk', [ProjectController::class, 'bulk'])->name('projects.bulk');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::patch('projects/{project}/quick', [ProjectController::class, 'quickUpdate'])->name('projects.quick-update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::post('projects/{project}/images', [ProjectGalleryController::class, 'store'])->name('projects.images.store');
    Route::put('projects/{project}/images/{image}', [ProjectGalleryController::class, 'update'])->name('projects.images.update');
    Route::delete('projects/{project}/images/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.images.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/bulk', [CategoryController::class, 'bulk'])->name('categories.bulk');
    Route::post('categories/bulk-store', [CategoryController::class, 'bulkStore'])->name('categories.bulk-store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::patch('categories/{category}/quick', [CategoryController::class, 'quickUpdate'])->name('categories.quick-update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('technologies', [TechnologyController::class, 'index'])->name('technologies.index');
    Route::get('technologies/create', [TechnologyController::class, 'create'])->name('technologies.create');
    Route::post('technologies', [TechnologyController::class, 'store'])->name('technologies.store');
    Route::post('technologies/bulk', [TechnologyController::class, 'bulk'])->name('technologies.bulk');
    Route::post('technologies/bulk-store', [TechnologyController::class, 'bulkStore'])->name('technologies.bulk-store');
    Route::get('technologies/{technology}/edit', [TechnologyController::class, 'edit'])->name('technologies.edit');
    Route::put('technologies/{technology}', [TechnologyController::class, 'update'])->name('technologies.update');
    Route::patch('technologies/{technology}/quick', [TechnologyController::class, 'quickUpdate'])->name('technologies.quick-update');
    Route::delete('technologies/{technology}', [TechnologyController::class, 'destroy'])->name('technologies.destroy');

    Route::get('skills', [SkillController::class, 'index'])->name('skills.index');
    Route::get('skills/create', [SkillController::class, 'create'])->name('skills.create');
    Route::post('skills', [SkillController::class, 'store'])->name('skills.store');
    Route::post('skills/bulk', [SkillController::class, 'bulk'])->name('skills.bulk');
    Route::post('skills/bulk-store', [SkillController::class, 'bulkStore'])->name('skills.bulk-store');
    Route::get('skills/{skill}/edit', [SkillController::class, 'edit'])->name('skills.edit');
    Route::put('skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::patch('skills/{skill}/quick', [SkillController::class, 'quickUpdate'])->name('skills.quick-update');
    Route::delete('skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

    Route::get('about', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('about', [AboutController::class, 'update'])->name('about.update');

    Route::get('messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{contactMessage}', [AdminContactMessageController::class, 'show'])->name('messages.show');

    Route::get('contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::get('contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/post/{path?}', function (?string $path = null) {
    $path = trim((string) $path, '/');
    $path = match ($path) {
        '', 'dashboard' => 'dashboard',
        'profile-saya' => 'profile',
        'styleguide' => 'dashboard',
        default => $path,
    };

    return redirect()->to('/admin/'.$path, 301);
})->where('path', '.*');

require __DIR__.'/auth.php';
