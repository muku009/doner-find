<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonorController;


Route::get('/', [DonorController::class, 'index'])->name('donors.index');
Route::post('/donors/store', [DonorController::class, 'store'])->name('donors.store');
Route::post('/donors/search', [DonorController::class, 'search'])->name('donors.search');