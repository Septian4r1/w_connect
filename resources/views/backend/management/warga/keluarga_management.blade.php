@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid py-3">

        <div class="row justify-content-center">

            <div class="card modern-card">

                {{-- HEADER --}}
                <div class="card-header text-center bg-white border-0 pb-0">
                    <h5 class="fw-bold mb-1">Data Kartu Keluarga</h5>
                    <small class="text-muted">Isi Data Kartu Keluarga</small>
                </div>

                {{-- BODY --}}
                <div class="card-body pt-3">

                    <form id="formKeluarga" method="POST" action="{{ route('management.warga.store_keluarga') }}"
                        enctype="multipart/form-data">

                        @csrf

                        <input type="hidden" name="rumah_id" value="{{ $rumah_id }}">

                        <div class="row">

                            {{-- KIRI --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label>Nomor KK</label>
                                    <input type="number" name="no_kk" class="form-control modern-input" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Status</label>
                                    <input type="text" class="form-control modern-input" value="Aktif" readonly>
                                    <input type="hidden" name="status" value="aktif">
                                </div>

                                <div class="form-group mb-3">
                                    <label>KTP Setempat</label>
                                    <select name="ktp_setempat" id="ktp_setempat" class="form-control modern-input">
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Kependudukan</label>
                                    <input type="text" id="kependudukan_view" class="form-control modern-input" readonly>
                                    <input type="hidden" name="kependudukan" id="kependudukan">
                                </div>

                                {{-- FOTO KK --}}
                                <div class="form-group mb-3">
                                    <label>Upload Foto KK</label>

                                    <div class="filebox">
                                        <input type="file" name="foto_kk" class="form-control border-0" accept="image/*"
                                            onchange="previewImage(this, 'preview_kk')" required>

                                        <br>
                                        <div class="preview-wrapper mt-5">
                                            <img id="preview_kk" class="preview mt-3">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- KANAN --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label>Provinsi</label>
                                    <select id="provinsi" name="provinsi" class="form-control modern-input">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Kota/Kabupaten</label>
                                    <select id="kota" name="kota" class="form-control modern-input">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Kecamatan</label>
                                    <select id="kecamatan" name="kecamatan" class="form-control modern-input">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Desa/Kelurahan</label>
                                    <select id="desa" name="desa_kelurahan" class="form-control modern-input">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Alamat KK</label>
                                    <textarea name="alamat_kk" rows="3" class="form-control modern-input"></textarea>
                                </div>

                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <button type="submit" class="btn btn-success w-100 modern-btn">
                            💾 Simpan Data KK
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
        document.addEventListener("DOMContentLoaded", function() {

            /* =========================================================
               CONFIG API
            ========================================================= */
            const API_PROVINSI = "{{ config('wilayah.provinsi') }}";
            const API_KOTA = "{{ config('wilayah.kota') }}";
            const API_KECAMATAN = "{{ config('wilayah.kecamatan') }}";
            const API_DESA = "{{ config('wilayah.desa') }}";

            /* =========================================================
               ELEMENT
            ========================================================= */
            const form = document.getElementById('formKeluarga');
            const submitBtn = document.querySelector('.modern-btn');

            let isSubmitting = false;

            /* =========================================================
               SWEETALERT ERROR
            ========================================================= */
            function apiError(msg = 'Terjadi kesalahan sistem') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: msg,
                    customClass: {
                        popup: 'swal-popup-mini',
                        title: 'swal-title-mini'
                    }
                });
            }

            /* =========================================================
               LOAD PROVINSI
            ========================================================= */
            fetch(API_PROVINSI)
                .then(res => res.ok ? res.json() : Promise.reject())
                .then(data => {
                    const prov = document.getElementById('provinsi');

                    prov.innerHTML = '<option value="">Pilih Provinsi</option>';

                    data.forEach(item => {
                        prov.innerHTML += `
                    <option value="${item.name}" data-id="${item.id}">
                        ${item.name}
                    </option>`;
                    });
                })
                .catch(() => apiError('Gagal memuat provinsi'));

            /* =========================================================
               WILAYAH BERJENJANG
            ========================================================= */

            document.getElementById('provinsi')?.addEventListener('change', function() {

                const id = this.selectedOptions[0]?.dataset.id;

                const kota = document.getElementById('kota');
                const kec = document.getElementById('kecamatan');
                const desa = document.getElementById('desa');

                kota.innerHTML = '<option>Pilih Kota</option>';
                kec.innerHTML = '<option>Pilih Kecamatan</option>';
                desa.innerHTML = '<option>Pilih Desa</option>';

                if (!id) return;

                fetch(`${API_KOTA}/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            kota.innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });
                    })
                    .catch(() => apiError('Gagal memuat kota'));
            });

            document.getElementById('kota')?.addEventListener('change', function() {

                const id = this.selectedOptions[0]?.dataset.id;

                const kec = document.getElementById('kecamatan');
                const desa = document.getElementById('desa');

                kec.innerHTML = '<option>Pilih Kecamatan</option>';
                desa.innerHTML = '<option>Pilih Desa</option>';

                if (!id) return;

                fetch(`${API_KECAMATAN}/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            kec.innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });
                    })
                    .catch(() => apiError('Gagal memuat kecamatan'));
            });

            document.getElementById('kecamatan')?.addEventListener('change', function() {

                const id = this.selectedOptions[0]?.dataset.id;

                const desa = document.getElementById('desa');

                desa.innerHTML = '<option>Pilih Desa</option>';

                if (!id) return;

                fetch(`${API_DESA}/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            desa.innerHTML +=
                                `<option value="${item.name}">${item.name}</option>`;
                        });
                    })
                    .catch(() => apiError('Gagal memuat desa'));
            });

            /* =========================================================
               KEPENDUDUKAN AUTO
            ========================================================= */
            const ktp = document.getElementById('ktp_setempat');
            const kep = document.getElementById('kependudukan');
            const kepView = document.getElementById('kependudukan_view');

            function updateKependudukan() {
                if (!ktp) return;

                if (ktp.value === 'ya') {
                    kep.value = 'tetap';
                    kepView.value = 'tetap';
                } else {
                    kep.value = 'domisili';
                    kepView.value = 'domisili';
                }
            }

            updateKependudukan();
            ktp?.addEventListener('change', updateKependudukan);

            /* =========================================================
               SUBMIT (SAMA SEPERTI registerForm STYLE)
            ========================================================= */
            form?.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (isSubmitting) return;
                isSubmitting = true;

                try {

                    /* ================= CONFIRM ================= */
                    const confirm = await Swal.fire({
                        width: 300,
                        title: 'Konfirmasi Simpan',
                        text: 'Pastikan data sudah benar sebelum disimpan',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'swal-popup-mini',
                            title: 'swal-title-mini'
                        }
                    });

                    if (!confirm.isConfirmed) {
                        isSubmitting = false;
                        return;
                    }

                    const formData = new FormData(form);

                    /* ================= LOADING ================= */
                    Swal.fire({
                        width: 260,
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading(),
                        customClass: {
                            popup: 'swal-popup-mini',
                            title: 'swal-title-mini'
                        }
                    });

                    /* ================= SEND REQUEST ================= */
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    Swal.close();

                    /* ================= SUCCESS ================= */
                    if (response.ok && data.status === 'success') {

                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1200,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'swal-popup-mini',
                                title: 'swal-title-mini'
                            }
                        });

                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }

                        return;
                    }

                    /* ================= VALIDATION ERROR ================= */
                    let errorMessage = '';

                    if (data.errors) {
                        Object.values(data.errors).forEach(err => {
                            errorMessage += `• ${err[0]}<br>`;
                        });
                    } else {
                        errorMessage = data.message || 'Terjadi kesalahan';
                    }

                    await Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        html: `<div style="text-align:left">${errorMessage}</div>`,
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal-popup-mini',
                            title: 'swal-title-mini'
                        }
                    });

                } catch (error) {

                    Swal.close();

                    await Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal-popup-mini',
                            title: 'swal-title-mini'
                        }
                    });

                } finally {
                    isSubmitting = false;
                }
            });

        });

        /* =========================================================
           IMAGE PREVIEW
        ========================================================= */
        function previewImage(input, previewId) {
            const file = input.files?.[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById(previewId);
                if (!img) return;

                img.src = e.target.result;
                img.style.display = 'block';
            };

            reader.readAsDataURL(file);
        }
    </script>
@endpush
