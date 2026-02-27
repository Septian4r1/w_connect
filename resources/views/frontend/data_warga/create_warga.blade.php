<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Keluarga</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* Custom ukuran teks & tombol SweetAlert */
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
            font-family: Poppins;
            background: #eaeaea;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .app-wrapper {
            width: 100%;
            max-width: 420px;
            height: 100dvh;
            background: #f3f7f4;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #17a34a;
            color: white;
            padding-top: calc(12px + env(safe-area-inset-top));
            padding-bottom: 12px;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            border-radius: 0 0 20px 20px;
            flex-shrink: 0;
        }

        .container {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            padding-bottom: 120px;
        }

        .card {
            background: white;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.06);
        }

        .title {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .label {
            font-size: 13px;
            margin-bottom: 5px;
            display: block;
            font-weight: 600;
            color: #333;
        }

        .input,
        .select,
        .textarea {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            border: 1px solid #e2e2e2;
            margin-bottom: 15px;
            font-size: 14px;
            background: #fafafa;
        }

        .filebox {
            border: 2px dashed #1fa55b;
            padding: 18px;
            text-align: center;
            border-radius: 14px;
            margin-bottom: 15px;
            background: #f7fff9;
        }

        .preview {
            width: 100%;
            margin-top: 10px;
            border-radius: 12px;
            display: none;
        }

        .footer {
            flex-shrink: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.08);
            padding-top: 12px;
            padding-bottom: calc(12px + env(safe-area-inset-bottom));
            padding-left: 12px;
            padding-right: 12px;
        }

        .btn {
            width: 100%;
            padding: 16px;
            border-radius: 14px;
            border: none;
            background: #17a34a;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:active {
            transform: scale(0.98);
        }
    </style>
</head>

<body>

    <div class="app-wrapper">

        <div class="header">Form Data Keluarga</div>

        <div class="container">
            <div class="card">
                <div class="title">Isi Data Warga</div>

                <form method="POST" action="{{ route('warga.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Keluarga ID (relasi) -->
                    <!-- Keluarga ID otomatis dari controller -->
                    <input type="hidden" name="keluarga_id" value="{{ $keluarga->id }}">

                    <!-- NIK -->
                    <label class="label">NIK</label>
                    <input type="number" name="nik" class="input" required>

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
                        <option value="anak">Anak</option>
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
                        <option value="Tidak Sekolah">Tidak Sekolah</option>
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
                    <input type="text" name="pekerjaan" class="input">

                    <!-- No HP -->
                    <label class="label">No HP</label>
                    <input type="text" name="no_hp" class="input">

                    <!-- Email -->
                    <label class="label">Email</label>
                    <input type="email" name="email" class="input">

                    <!-- Golongan Darah -->
                    <label class="label">Golongan Darah</label>
                    <select name="golongan_darah" class="select">
                        <option value="">Pilih Golongan Darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>

                    <!-- Foto KTP -->
                    <label class="label">Foto KTP</label>
                    <div class="filebox">
                        <input type="file" name="foto_ktp" accept="image/*" capture="environment"
                            onchange="previewImage(this, 'preview_ktp')" required>
                        <img id="preview_ktp" class="preview" style="display:none; max-width:200px; margin-top:8px;">
                    </div>

                    <!-- Foto Warga / Selfie -->
                    <label class="label">Foto Selfie</label>
                    <div class="filebox">
                        <input type="file" name="foto" accept="image/*" capture="user"
                            onchange="previewImage(this, 'preview_foto')" required>
                        <img id="preview_foto" class="preview"
                            style="display:none; max-width:200px; margin-top:8px;">
                    </div>

                </form>
            </div>
        </div>

        <div class="footer" style="display: flex; gap: 5px; padding: 5px;">
            <!-- Tombol Simpan & Tambah -->
            <button type="button" class="btn"
                style="flex: 1; background: #000000; padding: 5px; font-size: 11px; border-radius: 10px;"
                onclick="submitForm('tambah')">
                Simpan & Tambah
            </button>

            <!-- Tombol Simpan & Close -->
            <button type="button" class="btn"
                style="flex: 1; background: #28a745; padding: 5px; font-size: 11px; border-radius: 10px;"
                onclick="submitForm('close')">
                Simpan & Close
            </button>

            <!-- Tombol Close / Batal -->
            <button type="button" class="btn"
                style="flex: 1; background: #dc3545; padding: 5px; font-size: 11px; border-radius: 10px;"
                onclick="window.history.back();">
                Batal
            </button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const apiProvinsi = "{{ config('wilayah.provinsi') }}";
            const apiKotaBase = "{{ config('wilayah.kota') }}";

            const selectProvinsi = document.getElementById('provinsi');
            const selectKota = document.getElementById('tempat_lahir');

            // --- Load Provinsi ---
            if (selectProvinsi) {
                fetch(apiProvinsi)
                    .then(res => res.ok ? res.json() : Promise.reject('Server provinsi tidak merespon'))
                    .then(data => {
                        const fragment = document.createDocumentFragment();
                        data.forEach(prov => {
                            const option = document.createElement('option');
                            option.value = prov.id;
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

            // --- Load Kota saat Provinsi dipilih ---
            if (selectProvinsi && selectKota) {
                selectProvinsi.addEventListener('change', function() {
                    const provId = this.value;
                    selectKota.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>'; // reset
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

            // --- Preview gambar sebelum upload ---
            function previewImage(fileInput, previewId) {
                const file = fileInput.files[0];
                if (!file) return;
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

            // --- AJAX Submit Form untuk SweetAlert ---
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

            // --- SweetAlert session lama ---
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


</body>

</html>
