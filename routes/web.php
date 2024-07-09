<?php

use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengukuranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\RemajaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RiwayatController;
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
    return redirect()->route('login');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::resource('remaja', RemajaController::class);
    Route::resource('petugas', PetugasController::class);
    Route::resource('pengukuran', PengukuranController::class);
    Route::resource('konsultasi', KonsultasiController::class);
    Route::post('konsultasi-messages/{id}/reply', [KonsultasiController::class, 'reply'])->name('konsultasi-messages.reply');
    Route::resource('artikel', ArtikelController::class);
    Route::resource('users', UserController::class);
    Route::get('/riwayat-pengukuran', [PengukuranController::class, 'riwayatPengukuran'])->name('riwayat.pengukuran');
    Route::get('/fetch-remaja-data/{id}', [RemajaController::class, 'fetchData']);
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::post('/pengukuran/hitung', [PengukuranController::class, 'hitungStatusGizi'])->name('pengukuran.hitung');
    Route::get('/markAsRead', [NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::get('/cetak-pdf', [PengukuranController::class, 'cetakPDF'])->name('cetakPDF');
    Route::get('/export-excel', [PengukuranController::class, 'exportExcel'])->name('exportExcel');
    Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');
    Route::get('profile', [RemajaController::class, 'profile'])->name('profile');
    Route::put('/profile/{id}', [RemajaController::class, 'profileUpdate'])->name('profile.update');
});
