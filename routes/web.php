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
use App\Http\Controllers\Frontend\PesanWargaController;
use App\Http\Controllers\Frontend\Profile\SettingPasswordController;
use App\Http\Controllers\Frontend\TambahDataAnakController;
use App\Http\Controllers\Frontend\WargaUpdateController;
use App\Http\Controllers\Management\AreaManagementController;
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
use App\Http\Controllers\Management\ForgotPasswordController as ManagementForgotPasswordController;
use App\Http\Controllers\Management\ManagementSettingPasswordController;
use App\Http\Controllers\Management\ManagementTambahDataBedaKKController;
use App\Http\Controllers\Management\ManagementTambahDataSatukkController;
use App\Http\Controllers\Management\ManagementTambahKeluargaBedaKKController;
use App\Http\Controllers\Management\ManagementTambahKeluargaController;
use App\Http\Controllers\Management\ManagementTambahWargaController;
use App\Http\Controllers\Management\Menus\MenuController;
use App\Http\Controllers\Management\Menus\RolePermissionsController;
use App\Http\Controllers\Management\MManagementTambahDataWargaController;
use App\Http\Controllers\Management\PengurusController;
use App\Http\Controllers\Management\Roles\ManagementRolseController;
use App\Http\Controllers\Management\RumahDanKK\ManagementRumahDanKK;
use App\Http\Controllers\Management\RWController;
use App\Http\Controllers\Management\StatistikWargaController;
use App\Http\Controllers\Management\StruktureManagementController;
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
    Route::get('/forgotPassword/check-unit', [ForgotPasswordController::class, 'showCheckUnit'])->name('forgotPassword.checkUnit');
    Route::get('/forgotPassword/new-password', [ForgotPasswordController::class, 'showNewPassword'])->name('forgotPassword.newPassword');
});

