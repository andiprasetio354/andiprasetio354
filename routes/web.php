<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::view('/about','about');
Route::view('/projects','projects');
Route::view('/resume','resume');

// Public contact form routes
Route::get('/contact', [ContactController::class, 'showForm']);
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes for managing projects and contact messages (authenticated)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::get('/contact', [ContactController::class, 'adminIndex'])->name('contact.index');
    Route::post('/contact/{message}/read', [ContactController::class, 'markRead'])->name('contact.read');
    Route::delete('/contact/{message}', [ContactController::class, 'destroy'])->name('contact.destroy');
});

require __DIR__.'/auth.php';
