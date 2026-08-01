<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
Route::post('/kuesioner', [KuesionerController::class, 'store'])->name('kuesioner.store');

// --- AUTH LOGIN ADMIN ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- EKSPOR EXCEL (PUBLIK, TANPA LOGIN) ---
Route::get('/export-kuesioner-excel', [KuesionerController::class, 'exportExcel'])->name('kuesioner.export');

// --- ROUTE ADMIN (WAJIB LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard-kurva', [KuesionerController::class, 'dashboard'])->name('kuesioner.dashboard');
    Route::post('/admin/alumni/import', [KuesionerController::class, 'import'])->name('alumni.import');
});
