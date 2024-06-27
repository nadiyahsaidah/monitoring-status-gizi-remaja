<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\PengukuranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RemajaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::resource('remaja', RemajaController::class);
Route::resource('petugas', PetugasController::class);
Route::resource('pengukuran', PengukuranController::class);
Route::resource('konsultasi', KonsultasiController::class);
Route::resource('artikel', ArtikelController::class);
Route::resource('users', UserController::class);
Route::get('/fetch-remaja-data/{id}', [RemajaController::class, 'fetchData']);
Route::get('/home', [DashboardController::class, 'index'])->name('home');
Route::post('/pengukuran/hitung', [PengukuranController::class, 'hitungStatusGizi'])->name('pengukuran.hitung');

