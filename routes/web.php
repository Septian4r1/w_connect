<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Frontend\BirokrasiController;
use App\Http\Controllers\Frontend\DataKeluargaController;
use App\Http\Controllers\Frontend\HomeWargaController;
use App\Http\Controllers\Frontend\IplController;
use App\Http\Controllers\Frontend\KeamananController;
use App\Http\Controllers\Frontend\KeluargaController;
use App\Http\Controllers\Frontend\LayananApprovalController;
use App\Http\Controllers\Frontend\LkRtController;
use App\Http\Controllers\Frontend\LkRukemController;
use App\Http\Controllers\Frontend\LkRwController;
use App\Http\Controllers\Frontend\PengaduanController;
use App\Http\Controllers\Frontend\Profile\DataPribadiController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\WargaController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('frontend.splash');
})->name('splash');

// ================= LOGIN =================
// GET login / register / forgotPassword → tidak pakai rate limiter
Route::middleware(['guest.rumah'])->group(function () {
    Route::get('/showlogin', [LoginController::class, 'showLogin'])->name('showlogin');
    Route::get('/showregister', [RegisterController::class, 'showRegister'])->name('showregister');
    Route::get('/forgotPassword', [ForgotPasswordController::class, 'ShowforgotPassword'])->name('forgotPassword');
    Route::get('/forgotPassword/check-unit', [ForgotPasswordController::class, 'showCheckUnit'])->name('forgotPassword.checkUnit');
    Route::get('/forgotPassword/new-password', [ForgotPasswordController::class, 'showNewPassword'])->name('forgotPassword.newPassword');
});

// POST login / register / forgotPassword → pakai rate limiter
Route::middleware(['guest.rumah', 'rate.limiter'])->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/forgotPassword/check-nik', [ForgotPasswordController::class, 'checkNik'])->name('forgotPassword.checkNik');
    Route::post('/verifikasi/check-unit', [ForgotPasswordController::class, 'verifikasiCheckUnit'])->name('forgotPassword.verifikasiCheckUnit');
    Route::post('/forgotPassword/save-password', [ForgotPasswordController::class, 'saveNewPassword'])->name('forgotPassword.savePassword');
});

// ================= LOGOUT =================
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout-all-devices/{id}', [LoginController::class, 'logoutAllDevices'])->name('logoutAllDevices');

// ================= WARGA =================
Route::middleware(['auth.rumah', 'check.approval', 'check.data'])->prefix('management')->group(function () {
    Route::get('/Home_warga', [HomeWargaController::class, 'HomeWarga'])->name('homeWarga');
    // ================= MENU UTAMA =================
    Route::get('/datakeluarga', [DataKeluargaController::class, 'index'])->name('Datakeluarga');
    Route::get('/ipl', [IplController::class, 'index'])->name('ipl');
    Route::get('/birokrasi', [BirokrasiController::class, 'index'])->name('birokrasi');
    Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan');
    Route::get('/keamanan', [KeamananController::class, 'index'])->name('keamanan');
    Route::get('/lk-rt', [LkRtController::class, 'index'])->name('lk.rt');
    Route::get('/lk-rw', [LkRwController::class, 'index'])->name('lk.rw');
    Route::get('/lk-rukem', [LkRukemController::class, 'index'])->name('lk.rukem');




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
