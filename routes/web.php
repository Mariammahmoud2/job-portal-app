<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationsController;
use App\Http\Controllers\JobController;
use Gemini\Laravel\Facades\Gemini;


Route::get('/', function () {
    return view('welcome');
});

 Route::middleware(['auth', 'role:job-seeker'])->group(function () {
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/job-applications', [JobApplicationsController::class, 'index'])->name('job-applications.index');
    Route::get('/job-applications/{application}', [JobApplicationsController::class, 'show'])->name('applications.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{job}/apply', [JobController::class, 'showApplyForm'])->name('jobs.apply.form');
Route::post('/jobs/{job}/apply', [JobController::class, 'submitApplication'])->name('jobs.apply.submit');

});
 
 
   
 require __DIR__.'/auth.php';
