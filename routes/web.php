<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KdmpSurveyController;
use App\Http\Controllers\MasyarakatSurveyController;
use App\Http\Controllers\SppgSurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard with new controller
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])
    ->middleware(['auth'])
    ->name('dashboard.chart-data');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management routes (admin only)
    Route::resource('users', UserController::class)->except(['show']);

    // KDMP Survey routes
    Route::resource('kdmp', KdmpSurveyController::class);
    Route::get('kdmp/{kdmp}/pdf', [KdmpSurveyController::class, 'exportPdf'])->name('kdmp.pdf');

    // Masyarakat Survey routes
    Route::resource('masyarakat', MasyarakatSurveyController::class);

    // SPPG Survey routes
    Route::resource('sppg', SppgSurveyController::class);
});

require __DIR__ . '/auth.php';

