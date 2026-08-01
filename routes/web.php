<?php

use App\Http\Controllers\SeraController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SeraController::class, 'index'])->name('sera.index');
Route::post('/report', [SeraController::class, 'generate'])->name('sera.generate');
