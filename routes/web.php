<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KdmpSurveyController;
use App\Http\Controllers\MasyarakatSurveyController;
use App\Http\Controllers\SppgSurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationScoreController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProgresFisikController;
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
    
    // Produksi records (laporan berkala per KDMP)
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('/', [ProduksiController::class, 'index'])->name('index');
        Route::get('/pdf', [ProduksiController::class, 'exportPdf'])->name('pdf');
        Route::get('/excel', [ProduksiController::class, 'exportExcel'])->name('excel');
        Route::get('/create', [ProduksiController::class, 'create'])->name('create');
        Route::post('/', [ProduksiController::class, 'store'])->name('store');
        Route::get('/kdmp/{kdmp}/pdf', [ProduksiController::class, 'exportPdfDetail'])->name('pdf-detail');
        Route::get('/kdmp/{monitoring}', [ProduksiController::class, 'show'])->name('show');
        Route::get('/{monitoring}/edit', [ProduksiController::class, 'edit'])->name('edit');
        Route::put('/{monitoring}', [ProduksiController::class, 'update'])->name('update');
        Route::delete('/{monitoring}', [ProduksiController::class, 'destroy'])->name('destroy');
    });
    
    // Progres Fisik routes
    Route::prefix('progres-fisik')->name('progres-fisik.')->group(function () {
        Route::get('/', [ProgresFisikController::class, 'index'])->name('index');
        Route::get('/pdf', [ProgresFisikController::class, 'exportPdf'])->name('pdf');
        Route::get('/create', [ProgresFisikController::class, 'create'])->name('create');
        Route::post('/', [ProgresFisikController::class, 'store'])->name('store');
        Route::get('/kdmp/{kdmp}', [ProgresFisikController::class, 'show'])->name('show');
        Route::get('/kdmp/{kdmp}/pdf', [ProgresFisikController::class, 'exportPdfDetail'])->name('pdf-detail');
        Route::get('/{record}/edit', [ProgresFisikController::class, 'edit'])->name('edit');
        Route::put('/{record}', [ProgresFisikController::class, 'update'])->name('update');
        Route::delete('/{record}', [ProgresFisikController::class, 'destroy'])->name('destroy');
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

