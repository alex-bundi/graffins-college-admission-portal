<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Application\ApplicationController;
use App\Http\Controllers\Application\BioDataController;
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
    
    Route::get('/mode-of-study', [ApplicationController::class, 'getModeOfStudyPage'])->name('mode.of.study');
    Route::post('/post-mode-of-study', [ApplicationController::class, 'postModeOfStudy'])->name('post.mode.of.study');

    Route::get('/department', [ApplicationController::class, 'getDepartmentPage'])->name('department');
    Route::post('/post-department', [ApplicationController::class, 'postDepartment'])->name('post.department');

    Route::get('/pick-course', [ApplicationController::class, 'getPickCoursePage'])->name('pick.course');
    Route::post('/post-pick-course', [ApplicationController::class, 'postPickCourse'])->name('post.pick.course');

    Route::get('/course-type', [ApplicationController::class, 'getCourseTypePage'])->name('course-type');
    Route::post('/post-course-type', [ApplicationController::class, 'postCourseType'])->name('post.course-type');

    Route::get('/class-start-date', [ApplicationController::class, 'getClassStartDatePage'])->name('class.start.date');
    Route::post('/post-class-start-date', [ApplicationController::class, 'postClassStartDate'])->name('post.class-start-date');

    Route::get('/class-start-time', [ApplicationController::class, 'getClassStartTimePage'])->name('class.start.time');
    Route::post('/post-class-start-time', [ApplicationController::class, 'postClassStartTime'])->name('post.class.start.time');

    Route::get('/course-summary', [ApplicationController::class, 'getCourseSummmary'])->name('course.summary');
    Route::post('/post-course-summary', [ApplicationController::class, 'postCourseSummmary'])->name('post.course.summary');

    Route::get('/start-bio-data', [ApplicationController::class, 'getStartBioData'])->name('start.bio.data');


    Route::get('/full-name', [BioDataController::class, 'getFullNamePage'])->name('full.name');
    Route::post('/post-full-name', [BioDataController::class, 'postFullNamePage'])->name('post.full.name');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
