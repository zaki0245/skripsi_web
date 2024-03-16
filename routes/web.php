<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SPKSahamController;
use App\Http\Controllers\KriteriaController;

Route::get('/data', [SPKSahamController::class, 'index'])->name('data.index');
Route::get('/perhitungan', [SPKSahamController::class, 'hitung'])->name('perhitungan');
Route::get('/evaluasi', [SPKSahamController::class, 'evaluasi'])->name('evaluasi');
Route::put('/update-bobot/{id}', [KriteriaController::class, 'updateBobot'])->name('update.bobot');


