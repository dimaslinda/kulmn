<?php

use App\Http\Controllers\GeneralControlllers;
use Illuminate\Support\Facades\Route;

Route::get('/', [GeneralControlllers::class, 'index'])->name('index');
Route::get('/mitra', [GeneralControlllers::class, 'mitra'])->name('mitra');
