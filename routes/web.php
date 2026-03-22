<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH WARGA
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| FRONTEND WARGA
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\HomeWargaController;
use App\Http\Controllers\Frontend\DataKeluargaController;
use App\Http\Controllers\Frontend\IplController;
use App\Http\Controllers\Frontend\BirokrasiController;
use App\Http\Controllers\Frontend\PengaduanController;
use App\Http\Controllers\Frontend\KeamananController;
use App\Http\Controllers\Frontend\LkRtController;
use App\Http\Controllers\Frontend\LkRwController;
use App\Http\Controllers\Frontend\LkRukemController;
use App\Http\Controllers\Frontend\KeluargaController;
use App\Http\Controllers\Frontend\WargaController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\Profile\DataPribadiController;
use App\Http\Controllers\Frontend\LayananApprovalController;
use App\Http\Controllers\Frontend\PengajuanPerubahanController;
use App\Http\Controllers\Frontend\Profile\SettingPasswordController;
use App\Http\Controllers\Frontend\TambahDataAnakController;
use App\Http\Controllers\Frontend\WargaUpdateController;
/*
|--------------------------------------------------------------------------
| MANAGEMENT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Management\LoginManagementController;
use App\Http\Controllers\Management\DashboardManagementController;
use App\Http\Controllers\Management\UserController;
use App\Http\Controllers\Management\RoleController;
use App\Http\Controllers\Management\WargaController as ManagementWargaController;
use App\Http\Controllers\Management\KartuKeluargaController;
use App\Http\Controllers\Management\MutasiWargaController;
use App\Http\Controllers\Management\IuranController;
use App\Http\Controllers\Management\KasController;
use App\Http\Controllers\Management\PengeluaranController;
use App\Http\Controllers\Management\LaporanKeuanganController;
use App\Http\Controllers\Management\SuratPengantarController;
use App\Http\Controllers\Management\SuratKeteranganController;
use App\Http\Controllers\Management\ArsipSuratController;
use App\Http\Controllers\Management\StatistikWargaController;
use App\Http\Controllers\Management\UnderConstructionController;

/*
|--------------------------------------------------------------------------
| HALAMAN SPLASH
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('frontend.splash'))->name('splash');


/*
|--------------------------------------------------------------------------
| AUTH WARGA
|--------------------------------------------------------------------------
*/

Route::middleware(['guest.rumah'])->group(function () {

    Route::get('/showlogin', [LoginController::class, 'showLogin'])->name('showlogin');
    Route::get('/showregister', [RegisterController::class, 'showRegister'])->name('showregister');
    Route::get('/forgotPassword', [ForgotPasswordController::class, 'ShowforgotPassword'])->name('forgotPassword');
    Route::get('/forgotPassword/check-unit',[ForgotPasswordController::class, 'showCheckUnit'])->name('forgotPassword.checkUnit');
    Route::get('/forgotPassword/new-password',[ForgotPasswordController::class, 'showNewPassword'])->name('forgotPassword.newPassword');
});

Route::middleware(['guest.rumah', 'login.limit'])->group(function () {

    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/forgotPassword/check-nik',[ForgotPasswordController::class, 'checkNik'])->name('forgotPassword.checkNik');
    Route::post('/verifikasi/check-unit',[ForgotPasswordController::class, 'verifikasiCheckUnit'])->name('forgotPassword.verifikasiCheckUnit');
    Route::post('/forgotPassword/save-password',[ForgotPasswordController::class, 'saveNewPassword'])->name('forgotPassword.savePassword');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/logout-all-devices/{id}',[LoginController::class, 'logoutAllDevices'])->name('logoutAllDevices');

/*
|--------------------------------------------------------------------------
| AREA WARGA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.rumah', 'check.approval', 'check.data'])->prefix('management')->group(function () {

        Route::get('/Home_warga', [HomeWargaController::class, 'HomeWarga'])->name('homeWarga');

        //------------------------------- TAMBAH DATA KELUARGA -------------------------------------//

        Route::get('/datakeluarga', [DataKeluargaController::class, 'index'])->name('Datakeluarga');
        Route::get('/tambah_beda_kk', [DataKeluargaController::class, 'tambahData_bedakk'])->name('TambahBeda.kk');
        Route::post('/store_beda_kk', [DataKeluargaController::class, 'store_beda_kk'])->name('storeBeda.kk');

        Route::get('/dataanak', [TambahDataAnakController::class, 'index'])->name('tamabhDataAnak');
        Route::post('/store/dataanak', [TambahDataAnakController::class, 'store'])->name('store.DataAnak');

        //------------------------------- EDIT DATA WARGA -------------------------------------//
        Route::put('/warga/update-selfie/{id}', [WargaUpdateController::class, 'updateSelfie'])->name('warga.updateSelfie');
        Route::post('/pengajuan-perubahan/store',[PengajuanPerubahanController::class,'store'])->name('pengajuan.perubahan.store');

                //-------------------------END EDIT DATA WARGA --------------------------//
        //------------------------------- END TAMBAH DATA KELUARGA -------------------------------------//

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
    | PROFIL
    */

        Route::prefix('profil')->group(function () {

            Route::get('/', [ProfileController::class, 'index'])->name('profil');
            Route::get('/data-pribadi',[DataPribadiController::class, 'index'])->name('profil.dataPribadi');
            Route::get('/setting-password',[SettingPasswordController::class, 'index'])->name('setting.password');
            Route::post('/verify_nik',[SettingPasswordController::class, 'verify_data'])->name('data.Verify');
            Route::get('/password_baru',[SettingPasswordController::class, 'password_baru'])->name('password.baru');
            Route::post('/simpan-password',[SettingPasswordController::class, 'simpan_password'])->name('password.simpan');


        });


        /*
    | DATA WARGA
    */

        Route::get('/keluarga/create', [KeluargaController::class, 'create'])->name('keluarga.create');
        Route::post('/keluarga/store', [KeluargaController::class, 'store'])->name('keluarga.store');
        Route::get('/warga/create/{keluarga_id?}', [WargaController::class, 'create'])->name('warga.create');
        Route::post('/warga/store', [WargaController::class, 'store'])->name('warga.store');
        /*
    | PERSETUJUAN LAYANAN
    */
        Route::post('/setuju-layanan',[LayananApprovalController::class, 'setuju'])->name('setujuLayanan');
    });


