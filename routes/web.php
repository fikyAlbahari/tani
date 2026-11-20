<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalTanamController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PemupukanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PenyemprotanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('jadwal', JadwalTanamController::class)->parameters(['jadwal' => 'jadwal'])->except('show');
    Route::resource('pemupukan', PemupukanController::class)->except('show');
    Route::resource('penyemprotan', PenyemprotanController::class)->except('show');
    Route::resource('biaya', BiayaController::class)->except('show');
    Route::resource('penjualan', PenjualanController::class)->except('show');
});
