<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Application\ApplicationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [GeneralController::class, 'getHomePage']);
Route::get('/welcome', [GeneralController::class, 'getWelcomePage'])->name('welcome');

// General Queries
Route::prefix('application')->group(function () {
    Route::get('/department', [ApplicationController::class, 'getDepartmentPage'])->name('department');
    Route::get('/mode-of-study', [ApplicationController::class, 'getModeOfStudyPage'])->name('mode.of.study');

});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