Route::middleware(['guest.rumah', 'login.limit'])->group(function () {

    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/forgotPassword/check-nik', [ForgotPasswordController::class, 'checkNik'])->name('forgotPassword.checkNik');
    Route::post('/verifikasi/check-unit', [ForgotPasswordController::class, 'verifikasiCheckUnit'])->name('forgotPassword.verifikasiCheckUnit');
    Route::post('/forgotPassword/save-password', [ForgotPasswordController::class, 'saveNewPassword'])->name('forgotPassword.savePassword');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/logout-all-devices/{id}', [LoginController::class, 'logoutAllDevices'])->name('logoutAllDevices');

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
    Route::post('/pengajuan-perubahan/store', [PengajuanPerubahanController::class, 'store'])->name('pengajuan.perubahan.store');



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

    //--------------------------------PESAN WARGA CONTROLLER------------------------------//
    Route::get('/pesan', [PesanWargaController::class, 'index'])->name('pesanWarga');
    Route::get('/pesan/{id}', [PesanWargaController::class, 'show'])->name('pesanWarga.show');


    /*
    | PROFIL
    */

    Route::prefix('profil')->group(function () {

        Route::get('/', [ProfileController::class, 'index'])->name('profil');
        Route::get('/data-pribadi', [DataPribadiController::class, 'index'])->name('profil.dataPribadi');
        Route::get('/setting-password', [SettingPasswordController::class, 'index'])->name('setting.password');
        Route::post('/verify_nik', [SettingPasswordController::class, 'verify_data'])->name('data.Verify');
        Route::get('/password_baru', [SettingPasswordController::class, 'password_baru'])->name('password.baru');
        Route::post('/simpan-password', [SettingPasswordController::class, 'simpan_password'])->name('password.simpan');
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
    Route::post('/setuju-layanan', [LayananApprovalController::class, 'setuju'])->name('setujuLayanan');
});

/*
|--------------------------------------------------------------------------
| AUTH MANAGEMENT
|--------------------------------------------------------------------------
*/


// Splash Setup System
Route::get('/pengurus', fn() => view('backend.management.splash_management'))->name('splash_management');
/*
|--------------------------------------------------------------------------
| Setup Wilayah
|--------------------------------------------------------------------------
*/
// Route::post('/rw', [RWController::class, 'store'])->name('rw.store');
// Route::post('/rt', [RWController::class, 'storeRT'])->name('rt.store');
// Route::post('/block', [RWController::class, 'storeRT'])->name('block.store');


Route::get('/showlogin_management', [LoginManagementController::class, 'showLogin_management'])->middleware('redirect.management')->name('showlogin_management');
Route::post('/management/login', [LoginManagementController::class, 'login_management'])->name('management.login.process');
Route::post('/management/verify-otp', [LoginManagementController::class, 'verifyOtp'])->name('management.verify.otp');

/*------------------------------------------------------------------------ FORGOT PASSWORD ------------------------------------------------------------------------------- */
Route::get('/forgot-password', [ManagementForgotPasswordController::class, 'index'])->name('password.request');
Route::post('/forgot-password/check', [ManagementForgotPasswordController::class, 'checkName'])->name('password.check');
Route::get('/forgot-password/checkData/{id}', [ManagementForgotPasswordController::class, 'checkData'])->name('password.checkData');
Route::post('/forgot-password/checkData/{id}', [ManagementForgotPasswordController::class, 'verifyCheckData'])->name('password.verifyCheckData');
Route::post('/forgot-password/verifyOtp/{id}', [ManagementForgotPasswordController::class, 'verifyOtp'])->name('password.verify.otp');
Route::get('/forgot-password/inputPassword/{id}', [ManagementForgotPasswordController::class, 'inputPassword'])->name('password.input.password');
Route::post('/forgot-password/updatePassword/{id}', [ManagementForgotPasswordController::class, 'updatePassword'])->name('password.update.password');


/*------------------------------------------------------------------------ END FORGOT PASSWORD ------------------------------------------------------------------------------- */




/*
|--------------------------------------------------------------------------
| AREA MANAGEMENT
|--------------------------------------------------------------------------
*/

Route::prefix('management')->middleware(['check.device', 'prevent.back'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | DASHBOARD
    |----------------------------------------------------------------------
    */
    Route::prefix('/')->group(function () {

        /*------------------------------------------------------------------------ DASHBOARD ------------------------------------------------------------------------------- */
        Route::get('/dashboard', [DashboardManagementController::class, 'index'])->middleware('permission:dashboard.view')->name('management.dashboard');
        /*------------------------------------------------------------------------ END DASHBOARD ------------------------------------------------------------------------------- */

        Route::get('/statistik-warga', [StatistikWargaController::class, 'index'])->middleware('permission:statistik.warga')->name('management.dashboard.statistik_warga');
        Route::get('/statistik-keuangan', fn() => app(UnderConstructionController::class)->index('Dashboard Statistik Keuangan'))->middleware('permission:statistik.keuangan')->name('management.dashboard.statistik_keuangan');
        Route::get('/grafik-iuran', fn() => app(UnderConstructionController::class)->index('Dashboard Grafik Iuran'))->middleware('permission:keuangan.view')->name('management.dashboard.grafik_iuran');
    });

    /*
        |----------------------------------------------------------------------
        | USER MANAGEMENT
        |----------------------------------------------------------------------
        */
    Route::prefix('/')->group(function () {

        /*------------------------------------------------------------------------ MANAGEMENT MENU ------------------------------------------------------------------------------- */
        Route::get('/menu',  [MenuController::class, 'index'])->middleware('permission:management.setting')->name('management.users.index');
        Route::post('/menu/store', [MenuController::class, 'store'])->middleware('permission:menu.create')->name('management.menu.create');
        Route::put('/menu/update/{id}', [MenuController::class, 'update'])->middleware('permission:menu.update')->name('management.menu.update');
        Route::post('/permissions/store', [MenuController::class, 'StorePermission'])->middleware('permission:permissions.create')->name('management.permissions.store');
        Route::put('/permissions/{id}', [MenuController::class, 'updatePermission'])->middleware('permission:permissions.update')->name('management.permissions.update');
        Route::delete('/permissions/{id}', [MenuController::class, 'destroyPermission'])->middleware('permission:permissions.delete')->name('management.permissions.delete');
        /*------------------------------------------------------------------------ END MANAGEMENT MENU ------------------------------------------------------------------------------- */


        /*--------------------------------------------------- PERMISSION ---------------------------------------------------------- */
        Route::get('/role/permissions', [RolePermissionsController::class, 'index'])->middleware('permission:rolepermissions.view')->name('management.role_permissions.index');
        Route::get('/role-permissions/tree/{role}', [RolePermissionsController::class, 'getTree'])->middleware('permission:rolepermissions.view');
        Route::post('/role-permissions/sync', [RolePermissionsController::class, 'sync'])->middleware('permission:rolepermissions.update');
        /*--------------------------------------------------- END PERMISSION ---------------------------------------------------------- */


        /*------------------------------------------------------------------------ ROLES ------------------------------------------------------------------------------ */
        Route::get('/roles', [ManagementRolseController::class, 'index'])->middleware('permission:aksesuser.view')->name('management.roles.index');
        Route::post('/roles/create', [ManagementRolseController::class, 'store'])->middleware('permission:daftarstrukture.create')->name('management.roles.store');
        Route::put('/roles/update/{id}', [ManagementRolseController::class, 'update'])->middleware('permission:daftarstrukture.update')->name('management.roles.update');
        Route::post('/roles/akses/store', [ManagementRolseController::class, 'AksesStore'])->middleware('permission:aksesuser.create')->name('management.roles_akses.store');
        /*------------------------------------------------------------------------ END ROLES ------------------------------------------------------------------------------- */


        /*------------------------------------------------------------------------ WILAYAH ------------------------------------------------------------------------------- */
        /* STRUKTUR ORGANISASI */
        Route::get('/manage_struktur', [StruktureManagementController::class, 'index'])->middleware('permission:wilayah.view')->name('management.manage_struktur.index');
        Route::post('/store_RT', [StruktureManagementController::class, 'store_RT'])->middleware('permission:wilayahrt.create')->name('management.store_RT');
        Route::post('/store_Block', [StruktureManagementController::class, 'store_Block'])->middleware('permission:wilayahblock.create')->name('management.store_Block');
        Route::put('/management/block/{id}', [StruktureManagementController::class, 'updateBlock'])->middleware('permission:wilayahblock.update')->name('management.block.update');
        /*------------------------------------------------------------------------END WILAYAH ------------------------------------------------------------------------------- */
    });
    /*
    |----------------------------------------------------------------------
    | WARGA
    |----------------------------------------------------------------------
    */
    Route::prefix('/')->group(function () {
        Route::get('/warga', [ManagementWargaController::class, 'index'])->middleware('permission:managementwarga.view')->name('management.warga.index');
        Route::get('/view/warga/{warga}', [ManagementWargaController::class, 'show'])->middleware('permission:datawarga.view')->name('management.warga.view');
        /* TAMBAH WARGA */
        Route::get('/warga/tambah', [ManagementTambahWargaController::class, 'create'])->middleware('permission:datawarga.create')->name('management.warga.tambah');
        Route::post('/store', [ManagementTambahWargaController::class, 'store_management_warga'])->middleware('permission:datawarga.store')->name('store_management_warga');
        /* KELUARGA */
        Route::get('/view/tambah/keluargas', [ManagementTambahKeluargaController::class, 'ShowKeluarga'])->middleware('permission:keluarga_satu_kk.create')->name('management.warga.tambah_keluarga');
        Route::post('/store/keluargas', [ManagementTambahKeluargaController::class, 'store_management_Keluarga'])->middleware('permission:keluarga_satu_kk.store')->name('management.warga.store_keluarga');
        /* 1 KK */
        Route::get('/Data_warga/{keluarga_id}', [MManagementTambahDataWargaController::class, 'ShowWarga'])->middleware('permission:wargasatukk.create')->name('management.warga.tambahData_warga');
        Route::post('/Management/Data_warga/Store', [MManagementTambahDataWargaController::class, 'DataWargaStore'])->middleware('permission:wargasatukk.store')->name('management.warga.Store_Data_warga');
        Route::patch('/warga/{warga}/toggle-status', [ManagementWargaController::class, 'toggleStatus'])->middleware('permission:wargasatatus.update')->name('management.warga.toggle-status');
        Route::get('/tambah/satuKK/{id}', [ManagementTambahDataSatukkController::class, 'create'])->middleware('permission:wargasatukk.create')->name('management.warga.tambahSatuKK');
        Route::post('/store/satuKK', [ManagementTambahDataSatukkController::class, 'storDataSatuKK'])->middleware('permission:wargasatukk.store')->name('management.warga.StoreSatuKK');
        /* BEDA KK */
        Route::get('/tambah/BedaKK/{id}', [ManagementTambahDataBedaKKController::class, 'create'])->middleware('permission:wargabedakk.create')->name('management.warga.tambahBedaKK');
        Route::post('/store/bedaKK', [ManagementTambahDataBedaKKController::class, 'storDataBedaKK'])->middleware('permission:wargabedakk.store')->name('management.warga.StoreBedaKK');
        Route::get('/tambah/keluarga/BedaKK/{id}', [ManagementTambahKeluargaBedaKKController::class, 'create'])->middleware('permission:wargabedakk.create')->name('management.warga.tambahKeluargaBedaKK');
        Route::post('/Management/Data_warga/Store/beda_KK', [ManagementTambahKeluargaBedaKKController::class, 'DataWargaBedakkStore'])->middleware('permission:wargabedakk.store')->name('management.warga.Store_Data_warga_bedaKK');
        /* DATA RUMAH & KK */
        Route::get('/kartu-keluarga', [ManagementRumahDanKK::class, 'index'])->middleware('permission:keluarga.view')->name('management.kk.index');


        Route::get('/mutasi-warga', fn() => app(UnderConstructionController::class)->index('Mutasi Warga'))->middleware('permission:warga.update')->name('management.mutasi.index');
    });
    /*
    |----------------------------------------------------------------------
    | KEUANGAN
    |----------------------------------------------------------------------
    */
    Route::prefix('/')->group(function () {
        Route::get('/iuran', fn() => app(UnderConstructionController::class)->index('Manajemen Iuran'))->middleware('permission:keuangan.view')->name('management.iuran.index');
        Route::get('/kas', fn() => app(UnderConstructionController::class)->index('Manajemen Kas'))->middleware('permission:keuangan.view')->name('management.kas.index');
        Route::get('/pengeluaran', fn() => app(UnderConstructionController::class)->index('Manajemen Pengeluaran'))->middleware('permission:keuangan.view')->name('management.pengeluaran.index');
        Route::get('/laporan-keuangan', fn() => app(UnderConstructionController::class)->index('Laporan Keuangan'))->middleware('permission:keuangan.view')->name('management.laporan.keuangan');
    });

    /*
    |----------------------------------------------------------------------
    | SURAT
    |----------------------------------------------------------------------
    */
    Route::prefix('/')->group(function () {
        Route::get('/surat-pengantar', fn() => app(UnderConstructionController::class)->index('Surat Pengantar'))->middleware('permission:surat.view')->name('management.surat_pengantar.index');
        Route::get('/surat-keterangan', fn() => app(UnderConstructionController::class)->index('Surat Keterangan'))->middleware('permission:surat.view')->name('management.surat_keterangan.index');
        Route::get('/arsip-surat', fn() => app(UnderConstructionController::class)->index('Arsip Surat'))->middleware('permission:surat.view')->name('management.arsip_surat.index');
    });
    /*
    |----------------------------------------------------------------------
    | LOGOUT
    |----------------------------------------------------------------------
    */
    Route::post('/logout', [LoginManagementController::class, 'logout_management'])->name('management.logout');
    /*
    |--------------------------------------------------------------------------
    | PROFILE / SECURITY ACTION
    |--------------------------------------------------------------------------
    */

    Route::prefix('/')->group(function () {
        // halaman ganti password management
        Route::get('/change-password', [ManagementSettingPasswordController::class, 'index'])->name('management.change_password');
        Route::post('/verify-change-password', [ManagementSettingPasswordController::class, 'verifyCaptcha'])->name('management.verify.captcha');
        Route::post('/management/verify-otp', [ManagementSettingPasswordController::class, 'verifyOtpResetPassword'])->name('management.verify.otp');
        Route::get('/input-password-baru', [ManagementSettingPasswordController::class, 'inputPassword'])->name('management.input.password');
        Route::post('/update-password-baru', [ManagementSettingPasswordController::class, 'updatePassword'])->name('management.password.update');
    });
});
