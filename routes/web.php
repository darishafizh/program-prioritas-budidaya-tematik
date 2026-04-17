<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KdmpSurveyController;
use App\Http\Controllers\MasyarakatSurveyController;
use App\Http\Controllers\SppgSurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationScoreController;
use App\Http\Controllers\MonitoringController;
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

Route::get('/dashboard/filter-data', [DashboardController::class, 'filterData'])
    ->middleware(['auth'])
    ->name('dashboard.filter-data');

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
    
    // Monitoring records (laporan berkala per KDMP)
    Route::prefix('lokasi-budidaya')->name('monitoring.')->group(function () {
        Route::get('/', [MonitoringController::class, 'index'])->name('index');
        Route::get('/pdf', [MonitoringController::class, 'exportPdf'])->name('pdf');
        Route::get('/create', [MonitoringController::class, 'create'])->name('create');
        Route::post('/', [MonitoringController::class, 'store'])->name('store');
        Route::get('/kdmp/{kdmp}/pdf', [MonitoringController::class, 'exportPdfDetail'])->name('pdf-detail');
        Route::get('/kdmp/{monitoring}', [MonitoringController::class, 'show'])->name('show');
        Route::get('/{monitoring}/edit', [MonitoringController::class, 'edit'])->name('edit');
        Route::put('/{monitoring}', [MonitoringController::class, 'update'])->name('update');
        Route::delete('/{monitoring}', [MonitoringController::class, 'destroy'])->name('destroy');
    });
    
    // Scoring Dashboard routes
    Route::prefix('scoring')->name('scoring.')->group(function () {
        Route::get('/', [LocationScoreController::class, 'index'])->name('index');
        Route::get('/export', [LocationScoreController::class, 'export'])->name('export');
        Route::post('/calculate', [LocationScoreController::class, 'calculate'])->name('calculate');
        Route::post('/recalculate-all', [LocationScoreController::class, 'recalculateAll'])->name('recalculate-all');
        Route::post('/generate', [LocationScoreController::class, 'generateFromSurveys'])->name('generate');
        Route::get('/{locationScore}', [LocationScoreController::class, 'show'])->name('show');
    });
});


require __DIR__.'/auth.php';

