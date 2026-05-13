<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- 🔥 WAJIB UNTUK AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- loader-->
    <link href="{{ asset('tamplate_management/assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('tamplate_management/assets/js/pace.min.js') }}"></script>

    <!--plugins-->
    <link href="{{ asset('tamplate_management/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('tamplate_management/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('tamplate_management/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />

    <!-- CSS Files -->
    <link href="{{ asset('tamplate_management/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tamplate_management/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('tamplate_management/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('tamplate_management/assets/css/icons.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!--Theme Styles-->
    <link href="{{ asset('tamplate_management/assets/css/dark-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('tamplate_management/assets/css/semi-dark.css') }}" rel="stylesheet" />
    <link href="{{ asset('tamplate_management/assets/css/header-colors.css') }}" rel="stylesheet" />

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Forgot Password - W_connect Management</title>

    <style>
        :root {
            --auth-radius: 22px;
            --input-radius: 14px;
            --primary-color: #198754;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
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

            /* BACKGROUND BLUR TANPA GAMBAR */
            background: rgba(15, 23, 42, 0.55);

            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        /* =========================================
            CARD
        ========================================== */
        .auth-card {
            width: 100%;
            max-width: 460px;

            border-radius: var(--auth-radius);

            overflow: hidden;

            background: rgba(255, 255, 255, 0.92);

            border: 1px solid rgba(255, 255, 255, 0.2);

            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            position: relative;

            z-index: 9999999;

            animation: fadeInUp .35s ease;
        }

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
            BUTTON
        ========================================== */
        .custom-btn {
            height: 48px;

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

</head>

<body>

    {{-- =========================================
        FULL SCREEN FORGOT PASSWORD OVERLAY
    ========================================== --}}
    <div class="auth-overlay">

        <div class="auth-card shadow-lg">

            <div class="card-body p-4 p-md-5">

                {{-- =========================================
                    HEADER
                ========================================== --}}
                <div class="text-center mb-4">

                    <div class="password-icon mb-3">
                        📧
                    </div>

                    <h3 class="fw-bold mb-2">
                        Forgot Password
                    </h3>

                    <p class="text-muted small mb-0">
                        Masukkan alamat email yang terdaftar.
                        Kami akan mengirimkan link reset password ke email Anda.
                    </p>

                </div>

                {{-- =========================================
                    FORM
                ========================================== --}}
                <form id="formForgotPassword">

                    @csrf

                    {{-- EMAIL --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Alamat Email
                        </label>

                        <input type="email" id="email" class="form-control custom-input"
                            placeholder="Masukkan email Anda">

                        <small class="text-muted">
                            Contoh: user@gmail.com
                        </small>

                    </div>

                    {{-- BUTTON SUBMIT --}}
                    <button type="button" id="btnSubmit" class="btn btn-success custom-btn w-100 fw-semibold"
                        onclick="submitForgotPassword()">

                        Kirim Link Reset Password

                    </button>

                    {{-- BUTTON CANCEL --}}
                    <a href="{{ route('showlogin_management') }}"
                        class="btn btn-light border custom-btn w-100 mt-2 d-flex align-items-center justify-content-center">

                        Kembali ke Login

                    </a>

                </form>

            </div>
        </div>

    </div>

    {{-- =========================================
        SCRIPT
    ========================================== --}}
    <script>
        /**
         * =========================================
         * ELEMENT
         * =========================================
         */
        const emailInput =
            document.getElementById('email');

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

                submitForgotPassword();
            }
        });

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
            Mengirim...
            ` :

                'Kirim Link Reset Password';
        }

        /**
         * =========================================
         * VALIDATE EMAIL
         * =========================================
         */
        function validateEmail(email) {

            const regex =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            return regex.test(email);
        }

        /**
         * =========================================
         * SUBMIT FORGOT PASSWORD
         * =========================================
         */
        async function submitForgotPassword() {

            if (btnSubmit.disabled) {
                return;
            }

            const email =
                emailInput.value.trim();

            /**
             * VALIDASI
             */
            if (!email) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Email wajib diisi'
                });

                return;
            }

            if (!validateEmail(email)) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Format email tidak valid'
                });

                return;
            }

            setLoading(true);

            try {

                const response = await fetch(
                    "", {

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
                            email: email
                        })
                    });

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
                 * ERROR
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
                    text: 'Link reset password berhasil dikirim ke email Anda',
                    confirmButtonText: 'OK'
                });

                emailInput.value = '';

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

</body>

</html>
