<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadSignalController;
use App\Http\Controllers\SavedSignalController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SignalController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\TodayController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/today', TodayController::class)->name('today');
    Route::get('/signals/{signal:slug}', [SignalController::class, 'show'])->name('signals.show');
    Route::post('/signals/{signal:slug}/save', [SavedSignalController::class, 'store'])->name('signals.save');
    Route::delete('/signals/{signal:slug}/save', [SavedSignalController::class, 'destroy'])->name('signals.unsave');
    Route::post('/signals/{signal:slug}/read', [ReadSignalController::class, 'store'])->name('signals.read');
    Route::get('/saved', [SavedSignalController::class, 'index'])->name('saved');
    Route::get('/sources', SourceController::class)->name('sources');
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::put('/settings/preferences', [SettingsController::class, 'update'])->name('settings.preferences.update');

    Route::get('/dashboard', fn () => redirect()->route('today'))->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
