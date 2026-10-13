<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
 * Generic entry point (used by Breeze redirects): sends each user to their role's dashboard.
 */
Route::get('/dashboard', function (Request $request) {
    return redirect()->route($request->user()->dashboardRoute());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::view('/dashboard', 'student.dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:counselor'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::view('/dashboard', 'counselor.dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified', 'role:director'])->prefix('director')->name('director.')->group(function () {
    Route::view('/dashboard', 'director.dashboard')->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
