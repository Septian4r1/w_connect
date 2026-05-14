<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Buat Password Baru | W_connect</title>

    <link rel="icon" type="image/gif" href="{{ asset('images/logo_w_connect_web.gif') }}">

    <!-- Bootstrap -->
    <link href="{{ asset('tamplate_management/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #198754;
            --radius: 22px;
            --input-radius: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;

            font-family: 'Roboto', sans-serif;

            background:
                linear-gradient(135deg,
                    #0f172a,
                    #1e293b,
                    #334155);

            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========================================
           OVERLAY
        ======================================== */
        .auth-overlay {
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 20px;
        }

        /* ========================================
           CARD
        ======================================== */
        .auth-card {

            width: 100%;
            max-width: 460px;

            border-radius: var(--radius);

            background:
                rgba(255, 255, 255, 0.96);

            backdrop-filter: blur(12px);

            border:
                1px solid rgba(255, 255, 255, 0.2);

            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.15);

            overflow: hidden;

            animation: fadeIn .35s ease;
        }

        @keyframes fadeIn {

            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-body {
            padding: 36px;
        }

        /* ========================================
           ICON
        ======================================== */
        .password-icon {

            width: 78px;
            height: 78px;

            margin: auto;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(135deg,
                    #d1fae5,
                    #bbf7d0);

            box-shadow:
                0 10px 25px rgba(34, 197, 94, .15);
        }

        /* ========================================
           TEXT
        ======================================== */
        .title-auth {

            font-size: clamp(22px, 3vw, 30px);
            font-weight: 700;

            color: #111827;
        }

        .desc-auth {

            font-size: clamp(13px, 2vw, 15px);

            line-height: 1.7;

            color: #6b7280;
        }

        .form-label {

            font-size: 14px;

            margin-bottom: 8px;

            color: #374151;
        }

        /* ========================================
           INPUT
        ======================================== */
        .custom-input {

            height: 52px;

            border-radius: var(--input-radius);

            border: 1px solid #d1d5db;

            padding: 0 16px;

            font-size: 14px;

            transition: .25s ease;
        }

        .custom-input:focus {

            border-color: var(--primary);

            box-shadow:
                0 0 0 4px rgba(25, 135, 84, .12);

            outline: none;
        }

        .custom-btn {

            height: 48px;

            border-radius: var(--input-radius);

            font-size: 14px;
            font-weight: 600;
        }

        .btn-success {

            background: var(--primary);

            border: none;
        }

        .small-text {

            font-size: 12px;

            color: #9ca3af;
        }

        /* ========================================
           PASSWORD TOGGLE
        ======================================== */
        .toggle-password {

            position: absolute;

            top: 50%;
            right: 12px;

            transform: translateY(-50%);

            border: none;
            background: transparent;

            width: 38px;
            height: 38px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition: .2s ease;
        }

        .toggle-password:hover {

            background: rgba(0, 0, 0, .05);
        }

        /* ========================================
           PASSWORD STRENGTH
        ======================================== */
        .password-strength {
            margin-top: 10px;
        }

        .strength-bar {

            width: 100%;
            height: 8px;

            border-radius: 999px;

            background: #e5e7eb;

            overflow: hidden;

            margin-bottom: 6px;
        }

        #strengthFill {

            width: 0%;
            height: 100%;

            border-radius: 999px;

            background: #dc3545;

            transition: .3s ease;
        }

        /* ========================================
           SWEET ALERT
        ======================================== */
        .swal2-container {
            z-index: 999999999 !important;
        }

        /* ========================================
           MOBILE
        ======================================== */
        @media(max-width:576px) {

            .auth-body {
                padding: 22px 18px;
            }

            .password-icon {

                width: 62px;
                height: 62px;
            }

            .custom-input {
                height: 50px;
            }
        }
    </style>

</head>

<body>

    <div class="auth-overlay">

        <div class="auth-card">

            <div class="auth-body">

                <!-- HEADER -->
                <div class="text-center mb-4">

                    <div class="password-icon mb-3">

                        <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" alt="Security Safe"
                            style="
                                width:42px;
                                height:42px;
                                object-fit:contain;
                            ">

                    </div>

                    <h3 class="title-auth mb-2">
                        Buat Password Baru
                    </h3>

                    <p class="desc-auth mb-0">
                        Password baru harus berbeda dari password sebelumnya
                        dan minimal terdiri dari 8 karakter serta mengandung
                        huruf besar.
                    </p>

                </div>

                <!-- FORM -->
                <form id="formChangePassword">

                    @csrf

                    <!-- PASSWORD -->
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Password Baru
                        </label>

                        <div class="position-relative">

                            <input type="password" id="password" name="password" autocomplete="new-password"
                                class="form-control custom-input pe-5" placeholder="Masukkan password baru">

                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">

                                👁️

                            </button>

                        </div>

                        <!-- PASSWORD STRENGTH -->
                        <div class="password-strength">

                            <div class="strength-bar">
                                <div id="strengthFill"></div>
                            </div>

                            <small id="strengthText" class="small-text">

                                Gunakan kombinasi huruf, angka, dan simbol

                            </small>

                        </div>

                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Konfirmasi Password
                        </label>

                        <div class="position-relative">

                            <input type="password" id="password_confirmation" name="password_confirmation"
                                autocomplete="new-password" class="form-control custom-input pe-5"
                                placeholder="Ulangi password baru">

                            <button type="button" class="toggle-password"
                                onclick="togglePassword('password_confirmation', this)">

                                👁️

                            </button>

                        </div>

                        <small id="confirmText" class="small-text mt-2 d-block">
                        </small>

                    </div>

                    <!-- BUTTON -->
                    <button type="button" id="btnSubmit" onclick="submitPassword()"
                        class="btn btn-success custom-btn w-100">

                        Simpan Password Baru

                    </button>

                    <!-- CANCEL -->
                    <a href="{{ route('management.dashboard') }}" class="btn btn-light border custom-btn w-100 mt-2">

                        Batal

                    </a>

                </form>

            </div>

        </div>

    </div>

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

        const btnSubmit =
            document.getElementById(
                'btnSubmit'
            );

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

            if (password.length >= 8)
                strength++;

            if (/[A-Z]/.test(password))
                strength++;

            if (/[0-9]/.test(password))
                strength++;

            if (/[^A-Za-z0-9]/.test(password))
                strength++;

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
                    'text-success small-text mt-2 d-block';

                text.innerHTML =
                    'Password cocok';

            } else {

                text.className =
                    'text-danger small-text mt-2 d-block';

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
            Memproses...
            ` :

                'Simpan Password Baru';
        }

        /**
         * =========================================
         * VALIDASI PASSWORD
         * =========================================
         */
        function validatePassword(
            password,
            confirmation
        ) {

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

            if (!/[0-9]/.test(password)) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Password harus mengandung angka'
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
         * UPDATE PASSWORD
         * =========================================
         */
        async function updatePassword(
            password,
            confirmation
        ) {

            const response = await fetch(

                "{{ route('password.update.password', ['id' => request()->route('id')]) }}",

                {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,

                        'X-Requested-With': 'XMLHttpRequest',

                        'Accept': 'application/json'
                    },

                    body: JSON.stringify({

                        password: password,

                        password_confirmation: confirmation
                    })
                }
            );

            /**
             * DEBUG STATUS
             */
            console.log(
                'STATUS:',
                response.status
            );

            /**
             * CONTENT TYPE
             */
            const contentType =
                response.headers.get(
                    'content-type'
                );

            let data = {};

            /**
             * RESPONSE JSON
             */
            if (
                contentType &&
                contentType.includes(
                    'application/json'
                )
            ) {

                data =
                    await response.json();

            } else {

                const text =
                    await response.text();

                console.error(
                    'Response bukan JSON:',
                    text
                );

                throw new Error(
                    'Response server bukan JSON'
                );
            }

            return {
                response,
                data
            };
        }

        /**
         * =========================================
         * SUBMIT PASSWORD
         * =========================================
         */
        async function submitPassword() {

            if (btnSubmit.disabled) {
                return;
            }

            const password =
                passwordInput.value.trim();

            const confirmation =
                confirmInput.value.trim();

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

                const {
                    response,
                    data
                } = await updatePassword(

                    password,
                    confirmation
                );

                /**
                 * VALIDATION ERROR
                 */
                if (response.status === 422) {

                    let errors = '';

                    if (data.errors) {

                        Object.values(data.errors)
                            .forEach(error => {

                                errors +=
                                    `• ${error[0]}<br>`;
                            });

                    } else {

                        errors =
                            data.message ||
                            'Validasi gagal';
                    }

                    Swal.fire({

                        icon: 'warning',

                        title: 'Validasi Gagal',

                        html: errors
                    });

                    return;
                }

                /**
                 * SESSION INVALID
                 */
                if (
                    response.status === 401 ||
                    response.status === 403
                ) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Session Berakhir',

                        text: data.message ||
                            'Session reset password sudah tidak valid'
                    });

                    return;
                }

                /**
                 * ERROR UMUM
                 */
                if (
                    !response.ok ||
                    !data.status
                ) {

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

                    text: data.message ||
                        'Password berhasil diubah',

                    timer: 2000,

                    showConfirmButton: false
                });

                setTimeout(() => {

                    window.location.href =
                        data.redirect;

                }, 2000);

            } catch (error) {

                console.error(error);

                Swal.fire({

                    icon: 'error',

                    title: 'Server Error',

                    text: error.message ||
                        'Tidak dapat menghubungi server'
                });

            } finally {

                setLoading(false);
            }
        }

        /**
         * =========================================
         * ENTER SUBMIT
         * =========================================
         */
        document.getElementById(
                'formChangePassword'
            )
            .addEventListener(
                'keypress',
                function(e) {

                    if (e.key === 'Enter') {

                        e.preventDefault();

                        submitPassword();
                    }
                }
            );
    </script>

</body>

</html>
