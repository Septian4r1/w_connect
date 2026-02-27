<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\HomeWargaController;
use App\Http\Controllers\Frontend\KeluargaController;
use App\Http\Controllers\Frontend\LayananApprovalController;
use App\Http\Controllers\Frontend\Profile\DataPribadiController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\WargaController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('frontend.splash');
})->name('splash');

// ================= LOGIN =================
Route::middleware('guest.rumah')->group(function () {
    Route::get('/showlogin', [LoginController::class, 'showLogin'])->name('showlogin');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/showregister', [RegisterController::class, 'showRegister'])->name('showregister');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

// ================= LOGOUT =================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout-all-devices/{id}', [LoginController::class, 'logoutAllDevices'])->name('logoutAllDevices');

// ================= WARGA =================
Route::middleware(['auth.rumah', 'check.approval', 'check.data'])->prefix('management')->group(function () {
    Route::get('/Home_warga', [HomeWargaController::class, 'HomeWarga'])->name('homeWarga');
    Route::get('/berita', fn() => view('frontend.berita'))->name('berita');
    Route::get('/kontak', fn() => view('frontend.kontak'))->name('kontak');

    /*
    =====================
    PROFIL
    =====================
    */
    Route::prefix('profil')->group(function () {

        Route::get('/', [ProfileController::class, 'index'])->name('profil');
        Route::get('/data-pribadi', [DataPribadiController::class, 'index'])->name('profil.dataPribadi');

    });

    // Route untuk menyimpan persetujuan layanan
    Route::post('/setuju-layanan', [LayananApprovalController::class, 'setuju'])->name('setujuLayanan');

    // Route untuk menyimpan data warga
    Route::get('/keluarga/create', [KeluargaController::class, 'create'])->name('keluarga.create');
    Route::post('/keluarga/store', [KeluargaController::class, 'store'])->name('keluarga.store');
    Route::get('/warga/create', [WargaController::class, 'create'])->name('warga.create');
    Route::post('/warga/store', [WargaController::class, 'store'])->name('warga.store');
});
