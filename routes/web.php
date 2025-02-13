<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\HubunganInternasional;
use App\Http\Controllers\IlmuKomunikasi;
use App\Http\Controllers\IlmuPemerintahan;

Auth::routes();

Route::get('/', [Dashboard::class, 'index'])->middleware('auth');
Route::get('/hubungan_internasional', [HubunganInternasional::class, 'index'])->middleware('auth');
Route::get('/ilmu_komunikasi', [IlmuKomunikasi::class, 'index'])->middleware('auth');
Route::get('/ilmu_pemerintahan', [IlmuPemerintahan::class, 'index'])->middleware('auth');
Route::get('/upload', [Dashboard::class, 'upload'])->middleware('auth');
Route::post('/upload', [Dashboard::class, 'InsertData'])->middleware('auth');
Route::post('/InsertNewData', [Dashboard::class, 'InsertNewData'])->middleware('auth');
Route::post('/DeleteNewData', [Dashboard::class, 'DeleteNewData'])->middleware('auth');
Route::post('/DeleteOnceNewData', [Dashboard::class, 'DeleteOnceNewData'])->middleware('auth');
