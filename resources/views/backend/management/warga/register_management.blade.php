@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid py-3">

        <div class="row justify-content-center">


            <div class="card modern-card">

                {{-- HEADER --}}
                <div class="card-header text-center bg-white border-0 pb-0">
                    <h5 class="fw-bold mb-1">Tambah Warga</h5>
                    <small class="text-muted">Citra Swarna Riverside</small>
                </div>

                {{-- BODY --}}
                <div class="card-body pt-3">

                    <form id="registerForm" method="POST" action="{{ route('store_management_warga') }}">
                        @csrf

                        {{-- BLOK --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Blok</label>
                            <select name="block_id"
                                class="form-control modern-input @error('block_id') is-invalid @enderror" required>
                                <option value="">Pilih Blok</option>

                                @foreach ($blocks as $b)
                                    <option value="{{ $b->id }}" {{ old('block_id') == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_blok }}
                                    </option>
                                @endforeach

                            </select>
                            @error('block_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- NOMOR RUMAH --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Nomor Rumah</label>
                            <input type="text" name="nomor_rumah" value="{{ old('nomor_rumah') }}"
                                class="form-control modern-input @error('nomor_rumah') is-invalid @enderror"
                                placeholder="Contoh: A1/1" required>

                            @error('nomor_rumah')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- ALAMAT --}}
                        <div class="form-group mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" rows="2"
                                class="form-control modern-input @error('alamat_lengkap') is-invalid @enderror" placeholder="Opsional">{{ old('alamat_lengkap') }}</textarea>

                            @error('alamat_lengkap')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- GRID --}}
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label>Desa</label>
                                <input type="text" class="form-control modern-input" value="Bojong" readonly>
                            </div>

                            <div class="col-6 mb-3">
                                <label>Kecamatan</label>
                                <input type="text" class="form-control modern-input" value="Klapanunggal" readonly>
                            </div>
                        </div>

                        {{-- STATUS HUNIAN --}}
                        <div class="form-group mb-3">
                            <label>Status Hunian</label>
                            <select name="status_hunian"
                                class="form-control modern-input @error('status_hunian') is-invalid @enderror" required>

                                <option value="">Pilih Status</option>

                                @foreach ($statusHunianOptions as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_hunian') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach

                            </select>

                            @error('status_hunian')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        {{-- BUTTON --}}
                        <button type="submit" class="btn btn-primary w-100 modern-btn">
                            <i class="bx bx-user-plus"></i> Daftarkan Warga
                        </button>

                    </form>

                </div>
            </div>


        </div>

    </div>

    @include('backend.management.warga.style')
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById('registerForm');
            let isSubmitting = false; // 🔒 anti double submit

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // =========================
                // 🚫 PREVENT DOUBLE SUBMIT
                // =========================
                if (isSubmitting) return;
                isSubmitting = true;

                try {

                    // =========================
                    // 1. CONFIRMATION MODAL
                    // =========================
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

                    // =========================
                    // 2. LOADING STATE
                    // =========================
                    Swal.fire({
                        width: 260,
                        title: 'Menyimpan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'swal-popup-mini',
                            title: 'swal-title-mini'
                        }
                    });

                    // =========================
                    // 3. SEND REQUEST
                    // =========================
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

                    // =========================
                    // 4. SUCCESS RESPONSE
                    // =========================
                    if (response.ok) {

                        await Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message || 'Data berhasil disimpan',
                            showConfirmButton: false,
                            timer: 1200,
                            allowOutsideClick: false,
                            customClass: {
                                popup: 'swal-popup-mini',
                                title: 'swal-title-mini'
                            }
                        });

                        // 🔥 smooth redirect setelah alert selesai
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }

                        return;
                    }

                    // =========================
                    // 5. VALIDATION / BUSINESS ERROR
                    // =========================
                    let errorMessage = '';

                    if (data.errors) {
                        Object.values(data.errors).forEach(errArr => {
                            errorMessage += `• ${errArr[0]}<br>`;
                        });
                    } else {
                        errorMessage = data.message || 'Terjadi kesalahan pada sistem';
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

                    // =========================
                    // 6. SYSTEM ERROR (NETWORK / SERVER DOWN)
                    // =========================
                    await Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal-popup-mini',
                            title: 'swal-title-mini'
                        }
                    });

                } finally {
                    // =========================
                    // RESET STATE
                    // =========================
                    isSubmitting = false;
                }
            });

        });
    </script>

    <style>
        /* =========================
       SWEETALERT PRO MINI STYLE
    ========================= */

        .swal-popup-mini {
            border-radius: 14px !important;
            padding: 1rem !important;
            font-size: 13px !important;
        }

        .swal-title-mini {
            font-size: 15px !important;
            font-weight: 600;
        }

        /* isi text */
        .swal2-html-container {
            font-size: 12px !important;
            margin-top: 5px !important;
            line-height: 1.4;
        }

        /* tombol */
        .swal2-confirm,
        .swal2-cancel {
            font-size: 12px !important;
            padding: 6px 14px !important;
            border-radius: 8px !important;
        }

        /* loader kecil & clean */
        .swal2-loader {
            width: 2em !important;
            height: 2em !important;
        }
    </style>
@endpush
