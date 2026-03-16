@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Password Baru')

@section('content')

    <style>
        /* ===== SWEET ALERT MOBILE SIZE ===== */
        .swal-mobile {
            width: 280px !important;
            padding: 14px !important;
            border-radius: 16px !important;
        }

        .swal2-title {
            font-size: 15px !important;
        }

        .swal2-html-container {
            font-size: 13px !important;
        }

        .swal2-confirm {
            font-size: 13px !important;
            padding: 6px 14px !important;
            border-radius: 10px !important;
        }

        .swal2-loader {
            transform: scale(.8);
        }

        /* ===== SCROLL LOCK TOTAL ===== */
        .app-content {
            overflow: hidden !important;
            position: relative;
        }

        /* ===== CENTER ABSOLUTE ===== */
        .center-wrapper {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            padding: 20px;
        }

        /* ===== FORM CARD ===== */
        .form-wrapper {
            background: white;
            border-radius: 22px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
            width: 100%;
            max-width: 340px;
            margin: auto;
            animation: fadeUp .4s ease;
        }

        @keyframes fadeUp {
            from {
                transform: translateY(30px);
                opacity: 0
            }

            to {
                transform: translateY(0);
                opacity: 1
            }
        }

        /* ===== TITLE ===== */
        .form-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            text-align: center;
        }

        /* ===== INPUT ===== */
        .label-mobile {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-mobile {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #e5e5e5;
            margin-bottom: 8px;
            font-size: 14px;
            background: #fafafa;
            transition: .2s;
        }

        .input-mobile:focus {
            border-color: #1abc9c;
            background: white;
        }

        /* ===== EYE ICON MODERN ===== */
        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            cursor: pointer;
            color: #9e9e9e;
            transition: .25s;
        }

        .eye-icon.active {
            color: #1abc9c;
        }

        .eye-icon:hover {
            transform: translateY(-50%) scale(1.15);
        }

        /* ===== MATCH TEXT ===== */
        .match-text {
            font-size: 12px;
            margin-bottom: 10px;
            min-height: 18px;
        }

        /* ===== STRENGTH ===== */
        .strength-bar {
            height: 6px;
            border-radius: 10px;
            background: #eee;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: .3s;
        }

        /* ===== BUTTON ===== */
        .btn-mobile {
            padding: 11px;
            border-radius: 12px;
            border: none;
            background: #1abc9c;
            color: white;
            font-size: 15px;
            width: 100%;
            transition: .2s;
        }

        .btn-mobile:active {
            transform: scale(.96);
        }
    </style>

    <div class="center-wrapper">
        <div class="form-wrapper">


            <div class="form-title">
                Buat Password Baru
            </div>

            <form method="POST" action="{{ route('password.simpan') }}">
                @csrf

                <label class="label-mobile">Password Baru</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="input-mobile"
                        placeholder="Masukkan password baru" required>
                    <i class="bi bi-eye eye-icon" id="togglePass"></i>
                </div>

                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>

                <label class="label-mobile">Ulangi Password</label>
                <div class="input-group">
                    <input type="password" id="confirm" name="password_confirmation" class="input-mobile"
                        placeholder="Ulangi password" required>
                    <i class="bi bi-eye eye-icon" id="toggleConfirm"></i>
                </div>

                <div id="matchText" class="match-text"></div>

                <button type="submit" class="btn-mobile">
                    Simpan Password
                </button>

            </form>

        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const pass = document.getElementById('password');
            const confirmPass = document.getElementById('confirm');
            const matchText = document.getElementById('matchText');
            const strengthFill = document.getElementById('strengthFill');
            const togglePass = document.getElementById('togglePass');
            const toggleConfirm = document.getElementById('toggleConfirm');
            const form = document.querySelector("form");

            /* ===============================
               TOGGLE PASSWORD
            =============================== */
            function togglePassword(input, icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.add('active');
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('active');
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }

            togglePass.addEventListener('click', () => togglePassword(pass, togglePass));
            toggleConfirm.addEventListener('click', () => togglePassword(confirmPass, toggleConfirm));

            /* ===============================
               REALTIME MATCH
            =============================== */
            confirmPass.addEventListener('input', () => {
                if (confirmPass.value === pass.value) {
                    matchText.innerHTML = "Password cocok ✅";
                    matchText.style.color = "green";
                } else {
                    matchText.innerHTML = "Password tidak cocok ❌";
                    matchText.style.color = "red";
                }
            });

            /* ===============================
               PASSWORD STRENGTH
            =============================== */
            pass.addEventListener('input', () => {
                let val = pass.value;
                let score = 0;

                if (val.length >= 6) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;

                let percent = score * 25;
                strengthFill.style.width = percent + "%";

                if (percent <= 25) strengthFill.style.background = "red";
                else if (percent <= 50) strengthFill.style.background = "orange";
                else if (percent <= 75) strengthFill.style.background = "#f1c40f";
                else strengthFill.style.background = "green";
            });

            /* ===============================
               SWEET ALERT SUBMIT LOADING
            =============================== */
            let allowSubmit = false;

            form.addEventListener("submit", function(e) {

                if (allowSubmit) return true;

                e.preventDefault();

                Swal.fire({
                    title: 'Mohon menunggu...',
                    text: 'Sedang menyimpan password',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        popup: 'swal-mobile'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                allowSubmit = true;

                setTimeout(() => {
                    form.requestSubmit();
                }, 600);
            });

        });
    </script>

@endsection