/*
|--------------------------------------------------------------------------
| AUTH MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::get('/pengurus', fn() => view('backend.management.splash_management'))->name('splash_management');
Route::get('/showlogin_management',[LoginManagementController::class, 'showLogin_management'])->middleware('redirect.management')->name('showlogin_management');
Route::post('/management/login',[LoginManagementController::class, 'login_management'])->name('management.login.process');
Route::post('/management/verify-otp',[LoginManagementController::class, 'verifyOtp'])->name('management.verify.otp');


/*
|--------------------------------------------------------------------------
| AREA MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::prefix('management')->middleware(['check.device', 'prevent.back'])->group(function () {

        /*
        |------------------------------------------------
        | DASHBOARD
        |------------------------------------------------
        */
        Route::get('/dashboard', [DashboardManagementController::class, 'index'])->name('management.dashboard');
        Route::get('/dashboard/statistik-warga', [StatistikWargaController::class, 'index'])->name('management.dashboard.statistik_warga')->defaults('title', 'Statistik Warga');
        Route::get('/dashboard/statistik-keuangan', [UnderConstructionController::class, 'index'])->name('management.dashboard.statistik_keuangan')->defaults('title', 'Statistik Keuangan');
        Route::get('/dashboard/grafik-iuran', [UnderConstructionController::class, 'index'])->name('management.dashboard.grafik_iuran')->defaults('title', 'Grafik Iuran');
        /*
        |------------------------------------------------
        | USER MANAGEMENT
        |------------------------------------------------
        */
        Route::get('/users', [UnderConstructionController::class, 'index'])->name('management.users.index')->defaults('title', 'Admin Management');
        Route::get('/roles', [UnderConstructionController::class, 'index'])->name('management.roles.index')->defaults('title', 'Role Permission');
        /*
        |------------------------------------------------
        | DATA WARGA
        |------------------------------------------------
        */
        Route::get('/warga', [UnderConstructionController::class, 'index'])->name('management.warga.index')->defaults('title', 'Data Warga');
        Route::get('/kartu-keluarga', [UnderConstructionController::class, 'index'])->name('management.kk.index')->defaults('title', 'Data Kartu Keluarga');
        Route::get('/mutasi-warga', [UnderConstructionController::class, 'index'])->name('management.mutasi.index')->defaults('title', 'Mutasi Warga');
        /*
        |------------------------------------------------
        | KEUANGAN
        |------------------------------------------------
        */
        Route::get('/iuran', [UnderConstructionController::class, 'index'])->name('management.iuran.index')->defaults('title', 'Iuran Bulanan');
        Route::get('/kas', [UnderConstructionController::class, 'index'])->name('management.kas.index')->defaults('title', 'Kas RW');
        Route::get('/pengeluaran', [UnderConstructionController::class, 'index'])->name('management.pengeluaran.index')->defaults('title', 'Pengeluaran');
        Route::get('/laporan-keuangan', [UnderConstructionController::class, 'index'])->name('management.laporan.keuangan')->defaults('title', 'Laporan Keuangan');
        /*
        |------------------------------------------------
        | SURAT
        |------------------------------------------------
        */
        Route::get('/surat-pengantar', [UnderConstructionController::class, 'index'])->name('management.surat_pengantar.index')->defaults('title', 'Surat Pengantar');
        Route::get('/surat-keterangan', [UnderConstructionController::class, 'index'])->name('management.surat_keterangan.index')->defaults('title', 'Surat Keterangan');
        Route::get('/arsip-surat', [UnderConstructionController::class, 'index'])->name('management.arsip_surat.index')->defaults('title', 'Arsip Surat');
        /*
        |------------------------------------------------
        | LOGOUT
        |------------------------------------------------
        */
        Route::post('/logout', [LoginManagementController::class, 'logout_management'])->name('management.logout');
    });
