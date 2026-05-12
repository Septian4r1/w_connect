@extends('backend.layouts.app')

@section('content')
    {{-- 🔥 FULL SCREEN OVERLAY (LOCK SEMUA UI) --}}
    <div class="auth-overlay">

        <div class="auth-card card shadow-lg border-0">

            <div class="card-body p-4">

                {{-- TITLE --}}
                <div class="text-center mb-3">
                    <h5 class="fw-bold mb-1">Ganti Password</h5>
                    <small class="text-muted">
                        Verifikasi terlebih dahulu sebelum melanjutkan
                    </small>
                </div>

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" class="form-control" placeholder="Masukkan nama lengkap">
                </div>

                {{-- CAPTCHA --}}
                <div class="text-center mb-3 p-3 rounded bg-light">

                    <small class="text-muted d-block mb-2">
                        Selesaikan penjumlahan berikut
                    </small>

                    <div id="math-question" class="fw-bold fs-5"></div>

                    <input type="number" id="answer" class="form-control mt-3 text-center" placeholder="Jawaban"
                        oninput="checkAnswer()">

                </div>

                {{-- BUTTON VERIFIKASI --}}
                <button id="btnVerify" class="btn btn-success w-100" disabled onclick="verify()">
                    Verifikasi & Lanjut
                </button>

                {{-- BATAL --}}
                <a href="{{ route('management.dashboard') }}" class="btn btn-outline-secondary w-100 mt-2">
                    Batal
                </a>

                <div id="msg" class="text-center mt-2 small"></div>

            </div>
        </div>

    </div>

    {{-- STYLE --}}
    <style>
        /* 🔥 LOCK FULL SCREEN TERMASUK SIDEBAR */
        .auth-overlay {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            justify-content: center;
            align-items: center;

            /* blur seluruh halaman */
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);

            /* 🔥 ini penting: matiin klik ke belakang */
            pointer-events: all;
        }

        /* card tetap interaktif */
        .auth-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            z-index: 1000000;
        }

        /* optional: matiin interaksi halaman belakang */
        .wrapper {
            pointer-events: none;
            user-select: none;
        }

        .swal2-popup {
            z-index: 10000001 !important;
        }
    </style>


@endsection

@include('backend.layouts.settingpassword.script_settingPassword')
