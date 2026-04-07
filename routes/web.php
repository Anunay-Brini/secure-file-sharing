<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FileController;
use App\Http\Controllers\ShareLinkController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [FileController::class, 'index'])->name('dashboard');
    Route::post('/files', [FileController::class, 'store'])->name('files.store');
    Route::get('/files/{file}', [FileController::class, 'show'])->name('files.show');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');

    Route::post('/shares', [ShareLinkController::class, 'store'])->name('shares.store');
    Route::delete('/shares/{shareLink}', [ShareLinkController::class, 'revoke'])->name('shares.revoke');
});

Route::get('/s/{uuid}', [ShareLinkController::class, 'show'])->name('shares.show');
Route::get('/s/{uuid}/download', [ShareLinkController::class, 'download'])->name('shares.download');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
