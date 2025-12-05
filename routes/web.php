<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [FileController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // File Manager Routes (Protected)
    Route::post('/files', [FileController::class, 'store'])
        ->middleware('throttle:10,1') // 10 uploads per minute
        ->name('files.store');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    // Note: files.index is now the dashboard
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
});

// Public Download Routes (Protected by signed URLs)
Route::get('/files/{file}', [FileController::class, 'show'])
    ->middleware('signed')
    ->name('files.show');
Route::get('/download/{file}', [FileController::class, 'download'])
    ->middleware('signed')
    ->name('files.download');

require __DIR__.'/auth.php';
