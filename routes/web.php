<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KuesionerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\RecoveryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
Route::post('/kuesioner', [KuesionerController::class, 'store'])->name('kuesioner.store');

// --- AUTH LOGIN ADMIN ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- PEMULIHAN PASSWORD SUPER ADMIN (PUBLIK, DIBATASI THROTTLE) ---
Route::get('/pemulihan', [RecoveryController::class, 'index'])->name('pemulihan.index');
Route::post('/pemulihan', [RecoveryController::class, 'reset'])->middleware('throttle:5,15')->name('pemulihan.reset');

// --- EKSPOR EXCEL (PUBLIK, TANPA LOGIN) ---
Route::get('/export-kuesioner-excel', [KuesionerController::class, 'exportExcel'])->name('kuesioner.export');

// --- ROUTE ADMIN (WAJIB LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard-kurva', [KuesionerController::class, 'dashboard'])->name('kuesioner.dashboard');
    Route::post('/admin/alumni/import', [KuesionerController::class, 'import'])->name('alumni.import');
    Route::get('/admin/pengaturan', [SettingsController::class, 'index'])->name('pengaturan.index');
    Route::post('/admin/pengaturan', [SettingsController::class, 'update'])->name('pengaturan.update');
    Route::get('/admin/akun', [AccountController::class, 'index'])->name('akun.index');
    Route::post('/admin/akun', [AccountController::class, 'store'])->name('akun.store');
    Route::post('/admin/foto', [AccountController::class, 'uploadFoto'])->name('foto.upload');
    Route::post('/admin/nama', [AccountController::class, 'updateNama'])->name('nama.update');
    Route::post('/admin/akun/kode-pemulihan', [AccountController::class, 'simpanKodePemulihan'])->name('akun.kodePemulihan');
    Route::post('/admin/akun/ganti-password', [AccountController::class, 'gantiPassword'])->name('akun.gantiPassword');
    Route::post('/admin/akun/{user}/super', [AccountController::class, 'toggleSuper'])->name('akun.super');
    Route::post('/admin/akun/{user}/reset', [AccountController::class, 'reset'])->name('akun.reset');
    Route::post('/admin/akun/{user}/hapus', [AccountController::class, 'hapus'])->name('akun.hapus');
});
