<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root → lobby
Route::get('/', function () {
    return redirect('/lobby');
});

// Authenticated pages
Route::middleware('auth')->group(function () {

    // Lobby page
    Route::get('/lobby', function () {
        return Inertia::render('Lobby');
    })->name('lobby');

    // Match page
    Route::get('/matches/{match}', function ($matchId) {
        return Inertia::render('MatchShow', [
            'matchId' => $matchId,
        ]);
    })->name('matches.show');

    // Profile pages
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes
require __DIR__ . '/auth.php';