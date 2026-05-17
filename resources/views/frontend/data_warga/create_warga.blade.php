@extends('frontend.layouts.app')

@section('title', 'Form Data Keluarga')
@section('header-title', 'Form')

@section('content')

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        .swal-title-small {
            font-size: 14px !important;
        }

        .swal-text-small {
            font-size: 12px !important;
        }

        .swal-btn-small {
            font-size: 12px !important;
            padding: 4px 12px !important;
        }

        * {
            box-sizing: border-box;
        }

        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* CONTAINER MOBILE */
        .container {
            max-width: 420px;
            margin: auto;
        }

        /* TITLE */
        .title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        /* LABEL */
        .label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 3px;
            display: block;
            color: #444;
        }

        /* INPUT */
        .input,
        .select,
        .textarea {
            width: 100%;
            padding: 9px 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            font-size: 13px;
            background: #fafafa;
        }

        /* TEXTAREA */
        .textarea {
            min-height: 70px;
            resize: none;
        }

        /* FILE UPLOAD */
        .filebox {
            border: 1.5px dashed #1fa55b;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            background: #f7fff9;
            margin-bottom: 12px;
            font-size: 12px;
        }

        /* PREVIEW */
        .preview {
            width: 100%;
            border-radius: 8px;
            margin-top: 8px;
            max-height: 150px;
            object-fit: cover;
        }

        /* BUTTON */
        .btn {
            width: 100%;
            padding: 11px;
            border: none;
            border-radius: 8px;
            background: #17a34a;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        /* SELECT STYLE */
        select {
            appearance: none;
        }
    </style>


    <div class="container py-2">

        <div class="title">
            Isi Data Keluarga
        </div>

        <form method="POST" action="{{ route('warga.store') }}" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="keluarga_id" value="{{ $keluarga->id }}">

            <!-- NIK -->
            {{-- <label class="label">
                NIK <small style="font-weight:400;font-size:11px;color:#ff0000;">
                    (17th Keatas Wajib Di isi ) </small>
            </label>
            <input type="number" name="nik" class="input" id="nik"> --}}

            <!-- Nama -->
            <label class="label">Nama</label>
            <input type="text" name="nama" class="input" required>

            <!-- Jenis Kelamin -->
            <label class="label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="select" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <!-- Hubungan -->
            <label class="label">Hubungan</label>
            <select name="hubungan" class="select" required>
                <option value="">Pilih Hubungan</option>
                <option value="kepala_keluarga">Kepala Keluarga</option>
                <option value="istri">Istri</option>
                {{-- <option value="anak">Anak</option> --}}
                <option value="keluarga_lain">Keluarga Lain</option>
            </select>

            <!-- Status Perkawinan -->
            <label class="label">Status Perkawinan</label>
            <select name="status_perkawinan" class="select">
                <option value="">Pilih Status</option>
                <option value="belum_kawin">Belum Kawin</option>
                <option value="kawin">Kawin</option>
                <option value="cerai_hidup">Cerai Hidup</option>
                <option value="cerai_mati">Cerai Mati</option>
            </select>

            <!-- Agama -->
            <label class="label">Agama</label>
            <select name="agama" class="select" required>
                <option value="">Pilih Agama</option>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Katolik">Katolik</option>
                <option value="Hindu">Hindu</option>
                <option value="Buddha">Buddha</option>
                <option value="Konghucu">Konghucu</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <!-- Pendidikan -->
            <label class="label">Pendidikan</label>
            <select name="pendidikan" class="select" required>
                <option value="">Pilih Pendidikan</option>
                <option value="Belum/Tidak Sekolah">Belum/Tidak Sekolah</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA/SMK">SMA/SMK</option>
                <option value="Diploma">Diploma</option>
                <option value="Sarjana">Sarjana</option>
                <option value="Pasca Sarjana">Pasca Sarjana</option>
            </select>

            <!-- Tanggal Lahir -->
            <label class="label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="input">

            <!-- Provinsi -->
            <label class="label">Provinsi</label>
            <select name="provinsi" id="provinsi" class="select" required>
                <option value="">Pilih Provinsi</option>
            </select>

            <!-- Tempat Lahir -->
            <label class="label">Tempat Lahir</label>
            <select name="tempat_lahir" id="tempat_lahir" class="select" required>
                <option value="">Pilih Kota/Kabupaten</option>
            </select>

            <!-- Pekerjaan -->
            <label class="label">Pekerjaan</label>
            <select name="pekerjaan" class="select" required>
                <option value="">-- Pilih Pekerjaan --</option>
                <option value="Belum / Tidak Bekerja">Belum / Tidak Bekerja</option>
                <option value="Pelajar / Mahasiswa">Pelajar / Mahasiswa</option>
                <option value="Mengurus Rumah Tangga">Mengurus Rumah Tangga</option>
                <option value="Pegawai Negeri Sipil (PNS)">Pegawai Negeri Sipil (PNS)</option>
                <option value="TNI">TNI</option>
                <option value="POLRI">POLRI</option>
                <option value="Pegawai Swasta">Pegawai Swasta</option>
                <option value="Wiraswasta">Wiraswasta</option>
                <option value="Pedagang">Pedagang</option>
                <option value="Petani">Petani</option>
                <option value="Nelayan">Nelayan</option>
                <option value="Driver / Sopir">Driver / Sopir</option>
                <option value="Ojek Online">Ojek Online</option>
                <option value="Guru">Guru</option>
                <option value="Dokter">Dokter</option>
                <option value="Programmer / Software Developer">Programmer</option>
                <option value="Desainer Grafis">Desainer Grafis</option>
                <option value="Satpam">Satpam</option>
                <option value="Cleaning Service">Cleaning Service</option>
                <option value="Pensiunan">Pensiunan</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <label class="label">No HP</label>
            <input type="number" name="no_hp" class="input">

            <label class="label">Email</label>
            <input type="email" name="email" class="input" placeholder="Optional, Sebaiknya Di isi">

            <label class="label">Golongan Darah</label>
            <select name="golongan_darah" class="select">
                <option value="">Pilih Golongan Darah</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>

            {{-- <label class="label">Foto KTP</label>
            <div class="filebox">
                <input type="file" name="foto_ktp" accept="image/*" capture="environment"
                    onchange="previewImage(this,'preview_ktp')" required>
                <img id="preview_ktp" class="preview" style="display:none;max-width:200px;margin-top:8px;">
            </div> --}}

            <label class="label">Foto Selfie</label>
            <div class="filebox">
                <input type="file" name="foto" accept="image/*" capture="user"
                    onchange="previewImage(this,'preview_foto')" required>
                <img id="preview_foto" class="preview" style="display:none;max-width:200px;margin-top:8px;">
            </div>


            <button type="button" class="btn" onclick="submitForm()">
                Simpan Data
            </button>


        </form>


    </div>






    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // =========================================================
            // 1️⃣ KONFIGURASI API WILAYAH
            // =========================================================
            // API provinsi dan kota diambil dari config Laravel
            // contoh:
            // config/wilayah.php
            //
            // 'provinsi' => 'https://...',
            // 'kota'     => 'https://...'
            //
            // =========================================================

            const apiProvinsi = "{{ config('wilayah.provinsi') }}";
            const apiKotaBase = "{{ config('wilayah.kota') }}";

            // =========================================================
            // 2️⃣ ELEMENT FORM
            // =========================================================

            const selectProvinsi = document.getElementById('provinsi');
            const selectKota = document.getElementById('tempat_lahir');

            // Mapping:
            // nama provinsi => id provinsi
            //
            // contoh:
            // {
            //   "JAWA BARAT": 32
            // }
            //
            const provinsiMap = {};

            // =========================================================
            // 3️⃣ LOAD DATA PROVINSI
            // =========================================================
            // Mengambil daftar provinsi dari API
            // lalu dimasukkan ke select option
            // =========================================================

            if (selectProvinsi) {

                fetch(apiProvinsi)

                    .then(response => {

                        // Jika response gagal
                        if (!response.ok) {
                            throw new Error('Server provinsi tidak merespon');
                        }

                        return response.json();
                    })

                    .then(data => {

                        const fragment = document.createDocumentFragment();

                        data.forEach(provinsi => {

                            // Simpan mapping nama => id
                            provinsiMap[provinsi.name] = provinsi.id;

                            // Buat option
                            const option = document.createElement('option');

                            option.value = provinsi.name;
                            option.textContent = provinsi.name;

                            fragment.appendChild(option);
                        });

                        // Masukkan ke select
                        selectProvinsi.appendChild(fragment);
                    })

                    .catch(error => {

                        console.error('Error Provinsi:', error);

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memuat provinsi',
                            text: 'Periksa koneksi internet atau server sedang sibuk.',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                title: 'swal-title-small',
                                content: 'swal-text-small',
                                confirmButton: 'swal-btn-small'
                            }
                        });
                    });
            }

            // =========================================================
            // 4️⃣ LOAD KOTA BERDASARKAN PROVINSI
            // =========================================================
            // Saat user memilih provinsi,
            // sistem akan mengambil data kota dari API
            // =========================================================

            if (selectProvinsi && selectKota) {

                selectProvinsi.addEventListener('change', function() {

                    const namaProvinsi = this.value;

                    // Reset kota
                    selectKota.innerHTML =
                        '<option value="">Pilih Kota/Kabupaten</option>';

                    // Jika belum pilih provinsi
                    if (!namaProvinsi) return;

                    // Ambil ID provinsi dari mapping
                    const provinsiId = provinsiMap[namaProvinsi];

                    if (!provinsiId) return;

                    // Fetch kota
                    fetch(`${apiKotaBase}/${provinsiId}.json`)

                        .then(response => {

                            if (!response.ok) {
                                throw new Error('Server kota tidak merespon');
                            }

                            return response.json();
                        })

                        .then(data => {

                            const fragment = document.createDocumentFragment();

                            data.forEach(kota => {

                                const option = document.createElement('option');

                                option.value = kota.name;
                                option.textContent = kota.name;

                                fragment.appendChild(option);
                            });

                            selectKota.appendChild(fragment);
                        })

                        .catch(error => {

                            console.error('Error Kota:', error);

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal memuat kota',
                                text: 'Periksa koneksi internet atau server sedang sibuk.',
                                confirmButtonColor: '#3085d6',
                                customClass: {
                                    title: 'swal-title-small',
                                    content: 'swal-text-small',
                                    confirmButton: 'swal-btn-small'
                                }
                            });
                        });
                });
            }

            // =========================================================
            // 5️⃣ PREVIEW GAMBAR SEBELUM UPLOAD
            // =========================================================
            // Fungsi ini digunakan untuk:
            // - foto KTP
            // - foto KK
            // - foto profile
            // - selfie
            // - dll
            //
            // Aman walaupun:
            // - preview image dihapus
            // - input file di-hide
            // - tidak ada img preview
            // =========================================================

            function previewImage(fileInput, previewId) {

                // Validasi input file
                if (
                    !fileInput ||
                    !fileInput.files ||
                    !fileInput.files[0]
                ) {
                    return;
                }

                // Ambil element preview image
                const previewImageElement =
                    document.getElementById(previewId);

                // Jika img preview tidak ada
                // stop tanpa error
                if (!previewImageElement) {
                    return;
                }

                const file = fileInput.files[0];

                // Validasi tipe file gambar
                if (!file.type.startsWith('image/')) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'File tidak valid',
                        text: 'File harus berupa gambar.',
                        confirmButtonColor: '#f39c12',
                        customClass: {
                            title: 'swal-title-small',
                            content: 'swal-text-small',
                            confirmButton: 'swal-btn-small'
                        }
                    });

                    fileInput.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(event) {

                    previewImageElement.src = event.target.result;

                    // Tampilkan preview
                    previewImageElement.style.display = 'block';
                };

                reader.readAsDataURL(file);
            }

            // =========================================================
            // 6️⃣ AUTO PREVIEW SAAT FILE DIPILIH
            // =========================================================
            // Otomatis mencari IMG setelah input file
            //
            // contoh:
            //
            // <input type="file">
            // <img id="previewKtp">
            //
            // =========================================================

            document.addEventListener('change', function(event) {

                const target = event.target;

                // Pastikan element adalah input file
                if (
                    target &&
                    target.tagName === 'INPUT' &&
                    target.type === 'file'
                ) {

                    // Cari img setelah input
                    const nextImage = target.nextElementSibling;

                    // Jalankan preview jika img ditemukan
                    if (
                        nextImage &&
                        nextImage.tagName === 'IMG'
                    ) {
                        previewImage(target, nextImage.id);
                    }
                }
            });

            // =========================================================
            // 7️⃣ AJAX SUBMIT FORM
            // =========================================================
            // Submit form tanpa reload halaman
            //
            // action:
            // - tambah
            // - simpan
            // =========================================================

            window.submitForm = function(action) {

                const form = document.querySelector('form');

                if (!form) {
                    console.error('Form tidak ditemukan');
                    return;
                }

                // =====================================================
                // Tambahkan hidden input action_type
                // =====================================================

                let hiddenInput =
                    form.querySelector('input[name="action_type"]');

                if (!hiddenInput) {

                    hiddenInput = document.createElement('input');

                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action_type';

                    form.appendChild(hiddenInput);
                }

                hiddenInput.value = action;

                // =====================================================
                // FormData
                // =====================================================

                const formData = new FormData(form);

                // =====================================================
                // Loading SweetAlert
                // =====================================================

                Swal.fire({
                    title: 'Sedang menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                    customClass: {
                        title: 'swal-title-small',
                        content: 'swal-text-small'
                    }
                });

                // =====================================================
                // Fetch Submit
                // =====================================================

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': form.querySelector(
                                'input[name="_token"]'
                            ).value
                        },
                        body: formData
                    })

                    .then(response => response.json())

                    .then(response => {

                        Swal.close();

                        // =================================================
                        // SUCCESS
                        // =================================================

                        if (response.status === 'success') {

                            Swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    confirmButtonColor: '#3085d6',
                                    customClass: {
                                        title: 'swal-title-small',
                                        content: 'swal-text-small',
                                        confirmButton: 'swal-btn-small'
                                    }
                                })

                                .then(() => {

                                    // =========================================
                                    // Jika tambah data lagi
                                    // =========================================

                                    if (response.action === 'tambah') {

                                        form.reset();

                                        // Hide semua preview image
                                        document
                                            .querySelectorAll('.preview')
                                            .forEach(preview => {

                                                preview.style.display = 'none';
                                                preview.src = '';
                                            });

                                    } else {

                                        // Redirect
                                        window.location.href =
                                            "{{ route('homeWarga') }}";
                                    }
                                });

                        }

                        // =================================================
                        // FAILED
                        // =================================================
                        else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message ||
                                    'Data gagal disimpan.',
                                confirmButtonColor: '#d33',
                                customClass: {
                                    title: 'swal-title-small',
                                    content: 'swal-text-small',
                                    confirmButton: 'swal-btn-small'
                                }
                            });
                        }
                    })

                    // =====================================================
                    // ERROR SERVER / NETWORK
                    // =====================================================

                    .catch(error => {

                        Swal.close();

                        console.error('Submit Error:', error);

                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Tidak dapat menyimpan data, silakan coba lagi.',
                            confirmButtonColor: '#d33',
                            customClass: {
                                title: 'swal-title-small',
                                content: 'swal-text-small',
                                confirmButton: 'swal-btn-small'
                            }
                        });
                    });
            };

            // =========================================================
            // 8️⃣ SWEETALERT SESSION LARAVEL
            // =========================================================
            // Menampilkan alert dari session:
            //
            // session('status')
            // session('message')
            // =========================================================

            @if (session('status'))
                Swal.fire({
                    icon: "{{ session('status') }}",
                    title: "{{ session('message') }}",
                    confirmButtonColor: '#3085d6',
                    customClass: {
                        title: 'swal-title-small',
                        content: 'swal-text-small',
                        confirmButton: 'swal-btn-small'
                    }
                });
            @endif

        });
    </script>



@endsection
