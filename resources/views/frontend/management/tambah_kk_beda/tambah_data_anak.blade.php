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

        <form method="POST" action="{{ route('store.DataAnak') }}" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="keluarga_id" value="{{ $keluarga->id }}">

            <!-- Nama -->
            <label class="label">Nama</label>
            <input type="text" name="nama" class="input" required>

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

            <!-- Jenis Kelamin -->
            <label class="label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="select" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>

            <!-- NIK -->
            <label class="label">
                NIK <br>
                <small style="font-weight:400;font-size:11px;color:#ff0000;">
                    Jika umur dibawah 16 tahun, boleh dikosongkan</small>
            </label>
            <input type="number" name="nik" class="input" id="nik">

            <!-- Hubungan -->
            <label class="label">Hubungan</label>
            <select class="select" disabled>
                <option value="anak" selected>Anak</option>
            </select>
            <!-- Hidden input tetap dikirim ke backend -->
            <input type="hidden" name="hubungan" value="anak">

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
                <option value="Tidak Sekolah">Tidak Sekolah</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA/SMK">SMA/SMK</option>
                <option value="Diploma">Diploma</option>
                <option value="Sarjana">Sarjana</option>
                <option value="Pasca Sarjana">Pasca Sarjana</option>
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

            <label class="label">No HP<br>
                <small style="font-weight:400;font-size:11px;color:#ff0000;">
                    Jika umur dibawah 16 tahun, boleh dikosongkan </small>
            </label>

            <input type="number" name="no_hp" class="input">

            <label class="label">Email <br>
                <small style="font-weight:400;font-size:11px;color:#ff0000;">
                    Jika umur dibawah 16 tahun, boleh dikosongkan </small>
            </label>
            <input type="email" name="email" class="input" placeholder="Optional, Sebaiknya Di isi">

            <label class="label">Golongan Darah</label>
            <select name="golongan_darah" class="select">
                <option value="">Pilih Golongan Darah</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>

            <label class="label">Foto KTP <br>
                <small style="font-weight:400;font-size:11px;color:#ff0000;">
                    Jika umur dibawah 16 tahun, boleh dikosongkan </small>
            </label>
            <div class="filebox">
                <input type="file" name="foto_ktp" accept="image/*" capture="environment"
                    onchange="previewImage(this,'preview_ktp')" required>
                <img id="preview_ktp" class="preview" style="display:none;max-width:200px;margin-top:8px;">
            </div>

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

            // =========================
            // 0️⃣ Validasi umur untuk NIK, No HP, Foto KTP, Email
            // =========================
            const inputTanggalLahir = document.querySelector('input[name="tanggal_lahir"]');
            const inputNik = document.querySelector('input[name="nik"]');
            const inputNoHp = document.querySelector('input[name="no_hp"]');
            const inputFotoKtp = document.querySelector('input[name="foto_ktp"]');
            const inputEmail = document.querySelector('input[name="email"]');

            function hitungUmur(tglLahir) {
                if (!tglLahir) return 0;
                const today = new Date();
                const lahir = new Date(tglLahir);
                let umur = today.getFullYear() - lahir.getFullYear();
                const m = today.getMonth() - lahir.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < lahir.getDate())) {
                    umur--;
                }
                return umur;
            }

            function cekUmur() {
                const tgl = inputTanggalLahir.value;
                if (!tgl) return;

                const umur = hitungUmur(tgl);

                if (umur >= 17) {
                    inputNik.removeAttribute('disabled');
                    inputNik.required = true;
                    inputNoHp.removeAttribute('disabled');
                    inputNoHp.required = true;
                    inputFotoKtp.removeAttribute('disabled');
                    inputFotoKtp.required = true;
                    inputEmail.removeAttribute('disabled');
                } else {
                    inputNik.value = '';
                    inputNik.setAttribute('disabled', true);
                    inputNik.required = false;
                    inputNoHp.value = '';
                    inputNoHp.setAttribute('disabled', true);
                    inputNoHp.required = false;
                    inputFotoKtp.value = '';
                    inputFotoKtp.setAttribute('disabled', true);
                    inputFotoKtp.required = false;
                    inputEmail.value = '';
                    inputEmail.setAttribute('disabled', true);
                }
            }

            cekUmur();
            if (inputTanggalLahir) inputTanggalLahir.addEventListener('change', cekUmur);

            // =========================
            // 1️⃣ Konfigurasi API Provinsi & Kota
            // =========================
            const apiProvinsi = "{{ config('wilayah.provinsi') }}";
            const apiKotaBase = "{{ config('wilayah.kota') }}";

            const selectProvinsi = document.getElementById('provinsi');
            const selectKota = document.getElementById('tempat_lahir');

            const provinsiMap = {};

            if (selectProvinsi) {
                fetch(apiProvinsi)
                    .then(res => res.ok ? res.json() : Promise.reject('Server provinsi tidak merespon'))
                    .then(data => {
                        const fragment = document.createDocumentFragment();
                        data.forEach(prov => {
                            provinsiMap[prov.name] = prov.id;
                            const option = document.createElement('option');
                            option.value = prov.name;
                            option.text = prov.name;
                            fragment.appendChild(option);
                        });
                        selectProvinsi.appendChild(fragment);
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal memuat provinsi',
                            text: 'Periksa koneksi internet atau server sedang sibuk!',
                            confirmButtonColor: '#3085d6',
                            customClass: {
                                title: 'swal-title-small',
                                content: 'swal-text-small',
                                confirmButton: 'swal-btn-small'
                            }
                        });
                    });
            }

            if (selectProvinsi && selectKota) {
                selectProvinsi.addEventListener('change', function() {
                    const provName = this.value;
                    selectKota.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    if (!provName) return;

                    const provId = provinsiMap[provName];
                    if (!provId) return;

                    fetch(`${apiKotaBase}/${provId}.json`)
                        .then(res => res.ok ? res.json() : Promise.reject('Server kota tidak merespon'))
                        .then(data => {
                            const fragment = document.createDocumentFragment();
                            data.forEach(kota => {
                                const option = document.createElement('option');
                                option.value = kota.name;
                                option.text = kota.name;
                                fragment.appendChild(option);
                            });
                            selectKota.appendChild(fragment);
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal memuat kota',
                                text: 'Periksa koneksi internet atau server sedang sibuk!',
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

            // =========================
            // 4️⃣ Preview Gambar sebelum Upload (global, aman)
            // =========================
            window.previewImage = function(fileInput, previewId) {
                if (!fileInput || !fileInput.files || fileInput.files.length === 0) return;
                const file = fileInput.files[0];
                const img = document.getElementById(previewId);
                if (!img) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }

            document.addEventListener('change', function(e) {
                const target = e.target;
                if (target.tagName === 'INPUT' && target.type === 'file') {
                    const nextImg = target.nextElementSibling;
                    if (nextImg && nextImg.tagName === 'IMG') {
                        previewImage(target, nextImg.id);
                    }
                }
            });

            // =========================
            // 5️⃣ AJAX Submit Form dengan SweetAlert
            // =========================
            window.submitForm = function(action) {
                const form = document.querySelector('form');
                if (!form) return;

                let hiddenInput = form.querySelector('input[name="action_type"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'action_type';
                    form.appendChild(hiddenInput);
                }
                hiddenInput.value = action;

                const formData = new FormData(form);

                Swal.fire({
                    title: 'Sedang menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                    customClass: {
                        title: 'swal-title-small',
                        content: 'swal-text-small',
                    }
                });

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        Swal.close();
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: res.message,
                                confirmButtonColor: '#3085d6',
                                customClass: {
                                    title: 'swal-title-small',
                                    content: 'swal-text-small',
                                    confirmButton: 'swal-btn-small'
                                }
                            }).then(() => {
                                if (res.action === 'tambah') {
                                    form.reset();
                                    document.querySelectorAll('.preview').forEach(p => p.style
                                        .display = 'none');
                                } else {
                                    window.location.href = "{{ route('homeWarga') }}";
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message,
                                confirmButtonColor: '#d33',
                                customClass: {
                                    title: 'swal-title-small',
                                    content: 'swal-text-small',
                                    confirmButton: 'swal-btn-small'
                                }
                            });
                        }
                    })
                    .catch(err => {
                        Swal.close();
                        console.error('Error:', err);
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
            }

            // =========================
            // 6️⃣ SweetAlert dari session Laravel
            // =========================
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
