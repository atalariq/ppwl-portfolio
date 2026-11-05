<?php

use App\Http\Controllers\CategoryController;
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
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::prefix('post')->name('post.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/images', [ProjectGalleryController::class, 'store'])->name('projects.images.store');
    Route::put('projects/{project}/images/{image}', [ProjectGalleryController::class, 'update'])->name('projects.images.update');
    Route::delete('projects/{project}/images/{image}', [ProjectGalleryController::class, 'destroy'])->name('projects.images.destroy');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('technologies', TechnologyController::class)->except(['show']);
    Route::resource('skills', SkillController::class)->except(['show']);
});

Route::prefix('post')->name('post.')->middleware('auth')->group(function () {
    Route::get('profile-saya', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile-saya', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
