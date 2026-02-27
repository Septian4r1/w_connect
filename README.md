# w_connect

**w_connect** adalah aplikasi untuk **manajemen warga tingkat RW**, dibangun dengan Laravel. Aplikasi ini memudahkan RT/RW dan warga untuk:

- Mengelola data warga, rumah, dan keluarga.
- Menyimpan dokumen penting (KK, KTP, Surat-surat).
- Memantau layanan dan approval warga.
- Menyediakan dashboard informasi lengkap untuk pengurus RW.
- Integrasi dengan notifikasi dan laporan digital.

## Fitur Utama

1. **Manajemen Warga**
   - Tambah, edit, hapus data warga.
   - Upload foto KTP, KK, dan selfie.
   - Riwayat perubahan data.

2. **Manajemen Rumah & Keluarga**
   - Relasi rumah → keluarga → warga.
   - Layanan approval terkait keluarga dan rumah.

3. **Dashboard RW**
   - Statistik warga, keluarga, RT/RW.
   - Laporan lengkap per wilayah.

4. **Autentikasi & Hak Akses**
   - Multi-level user: RW, RT, Operator, Admin, dll.
   - Sistem login, register, reset password.
   - Middleware untuk validasi data user.

5. **Upload & Storage Aman**
   - File disimpan di storage Laravel.
   - `.gitignore` melindungi file sensitif agar tidak ikut commit.

## Teknologi

- Backend: **Laravel 10**
- Frontend: Blade + Tailwind CSS + Alpine.js
- Database: MySQL / MariaDB
- DevOps: Docker + GitHub CI/CD (workflow Laravel)
- Testing: PHPUnit



