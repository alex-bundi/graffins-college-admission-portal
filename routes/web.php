<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\CourseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', [GeneralController::class, 'getHomePage']);
Route::get('/welcome', [GeneralController::class, 'getWelcomePage'])->name('welcome');
Route::get('/registration-status', [GeneralController::class, 'getRegistrationStatus'])->name('registration.status');

Route::middleware('guest')->group(function () {
    Route::get('register', [GeneralController::class, 'getRegisterPage'])
        ->name('register');

    Route::post('post-register', [GeneralController::class, 'postRegistration']);

    Route::get('login', [GeneralController::class, 'getLoginPage'])
        ->name('login');

    Route::post('/post-login', [GeneralController::class, 'postLogin']);

 
});

Route::get('/dashboard', [GeneralController::class, 'getDashboard'])->name('dashboard');
Route::get('/apply-course', [CourseController::class, 'getCourseList'])->name('course.list');

Route::prefix('application')->group(function () {
    Route::get('/department', [ApplicationController::class, 'getDepartmentPage'])->name('department');
    Route::get('/mode-of-study', [ApplicationController::class, 'getModeOfStudyPage'])->name('mode.of.study');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
