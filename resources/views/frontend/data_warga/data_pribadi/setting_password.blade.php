@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Data Diri')

@section('content')

    <style>
        /* 🔥 SWEET ALERT MOBILE */
        .swal-mobile {
            width: 300px !important;
            padding: 14px !important;
            border-radius: 16px !important;
        }

        .swal-title-mobile {
            font-size: 15px !important;
        }

        .swal-html-mobile {
            font-size: 13px !important;
        }

        .swal-input-mobile {
            height: 38px !important;
            font-size: 14px !important;
            border-radius: 10px !important;
        }

        .swal-btn-mobile {
            font-size: 13px !important;
            padding: 6px 14px !important;
            border-radius: 10px !important;
        }

        .app-content {
            overflow: hidden !important;
        }

        .center-wrapper {
            height: calc(100vh - 145px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-wrapper {
            background: white;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0px 3px 15px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 320px;
        }

        .form-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .label-mobile {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .input-mobile {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            border: 1px solid #e2e2e2;
            margin-bottom: 15px;
            font-size: 14px;
            background: #fafafa;
        }

        .btn-mobile {
            padding: 9px;
            border-radius: 9px;
            border: none;
            background: #1abc9c;
            color: white;
            font-weight: 400;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        .btn-mobile:active {
            transform: scale(0.97);
        }
    </style>

    <div class="center-wrapper">
        <div class="form-wrapper">

            <div class="form-title">
                Verifikasi Data Diri
            </div>

            <form id="formVerifikasi" method="POST" action="{{ route('data.Verify') }}">
                @csrf

                {{-- <label class="label-mobile">No Rumah</label>
                <input type="text" name="no_rumah" class="input-mobile" placeholder="Contoh: A1/01" required> --}}

                <label class="label-mobile">NIK KTP</label>
                <input type="text" name="nik" class="input-mobile" placeholder="16 digit NIK" inputmode="numeric"
                    required>

                <button type="submit" class="btn-mobile mt-2">
                    Kirim
                </button>


            </form>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Ambil form verifikasi
            const form = document.getElementById("formVerifikasi");

            // Flag untuk mencegah infinite submit loop
            let allowSubmit = false;

            form.addEventListener("submit", function(e) {

                /**
                 * ✅ Jika sudah lolos verifikasi sebelumnya
                 * maka biarkan submit normal ke Laravel
                 */
                if (allowSubmit) {
                    return true;
                }

                /**
                 * ❗ Stop submit normal sementara
                 * supaya SweetAlert tampil dulu
                 */
                e.preventDefault();

                /**
                 * 🔥 Generate soal random
                 * (Anti bot / anti spam sederhana)
                 */
                let satuan = Math.floor(Math.random() * 9) + 1;
                let puluhan = (Math.floor(Math.random() * 9) + 1) * 10;
                let ratusan = (Math.floor(Math.random() * 9) + 1) * 100;

                let hasil = satuan + puluhan + ratusan;

                /**
                 * 🔥 Popup verifikasi matematika
                 */
                Swal.fire({
                    title: 'Verifikasi Penjumlahan',
                    html: `
                <div style="font-size:17px;font-weight:700;margin-top:10px;">
                    ${ratusan} + ${puluhan} + ${satuan} = ?
                </div>
                <input type="number" id="jawaban"
                    class="swal2-input swal-input-mobile"
                    placeholder="Masukkan hasil">
            `,
                    confirmButtonText: 'Verifikasi',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    focusConfirm: false,
                    customClass: {
                        popup: 'swal-mobile',
                        title: 'swal-title-mobile',
                        htmlContainer: 'swal-html-mobile',
                        confirmButton: 'swal-btn-mobile'
                    },

                    /**
                     * ✅ Validasi sebelum popup ditutup
                     */
                    preConfirm: () => {

                        let userJawab = document.getElementById('jawaban').value;

                        if (userJawab === "") {
                            Swal.showValidationMessage('Masukkan hasil');
                            return false;
                        }

                        if (parseInt(userJawab) !== hasil) {
                            Swal.showValidationMessage('Jawaban salah ❌');
                            return false;
                        }

                        return true;
                    }

                }).then((result) => {

                    /**
                     * ✅ Jika jawaban benar
                     */
                    if (result.isConfirmed) {

                        /**
                         * 🔥 Loading feel mobile UX
                         */
                        Swal.fire({
                            title: 'Memverifikasi...',
                            allowOutsideClick: false,
                            customClass: {
                                popup: 'swal-mobile'
                            },
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        /**
                         * 🔥 Aktifkan submit
                         */
                        allowSubmit = true;

                        /**
                         * 🔥 Submit form SECARA BENAR
                         * requestSubmit = trigger validation + event normal
                         * BUKAN bypass seperti form.submit()
                         */
                        setTimeout(() => {
                            form.requestSubmit();
                        }, 600);
                    }

                });

            });

        });
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Verifikasi gagal',
                text: "{{ session('error') }}",
                customClass: {
                    popup: 'swal-mobile'
                }
            });
        </script>
    @endif

@endsection
