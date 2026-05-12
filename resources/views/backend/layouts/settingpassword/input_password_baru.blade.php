@extends('backend.layouts.app')

@section('content')
    {{-- =========================================
        FULL SCREEN PASSWORD RESET OVERLAY
    ========================================== --}}
    <div class="auth-overlay">

        <div class="auth-card card border-0 shadow-lg">

            <div class="card-body p-4 p-md-5">

                {{-- =========================================
                    HEADER
                ========================================== --}}
                <div class="text-center mb-4">

                    <div class="password-icon mb-3">
                        🔒
                    </div>

                    <h3 class="fw-bold mb-2">
                        Buat Password Baru
                    </h3>

                    <p class="text-muted small mb-0">
                        Password baru harus berbeda dari password sebelumnya
                        dan minimal terdiri dari 8 karakter terdiri dari Huruf besar.
                    </p>

                </div>

                {{-- =========================================
                    FORM
                ========================================== --}}
                <form id="formChangePassword">

                    @csrf

                    {{-- PASSWORD --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Password Baru
                        </label>

                        <div class="position-relative">

                            {{-- <input type="hidden" id="token" value="{{ $token }}"> --}}
                            <input type="password" id="password" class="form-control custom-input pe-5"
                                placeholder="Masukkan password baru">

                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">

                                👁️

                            </button>

                        </div>

                        {{-- PASSWORD STRENGTH --}}
                        <div class="password-strength mt-2">

                            <div class="strength-bar">
                                <div id="strengthFill"></div>
                            </div>

                            <small id="strengthText" class="text-muted">
                                Gunakan kombinasi huruf, angka, dan simbol
                            </small>

                        </div>

                    </div>

                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Konfirmasi Password
                        </label>

                        <div class="position-relative">

                            <input type="password" id="password_confirmation" class="form-control custom-input pe-5"
                                placeholder="Ulangi password baru">

                            <button type="button" class="toggle-password"
                                onclick="togglePassword('password_confirmation', this)">

                                👁️

                            </button>

                        </div>

                        <small id="confirmText" class="text-muted"></small>

                    </div>

                    {{-- BUTTON SUBMIT --}}
                    <button type="button" id="btnSubmit" class="btn btn-success btn-sm custom-btn w-100 fw-semibold"
                        onclick="submitPassword()">

                        Simpan Password Baru

                    </button>

                    {{-- BUTTON CANCEL --}}
                    <a href="{{ route('management.dashboard') }}" class="btn btn-light btn-sm border custom-btn w-100 mt-2">

                        Batal

                    </a>

                </form>

            </div>
        </div>

    </div>

    {{-- =========================================
    STYLE
========================================== --}}
    <style>
        /* =========================================
                                ROOT
                            ========================================== */
        :root {
            --auth-radius: 22px;
            --input-radius: 14px;
            --primary-color: #198754;
        }

        /* =========================================
                                OVERLAY
                            ========================================== */
        .auth-overlay {
            position: fixed;
            inset: 0;

            z-index: 999999;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 18px;

            overflow-y: auto;

            background: rgba(0, 0, 0, .35);

            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* =========================================
                                DISABLE BACKGROUND
                            ========================================== */
        body {
            overflow: hidden;
            touch-action: manipulation;
        }

        .wrapper,
        .main-wrapper,
        .page-wrapper {
            pointer-events: none !important;
            user-select: none !important;
        }

        /* =========================================
                                CARD
                            ========================================== */
        .auth-card {
            width: 100%;
            max-width: 460px;

            border-radius: var(--auth-radius);

            overflow: hidden;

            position: relative;

            z-index: 9999999;

            pointer-events: auto !important;

            animation: fadeInUp .35s ease;
        }

        /* =========================================
                                ANIMATION
                            ========================================== */
        @keyframes fadeInUp {

            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =========================================
                                CARD BODY
                            ========================================== */
        .auth-card .card-body {
            padding: 32px;
        }

        /* =========================================
                                ICON
                            ========================================== */
        .password-icon {
            width: 78px;
            height: 78px;

            margin: auto;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 34px;

            background: linear-gradient(135deg,
                    #d1fae5,
                    #bbf7d0);

            box-shadow:
                0 10px 25px rgba(34, 197, 94, .15);
        }

        /* =========================================
                                INPUT
                            ========================================== */
        .custom-input {
            height: 54px;

            border-radius: var(--input-radius);

            border: 1px solid #dcdcdc;

            transition: .25s ease;

            font-size: 15px;

            padding-left: 14px;

            background: #fff;
        }

        .custom-input:focus {
            border-color: var(--primary-color);

            box-shadow:
                0 0 0 4px rgba(25, 135, 84, .12);
        }

        .custom-input::placeholder {
            color: #adb5bd;
            font-size: 14px;
        }

        /* =========================================
                                TOGGLE PASSWORD
                            ========================================== */
        .toggle-password {
            position: absolute;

            top: 50%;
            right: 12px;

            transform: translateY(-50%);

            border: 0;
            background: transparent;

            width: 38px;
            height: 38px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;

            cursor: pointer;

            z-index: 20;

            transition: .2s ease;
        }

        .toggle-password:hover {
            background: rgba(0, 0, 0, .05);
        }

        /* =========================================
                                BUTTON
                            ========================================== */
        .custom-btn {
            height: 45px;

            border-radius: var(--input-radius);

            font-size: 15px;

            transition: .2s ease;
        }

        .custom-btn:hover {
            transform: translateY(-1px);
        }

        .custom-btn:disabled {
            opacity: .8;
            cursor: not-allowed;
        }

        /* =========================================
                                PASSWORD STRENGTH
                            ========================================== */
        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {
            width: 100%;
            height: 8px;

            border-radius: 999px;

            background: #e9ecef;

            overflow: hidden;

            margin-bottom: 6px;
        }

        #strengthFill {
            height: 100%;
            width: 0%;

            border-radius: 999px;

            transition: .3s ease;

            background: #dc3545;
        }

        /* =========================================
                        SWEET ALERT
                    ========================================== */
        .swal2-container.swal2-center,
        .swal2-container.swal2-backdrop-show {
            z-index: 99999999 !important;
        }

        .swal2-popup {
            border-radius: 20px !important;
        }

        /* =========================================
                                TABLET
                            ========================================== */
        @media (max-width: 768px) {

            .auth-overlay {
                padding: 16px;
                align-items: center;
            }

            .auth-card {
                max-width: 100%;
            }

            .auth-card .card-body {
                padding: 26px;
            }

            .password-icon {
                width: 72px;
                height: 72px;

                font-size: 30px;
            }

            h3 {
                font-size: 24px;
            }
        }

        /* =========================================
                                MOBILE
                            ========================================== */
        @media (max-width: 576px) {

            .auth-overlay {
                padding: 14px;

                align-items: flex-start;

                overflow-y: auto;
            }

            .auth-card {
                margin-top: 20px;

                border-radius: 20px;
            }

            .auth-card .card-body {
                padding: 22px 18px;
            }

            .password-icon {
                width: 66px;
                height: 66px;

                font-size: 28px;
            }

            h3 {
                font-size: 21px;
            }

            .text-muted.small {
                font-size: 13px !important;
                line-height: 1.6;
            }

            .custom-input {
                height: 50px;

                font-size: 14px;
            }

            .custom-btn {
                height: 45px;

                font-size: 14px;
            }

            .toggle-password {
                width: 34px;
                height: 34px;

                font-size: 16px;
            }

            .strength-bar {
                height: 7px;
            }
        }

        /* =========================================
                                EXTRA SMALL DEVICE
                            ========================================== */
        @media (max-width: 380px) {

            .auth-card .card-body {
                padding: 18px 15px;
            }

            h3 {
                font-size: 19px;
            }

            .custom-input {
                height: 48px;
            }

            .custom-btn {
                height: 45px;
            }
        }
    </style>

    {{-- =========================================
        SCRIPT
    ========================================== --}}
    @push('scripts')
        <script>
            /**
             * =========================================
             * ELEMENT
             * =========================================
             */
            const passwordInput =
                document.getElementById('password');

            const confirmInput =
                document.getElementById(
                    'password_confirmation'
                );

            // const tokenInput =
            //     document.getElementById('token');

            const btnSubmit =
                document.getElementById('btnSubmit');

            /**
             * =========================================
             * ENTER SUBMIT
             * =========================================
             */
            document.addEventListener('keydown', function(e) {

                if (e.key === 'Enter') {

                    e.preventDefault();

                    submitPassword();
                }
            });

            /**
             * =========================================
             * TOGGLE PASSWORD
             * =========================================
             */
            function togglePassword(id, el) {

                const input =
                    document.getElementById(id);

                if (input.type === 'password') {

                    input.type = 'text';
                    el.innerHTML = '🙈';

                } else {

                    input.type = 'password';
                    el.innerHTML = '👁️';
                }
            }

            /**
             * =========================================
             * PASSWORD STRENGTH
             * =========================================
             */
            passwordInput.addEventListener(
                'input',
                checkStrength
            );

            confirmInput.addEventListener(
                'input',
                checkConfirmation
            );

            function checkStrength() {

                const password =
                    passwordInput.value;

                const fill =
                    document.getElementById(
                        'strengthFill'
                    );

                const text =
                    document.getElementById(
                        'strengthText'
                    );

                let strength = 0;

                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                fill.style.width =
                    (strength * 25) + '%';

                switch (strength) {

                    case 0:
                    case 1:

                        fill.style.background =
                            '#dc3545';

                        text.innerHTML =
                            'Password lemah';

                        break;

                    case 2:

                        fill.style.background =
                            '#ffc107';

                        text.innerHTML =
                            'Password sedang';

                        break;

                    case 3:

                        fill.style.background =
                            '#0dcaf0';

                        text.innerHTML =
                            'Password bagus';

                        break;

                    case 4:

                        fill.style.background =
                            '#198754';

                        text.innerHTML =
                            'Password sangat kuat';

                        break;
                }
            }

            /**
             * =========================================
             * CHECK CONFIRMATION
             * =========================================
             */
            function checkConfirmation() {

                const password =
                    passwordInput.value;

                const confirm =
                    confirmInput.value;

                const text =
                    document.getElementById(
                        'confirmText'
                    );

                if (!confirm.length) {

                    text.innerHTML = '';
                    return;
                }

                if (password === confirm) {

                    text.className =
                        'text-success small';

                    text.innerHTML =
                        'Password cocok';

                } else {

                    text.className =
                        'text-danger small';

                    text.innerHTML =
                        'Password tidak cocok';
                }
            }

            /**
             * =========================================
             * BUTTON LOADING
             * =========================================
             */
            function setLoading(state = true) {

                btnSubmit.disabled = state;

                btnSubmit.innerHTML = state ?

                    `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Menyimpan...
                ` :

                    'Simpan Password Baru';
            }

            /**
             * =========================================
             * VALIDASI PASSWORD
             * =========================================
             */
            function validatePassword(password, confirmation) {

                if (!password) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Password wajib diisi'
                    });

                    return false;
                }

                if (password.length < 8) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Password minimal 8 karakter'
                    });

                    return false;
                }

                if (!/[A-Z]/.test(password)) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Password harus mengandung huruf besar'
                    });

                    return false;
                }

                if (password !== confirmation) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Konfirmasi password tidak cocok'
                    });

                    return false;
                }

                return true;
            }

            /**
             * =========================================
             * SUBMIT PASSWORD
             * =========================================
             */
            async function submitPassword() {

                /**
                 * PREVENT DOUBLE CLICK
                 */
                if (btnSubmit.disabled) {
                    return;
                }

                const password =
                    passwordInput.value.trim();

                const confirmation =
                    confirmInput.value.trim();

                //    const token = tokenInput.value?.trim();

                /**
                 * VALIDASI
                 */
                if (
                    !validatePassword(
                        password,
                        confirmation
                    )
                ) {
                    return;
                }

                setLoading(true);

                try {

                    const response = await fetch(
                        "{{ route('management.password.update') }}", {

                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',

                                'X-CSRF-TOKEN': document
                                    .querySelector(
                                        'meta[name="csrf-token"]'
                                    )
                                    .getAttribute('content'),

                                'X-Requested-With': 'XMLHttpRequest',

                                'Accept': 'application/json'
                            },

                            body: JSON.stringify({
                                password: password,
                                password_confirmation: confirmation
                            })
                        });

                    /**
                     * HANDLE NON JSON
                     */
                    let data;

                    try {

                        data =
                            await response.json();

                    } catch {

                        throw new Error(
                            'Response server tidak valid'
                        );
                    }

                    /**
                     * VALIDATION ERROR
                     */
                    if (response.status === 422) {

                        let errors = '';

                        Object.values(data.errors)
                            .forEach(error => {

                                errors +=
                                    `• ${error[0]}<br>`;
                            });

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            html: errors
                        });

                        return;
                    }

                    /**
                     * TOKEN INVALID / EXPIRED
                     */
                    if (
                        response.status === 401 ||
                        response.status === 403
                    ) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Session Berakhir',
                            text: data.message ||
                                'Token reset password sudah tidak valid'
                        }).then(() => {

                            window.location.href =
                                "{{ route('management.change_password') }}";
                        });

                        return;
                    }

                    /**
                     * ERROR UMUM
                     */
                    if (!response.ok || !data.status) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message ||
                                'Terjadi kesalahan'
                        });

                        return;
                    }

                    /**
                     * SUCCESS
                     */
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Password berhasil diperbarui',
                        timer: 1800,
                        showConfirmButton: false
                    });

                    setTimeout(() => {

                        window.location.href =
                            data.redirect;

                    }, 1800);

                } catch (error) {

                    console.error(error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: error.message ||
                            'Tidak dapat terhubung ke server'
                    });

                } finally {

                    setLoading(false);
                }
            }
        </script>
    @endpush
@endsection
