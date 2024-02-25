<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SPKSahamController;

Route::get('/data', [SPKSahamController::class, 'index'])->name('data.index');
Route::get('/perhitungan', [SPKSahamController::class, 'hitung'])->name('perhitungan');
Route::get('/evaluasi', [SPKSahamController::class, 'evaluasi'])->name('evaluasi');
