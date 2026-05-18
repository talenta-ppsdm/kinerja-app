<?php

use App\Http\Controllers\SkpEvaluasiController;
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

// Menuju halaman Pintasan
Route::get('/pintasan', [SkpPintasanController::class, 'index'])->name('pintasan.index');

