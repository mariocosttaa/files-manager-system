<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', [FileController::class, 'index'])->name('files.index');
Route::post('/files', [FileController::class, 'store'])->name('files.store');
Route::get('/download/{file}', [FileController::class, 'show'])->name('files.show');
Route::get('/download/{file}/content', [FileController::class, 'download'])->name('files.download');
