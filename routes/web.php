<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KuesionerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kuesioner', [KuesionerController::class, 'index'])->name('kuesioner.index');
Route::post('/kuesioner', [KuesionerController::class, 'store'])->name('kuesioner.store');
Route::get('/dashboard-kurva', [KuesionerController::class, 'dashboard'])->name('kuesioner.dashboard');
Route::get('/export-kuesioner-excel', [App\Http\Controllers\KuesionerController::class, 'exportExcel'])->name('kuesioner.export');
Route::post('/admin/alumni/import', [kuesionerController::class, 'import'])->name('alumni.import');