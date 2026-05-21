<?php

use App\Http\Controllers\SkpEvaluasiController;
use App\Http\Controllers\SkpPenyusunanController;
use App\Http\Controllers\SkpPintasanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Menuju halaman Monitoring Evaluasi
// Route::get('/monitoring-evaluasi', function () {
//     return view('monitoring_evaluasi');
// })->name('evaluasi.index');

Route::get('/monitoring-evaluasi', [SkpEvaluasiController::class, 'index'])->name('evaluasi.index');
Route::post('import/evaluasi-skp', [SkpEvaluasiController::class, 'import'])->name('import.evaluasi');
Route::get('/evaluasi/{id}/edit', [SkpEvaluasiController::class, 'edit'])->name('evaluasi.edit');
Route::put('/evaluasi/{id}', [SkpEvaluasiController::class, 'update'])->name('evaluasi.update');

// Menuju halaman Monitoring Penyusunan
Route::get('/monitoring-penyusunan', [SkpPenyusunanController::class, 'index'])->name('penyusunan.index');
Route::post('/import/penyusunan-skp', [SkpPenyusunanController::class, 'import'])->name('import.penyusunan');

// Menuju halaman Pintasan
Route::get('/pintasan', [SkpPintasanController::class, 'index'])->name('pintasan.index');

