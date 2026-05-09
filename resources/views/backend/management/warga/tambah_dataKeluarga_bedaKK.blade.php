@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid py-3">

        <div class="row justify-content-center">

            <div class="card modern-card shadow-sm border-0">

                {{-- HEADER --}}
                <div class="card-header text-center bg-white border-0 pb-0">
                    <h5 class="fw-bold mb-1">Tambah Keluarga Beda KK</h5>
                    <small class="text-muted">Isi data kartu keluarga baru</small>
                </div>

                {{-- BODY --}}
                <div class="card-body pt-3">

                    <form id="formKK" method="POST" action="{{ route('management.warga.StoreBedaKK') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            {{-- ================= LEFT ================= --}}
                            <div class="col-md-6">
                                <input type="hidden" name="warga_id" value="{{ $wargaIdEncrypted }}">

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Jenis Kartu Keluarga</label>
                                    <select name="jenis_kk_id" class="form-control modern-input" required>
                                        <option value="">Pilih Jenis KK</option>
                                        @foreach ($jenisKk as $jenis)
                                            <option value="{{ $jenis->id }}">
                                                {{ $jenis->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Nomor KK</label>
                                    <input type="text" name="no_kk" class="form-control modern-input" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <input type="text" class="form-control modern-input" value="Aktif" disabled>
                                    <input type="hidden" name="status" value="aktif">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">KTP Setempat</label>
                                    <select name="ktp_setempat" id="ktp_setempat" class="form-control modern-input">
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Kependudukan</label>
                                    <input type="text" id="kependudukan_view" class="form-control modern-input" disabled>
                                    <input type="hidden" name="kependudukan" id="kependudukan">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Alamat KK</label>
                                    <textarea name="alamat_kk" class="form-control modern-input" rows="3"></textarea>
                                </div>

                            </div>

                            {{-- ================= RIGHT ================= --}}
                            <div class="col-md-6">

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Provinsi</label>
                                    <select id="provinsi" name="provinsi" class="form-control modern-input">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Kota/Kabupaten</label>
                                    <select id="kota" name="kota" class="form-control modern-input">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Kecamatan</label>
                                    <select id="kecamatan" name="kecamatan" class="form-control modern-input">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Desa / Kelurahan</label>
                                    <select id="desa" name="desa_kelurahan" class="form-control modern-input">
                                        <option value="">Pilih Desa/Kelurahan</option>
                                    </select>
                                </div>

                                {{-- FOTO KK --}}
                                <div class="form-group mb-4">
                                    <label>Foto KK</label>

                                    <input type="file" name="foto_kk" class="form-control" accept="image/*"
                                        onchange="previewImage(this,'preview_kk')">

                                    <div class="preview-wrapper mt-3"
                                        style="position:relative; display:none; width:fit-content;">

                                        <img id="preview_kk" class="img-fluid"
                                            style="max-height:150px; border-radius:8px; display:none;">

                                        <button type="button" class="btn-remove-img"
                                            onclick="removeImage('foto_kk','preview_kk',this)">
                                            ✖
                                        </button>

                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <button type="submit" class="btn btn-sm btn-success w-100 modern-btn">
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
        /*
    =====================================================
    CONFIG API WILAYAH
    =====================================================
    */
        const API_PROVINSI = "{{ config('wilayah.provinsi') }}";
        const API_KOTA = "{{ config('wilayah.kota') }}";
        const API_KECAMATAN = "{{ config('wilayah.kecamatan') }}";
        const API_DESA = "{{ config('wilayah.desa') }}";


        /*
        =====================================================
        ERROR HANDLER API
        =====================================================
        */
        function apiError() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Wilayah',
                text: 'Periksa koneksi internet atau server'
            });
        }


        /*
        =====================================================
        PREVIEW IMAGE (GLOBAL)
        =====================================================
        */
        function previewImage(input, id) {
            const file = input.files[0];
            if (!file) return;

            const img = document.getElementById(id);
            const wrapper = img?.parentElement;

            img.src = URL.createObjectURL(file);
            img.style.display = 'block';

            if (wrapper) wrapper.style.display = 'inline-block';
        }


        /*
        =====================================================
        REMOVE IMAGE (GLOBAL)
        =====================================================
        */
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


        /*
        =====================================================
        DOM READY
        =====================================================
        */
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById('formKK');

            const prov = document.getElementById('provinsi');
            const kota = document.getElementById('kota');
            const kecamatan = document.getElementById('kecamatan');
            const desa = document.getElementById('desa');

            const ktp = document.getElementById('ktp_setempat');
            const kep = document.getElementById('kependudukan');
            const kepView = document.getElementById('kependudukan_view');

            const inputKK = document.querySelector('input[name="no_kk"]');

            let isSubmitting = false;


            /*
            =====================================================
            1. LOAD PROVINSI
            =====================================================
            */
            fetch(API_PROVINSI)
                .then(res => {
                    if (!res.ok) throw new Error();
                    return res.json();
                })
                .then(data => {
                    prov.innerHTML = '<option value="">Pilih Provinsi</option>';

                    data.forEach(item => {
                        prov.innerHTML +=
                            `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(apiError);


            /*
            =====================================================
            2. PROVINSI → KOTA
            =====================================================
            */
            prov.addEventListener('change', function() {

                let id = this.selectedOptions[0]?.dataset.id;

                kota.innerHTML = '<option value="">Pilih Kota</option>';
                kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                desa.innerHTML = '<option value="">Pilih Desa</option>';

                if (!id) return;

                fetch(`${API_KOTA}/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            kota.innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });
                    })
                    .catch(apiError);
            });


            /*
            =====================================================
            3. KOTA → KECAMATAN
            =====================================================
            */
            kota.addEventListener('change', function() {

                let id = this.selectedOptions[0]?.dataset.id;

                kecamatan.innerHTML = '<option value="">Pilih Kecamatan</option>';
                desa.innerHTML = '<option value="">Pilih Desa</option>';

                if (!id) return;

                fetch(`${API_KECAMATAN}/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            kecamatan.innerHTML +=
                                `<option value="${item.name}" data-id="${item.id}">${item.name}</option>`;
                        });
                    })
                    .catch(apiError);
            });


            /*
            =====================================================
            4. KECAMATAN → DESA
            =====================================================
            */
            kecamatan.addEventListener('change', function() {

                let id = this.selectedOptions[0]?.dataset.id;

                desa.innerHTML = '<option value="">Pilih Desa</option>';

                if (!id) return;

                fetch(`${API_DESA}/${id}.json`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            desa.innerHTML +=
                                `<option value="${item.name}">${item.name}</option>`;
                        });
                    })
                    .catch(apiError);
            });


            /*
            =====================================================
            5. AUTO KEPENDUDUKAN
            =====================================================
            */
            function setKependudukan() {
                if (ktp.value === 'ya') {
                    kep.value = 'tetap';
                    kepView.value = 'tetap';
                } else {
                    kep.value = 'domisili';
                    kepView.value = 'domisili';
                }
            }

            setKependudukan();
            ktp.addEventListener('change', setKependudukan);


            /*
            =====================================================
            6. VALIDASI NO KK
            =====================================================
            */
            if (inputKK) {
                inputKK.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 16);
                });
            }


            /*
            =====================================================
            7. SUBMIT FORM (FINAL AJAX - FIXED & SAFE)
            =====================================================
            */
            if (form) {
                form.addEventListener('submit', function(e) {

                    e.preventDefault();

                    // 🔥 Anti double submit
                    if (isSubmitting) return;

                    // 🔥 Validasi KK
                    if (inputKK && inputKK.value.length < 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nomor KK tidak valid',
                            text: 'Minimal 10 digit'
                        });
                        return;
                    }

                    isSubmitting = true;

                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;

                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const formData = new FormData(form);

                    // ✅ ambil CSRF dari input hidden
                    const csrfInput = form.querySelector('input[name="_token"]');

                    if (!csrfInput) {
                        Swal.fire({
                            icon: 'error',
                            title: 'CSRF Error',
                            text: 'Token tidak ditemukan'
                        });
                        return;
                    }

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfInput.value,
                                'Accept': 'application/json'
                            }
                        })
                        .then(async res => {

                            let data;

                            try {
                                data = await res.json();
                            } catch {
                                throw {
                                    message: 'Server tidak mengembalikan JSON'
                                };
                            }

                            if (!res.ok) throw data;

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message || 'Data berhasil disimpan'
                            }).then(() => {

                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                } else {
                                    window.location.reload();
                                }

                            });

                        })
                        .catch(err => {

                            console.error('ERROR:', err);

                            let message = 'Terjadi kesalahan';

                            // 🔥 error validasi Laravel
                            if (err.errors) {
                                const firstError = Object.values(err.errors)[0];
                                if (firstError) message = firstError[0];
                            }

                            if (err.message) {
                                message = err.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: message
                            });

                            isSubmitting = false;
                            if (submitBtn) submitBtn.disabled = false;
                        });

                });
            }

        });
    </script>
@endpush
