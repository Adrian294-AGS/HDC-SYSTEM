<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CumulativeRecordController;
use App\Support\Form5;
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
    Route::get('/dashboard', function (Request $request) {
        return view('student.dashboard', ['record' => $request->user()->latestCumulativeRecord]);
    })->name('dashboard');

    // Form 5: Student Cumulative Record wizard
    Route::get('/record', [CumulativeRecordController::class, 'index'])->name('record.index');
    Route::get('/record/start', [CumulativeRecordController::class, 'create'])->name('record.create');
    Route::post('/record', [CumulativeRecordController::class, 'store'])->name('record.store');
    Route::get('/records/{record}', [CumulativeRecordController::class, 'show'])->name('record.show');
    Route::get('/records/{record}/photo', [CumulativeRecordController::class, 'photo'])->name('record.photo');
    Route::get('/records/{record}/steps/{step}', [CumulativeRecordController::class, 'edit'])
        ->whereIn('step', array_keys(Form5::STEPS))
        ->name('record.edit');
    Route::put('/records/{record}/steps/{step}', [CumulativeRecordController::class, 'update'])
        ->whereIn('step', array_keys(Form5::STEPS))
        ->name('record.update');
    Route::post('/records/{record}/submit', [CumulativeRecordController::class, 'submit'])->name('record.submit');
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
});

require __DIR__.'/auth.php';
