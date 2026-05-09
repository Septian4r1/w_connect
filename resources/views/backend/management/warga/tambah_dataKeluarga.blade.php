@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid py-3">

        <div class="row justify-content-center">



            <div class="card modern-card shadow-sm border-0">

                {{-- HEADER --}}
                <div class="card-header text-center bg-white border-0 pb-0">
                    <h5 class="fw-bold mb-1">Input Data Warga</h5>
                    <small class="text-muted">Isi data anggota keluarga</small>
                </div>

                {{-- BODY --}}
                <div class="card-body pt-3">

                    <form id="formWarga" method="POST" action="{{ route('management.warga.StoreSatuKK') }}"
                        enctype="multipart/form-data">

                        @csrf

                        <input type="hidden" name="keluarga_id" value="{{ $keluargaIdEncrypted }}">

                        <div class="row">

                            {{-- ================= LEFT ================= --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">NIK</label>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*" name="nik"
                                        class="form-control modern-input">

                                    <small class="text-danger">
                                        ⚠️ Wajib diisi untuk usia di atas 17 tahun
                                    </small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Nama</label>
                                    <input type="text" name="nama" class="form-control modern-input" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control modern-input">
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Hubungan</label>
                                    <select name="hubungan" class="form-control modern-input">
                                        <option value="">Pilih</option>
                                        <option value="istri">Istri</option>
                                        <option value="anak">Anak</option>
                                        <option value="keluarga_lain">Keluarga Lain</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Status Perkawinan</label>
                                    <select name="status_perkawinan" class="form-control modern-input">
                                        <option value="">Pilih</option>
                                        <option value="belum_kawin">Belum Kawin</option>
                                        <option value="kawin">Kawin</option>
                                        <option value="cerai_hidup">Cerai Hidup</option>
                                        <option value="cerai_mati">Cerai Mati</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Agama</label>
                                    <select name="agama" class="form-control modern-input">
                                        <option value="">Pilih</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Pendidikan</label>
                                    <select name="pendidikan" class="form-control modern-input">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="Belum/Tidak Sekolah">Belum/Tidak Sekolah</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="Sarjana">Sarjana</option>
                                        <option value="Pasca Sarjana">Pasca Sarjana</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control modern-input">
                                </div>

                            </div>

                            {{-- ================= RIGHT ================= --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Provinsi</label>
                                    <select id="provinsi" name="provinsi" class="form-control modern-input"></select>
                                    <small class="text-danger">
                                        ⚠️ Wajib Pilih Provinsi Dahulu
                                    </small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Kota/Kabupaten</label>
                                    <select id="tempat_lahir" name="tempat_lahir"
                                        class="form-control modern-input"></select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Pekerjaan</label>
                                    <select name="pekerjaan" class="form-control modern-input">
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
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">No HP</label>
                                    <input type="text" name="no_hp" class="form-control modern-input">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control modern-input">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-semibold">Golongan Darah</label>
                                    <select name="golongan_darah" class="form-control modern-input">
                                        <option value="">Pilih</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>

                                {{-- FOTO KTP --}}
                                <div class="form-group mb-4">
                                    <label>Foto KTP</label>

                                    <input type="file" name="foto_ktp" class="form-control" accept="image/*"
                                        onchange="previewImage(this,'preview_ktp')">

                                    <div class="preview-wrapper mt-4"
                                        style="position:relative; display:none; width:fit-content;">
                                        <img id="preview_ktp" class="img-fluid"
                                            style="max-height:150px; border-radius:8px; display:none;">

                                        <button type="button" class="btn-remove-img"
                                            onclick="removeImage('foto_ktp','preview_ktp',this)">
                                            ✖
                                        </button>
                                    </div>
                                </div>


                                {{-- FOTO SELFIE --}}
                                <div class="form-group mb-4">
                                    <label>Foto Selfie</label>

                                    <input type="file" name="foto" class="form-control" accept="image/*"
                                        onchange="previewImage(this,'preview_foto')">

                                    <div class="preview-wrapper mt-4"
                                        style="position:relative; display:none; width:fit-content;">
                                        <img id="preview_foto" class="img-fluid"
                                            style="max-height:150px; border-radius:8px; display:none;">

                                        <button type="button" class="btn-remove-img"
                                            onclick="removeImage('foto','preview_foto',this)">
                                            ✖
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <button type="submit" class="btn btn-sm btn-success w-100 modern-btn">
                            💾 Simpan Data Warga
                        </button>

                    </form>

                </div>

            </div>


        </div>

    </div>

    @include('backend.management.warga.style')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // =========================
            // ELEMENT
            // =========================
            const form = document.getElementById('formWarga');

            const inputTanggalLahir = document.querySelector('input[name="tanggal_lahir"]');
            const inputNik = document.querySelector('input[name="nik"]');
            const inputNoHp = document.querySelector('input[name="no_hp"]');
            const inputFotoKtp = document.querySelector('input[name="foto_ktp"]');
            const inputEmail = document.querySelector('input[name="email"]');

            const prov = document.getElementById('provinsi');
            const kota = document.getElementById('tempat_lahir');

            // =========================
            // 1️⃣ VALIDASI UMUR
            // =========================
            function hitungUmur(tgl) {
                if (!tgl) return 0;

                const today = new Date();
                const lahir = new Date(tgl);

                let umur = today.getFullYear() - lahir.getFullYear();
                const m = today.getMonth() - lahir.getMonth();

                if (m < 0 || (m === 0 && today.getDate() < lahir.getDate())) {
                    umur--;
                }

                return umur;
            }

            function cekUmur() {
                if (!inputTanggalLahir || !inputTanggalLahir.value) {
                    // kalau belum isi tanggal → semua tetap aktif
                    [inputNik, inputNoHp, inputFotoKtp, inputEmail].forEach(el => {
                        if (!el) return;
                        el.removeAttribute('disabled');
                        el.required = false;
                    });
                    return;
                }

                const umur = hitungUmur(inputTanggalLahir.value);

                if (umur >= 17) {

                    // NIK wajib
                    if (inputNik) {
                        inputNik.removeAttribute('disabled');
                        inputNik.required = true;
                    }

                    // No HP & Foto KTP aktif tapi TIDAK wajib
                    [inputNoHp, inputFotoKtp].forEach(el => {
                        if (!el) return;
                        el.removeAttribute('disabled');
                        el.required = false;
                    });

                    // Email opsional
                    if (inputEmail) {
                        inputEmail.removeAttribute('disabled');
                        inputEmail.required = false;
                    }

                } else {
                    [inputNik, inputNoHp, inputFotoKtp].forEach(el => {
                        if (!el) return;
                        el.value = '';
                        el.setAttribute('disabled', true);
                        el.required = false;
                    });

                    if (inputEmail) {
                        inputEmail.value = '';
                        inputEmail.setAttribute('disabled', true);
                    }
                }
            }

            if (inputTanggalLahir) {
                cekUmur();
                inputTanggalLahir.addEventListener('change', cekUmur);
            }

            // =========================
            // 2️⃣ VALIDASI NIK (16 DIGIT)
            // =========================
            function validasiNIK() {
                if (!inputNik || inputNik.disabled) return true;

                const nik = inputNik.value.trim();

                if (!/^\d{16}$/.test(nik)) {
                    inputNik.classList.add('is-invalid');

                    Swal.fire({
                        icon: 'warning',
                        title: 'NIK tidak valid',
                        text: 'NIK harus terdiri dari 16 digit angka'
                    });

                    return false;
                }

                inputNik.classList.remove('is-invalid');
                return true;
            }

            // realtime hanya angka max 16 digit
            if (inputNik) {
                inputNik.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 16);
                });
            }

            // =========================
            // 3️⃣ LOAD PROVINSI & KOTA
            // =========================
            const apiProvinsi = "{{ config('wilayah.provinsi') }}";
            const apiKota = "{{ config('wilayah.kota') }}";

            const mapProv = {};

            if (prov) {
                fetch(apiProvinsi)
                    .then(res => res.json())
                    .then(data => {
                        prov.innerHTML = '<option value="">-- Pilih Provinsi --</option>';

                        data.forEach(p => {
                            mapProv[p.name] = p.id;
                            prov.innerHTML += `<option value="${p.name}">${p.name}</option>`;
                        });
                    })
                    .catch(() => {
                        prov.innerHTML = '<option>Gagal load provinsi</option>';
                    });

                prov.addEventListener('change', function() {
                    if (!kota) return;

                    kota.innerHTML = '<option value="">-- Pilih Kota --</option>';

                    const id = mapProv[this.value];
                    if (!id) return;

                    fetch(`${apiKota}/${id}.json`)
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(k => {
                                kota.innerHTML +=
                                    `<option value="${k.name}">${k.name}</option>`;
                            });
                        })
                        .catch(() => {
                            kota.innerHTML = '<option>Gagal load kota</option>';
                        });
                });
            }

            // =========================
            // 4️⃣ SUBMIT FORM
            // =========================
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // VALIDASI NIK DULU
                    if (!validasiNIK()) return;

                    submitForm();
                });
            }

        });


        // =========================
        // PREVIEW IMAGE
        // =========================
        function previewImage(input, id) {
            const file = input.files[0];
            if (!file) return;

            const img = document.getElementById(id);
            const wrapper = img?.parentElement;

            img.src = URL.createObjectURL(file);
            img.style.display = 'block';

            if (wrapper) wrapper.style.display = 'inline-block';
        }


        // =========================
        // REMOVE IMAGE
        // =========================
        function removeImage(inputName, previewId, btn) {
            const input = document.querySelector(`input[name="${inputName}"]`);
            if (input) input.value = "";

            const img = document.getElementById(previewId);
            const wrapper = btn.closest('.preview-wrapper');

            if (img) {
                img.src = "";
                img.style.display = "none";
            }

            if (wrapper) wrapper.style.display = "none";
        }


        // =========================
        // AJAX SUBMIT
        // =========================
        function submitForm() {

            const form = document.getElementById('formWarga');
            const formData = new FormData(form);

            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async (r) => {
                    const contentType = r.headers.get("content-type");

                    if (!contentType || !contentType.includes("application/json")) {
                        throw new Error("Server error");
                    }

                    return r.json();
                })
                .then(res => {

                    Swal.close();

                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            if (res.redirect) {
                                window.location.href = res.redirect;
                            } else {
                                location.reload();
                            }
                        });

                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }

                })
                .catch(err => {
                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan server'
                    });

                    console.error(err);
                });
        }
    </script>
@endpush
