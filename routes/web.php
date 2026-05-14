<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts/mainLayouts');
});

// Menuju halaman Rekapitulasi Data
Route::get('/olah_data', function () {
    return view('olah_data');
})->name('data.index');

// Menuju halaman Pintasan
Route::get('/pintasan', function () {
    return view('pintasan');
})->name('pintasan.index');
