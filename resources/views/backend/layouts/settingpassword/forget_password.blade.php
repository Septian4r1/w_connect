<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Forgot Password | W_connect</title>
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

            position: relative;
        }

        /* ========================================
           CARD
        ======================================== */
        .auth-card {
            width: 100%;
            max-width: 460px;

            border-radius: var(--radius);

            background: rgba(255, 255, 255, 0.96);

            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);

            border: 1px solid rgba(255, 255, 255, 0.2);

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

            font-size: 34px;

            background:
                linear-gradient(135deg,
                    #d1fae5,
                    #bbf7d0);

            box-shadow:
                0 10px 25px rgba(34, 197, 94, .15);
        }

        /* ========================================
           TYPOGRAPHY
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
            height: 50px;

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

        .custom-input::placeholder {
            color: #9ca3af;
            font-size: 13px;
        }

        /* ========================================
           BUTTON
        ======================================== */
        .custom-btn {
            height: 48px;

            border-radius: var(--input-radius);

            font-size: 14px;
            font-weight: 600;

            transition: .2s ease;
        }

        .custom-btn:hover {
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--primary);
            border: none;
        }

        /* ========================================
           SMALL TEXT
        ======================================== */
        .small-text {
            font-size: 12px;
            color: #9ca3af;
        }

        /* ========================================
           SWEET ALERT
        ======================================== */
        .swal2-container {
            z-index: 999999999 !important;
        }

        .swal2-popup {
            border-radius: 18px !important;
        }

        /* ========================================
           TABLET
        ======================================== */
        @media (max-width: 768px) {

            .auth-body {
                padding: 28px;
            }

            .password-icon {
                width: 72px;
                height: 72px;
                font-size: 30px;
            }
        }

        /* ========================================
           MOBILE
        ======================================== */
        @media (max-width: 576px) {

            .auth-overlay {
                padding: 14px;
                align-items: center;
            }

            .auth-card {
                border-radius: 18px;
            }

            .auth-body {
                padding: 22px 18px;
            }

            .password-icon {
                width: 62px;
                height: 62px;
                font-size: 26px;
            }

            .title-auth {
                font-size: 22px;
            }

            .desc-auth {
                font-size: 13px;
            }

            .custom-input {
                height: 46px;
                font-size: 13px;
            }

            .custom-btn {
                height: 44px;
                font-size: 13px;
            }

            .form-label {
                font-size: 13px;
            }
        }

        /* ========================================
           EXTRA SMALL DEVICE
        ======================================== */
        @media (max-width: 380px) {

            .auth-body {
                padding: 18px 14px;
            }

            .title-auth {
                font-size: 20px;
            }

            .desc-auth {
                font-size: 12px;
            }

            .custom-input {
                height: 44px;
                padding: 0 14px;
            }

            .custom-btn {
                height: 42px;
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
                        Forgot Password
                    </h3>

                    <p class="desc-auth mb-0">
                        Masukkan Nama Anda .
                        Kami akan Chek Sebelum Anda Di Izinkan Merubah Password Anda.
                    </p>

                </div>

                <!-- FORM -->
                <!-- FORM -->
                <form id="formForgotPassword">

                    @csrf

                    <!-- NAMA LENGKAP -->
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Nama Lengkap
                        </label>

                        <input type="text" id="name" class="form-control custom-input"
                            placeholder="Masukkan nama lengkap Anda">

                        <div class="small-text mt-2">
                            Contoh: John Doe
                        </div>

                    </div>

                    <!-- CAPTCHA PENJUMLAHAN -->
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Verifikasi Penjumlahan
                        </label>

                        <div class="d-flex align-items-center gap-2">

                            <div id="math-question" class="form-control custom-input d-flex align-items-center fw-bold"
                                style="
                    background:#f8fafc;
                    user-select:none;
                    pointer-events:none;
                ">
                            </div>

                            <button type="button" onclick="generateMath()" class="btn btn-light border"
                                style="
                    height:50px;
                    border-radius:14px;
                    min-width:50px;
                ">
                                🔄
                            </button>

                        </div>

                        <input type="number" id="answer" onkeyup="checkAnswer()"
                            class="form-control custom-input mt-3" placeholder="Masukkan hasil penjumlahan">

                        <div class="small-text mt-2">
                            Jawab hasil penjumlahan di atas
                        </div>

                    </div>

                    <!-- BUTTON SUBMIT -->
                    <button type="button" id="btnVerify" onclick="verify()" class="btn btn-success custom-btn w-100"
                        disabled>

                        Verifikasi & Lanjut

                    </button>

                    <!-- BUTTON BACK -->
                    <a href="{{ route('showlogin_management') }}"
                        class="btn btn-light border custom-btn w-100 mt-2 d-flex align-items-center justify-content-center">

                        Kembali ke Login

                    </a>

                </form>

            </div>

        </div>

    </div>

    <script>
        let a, b, c;

        function randomNumber() {

            return Math.floor(Math.random() * 900) + 1;
        }

        function shuffle(arr) {

            return arr.sort(() => Math.random() - 0.5);
        }

        /**
         * GENERATE SOAL PENJUMLAHAN
         */
        function generateMath() {

            a = randomNumber();
            b = randomNumber();
            c = randomNumber();

            document.getElementById("math-question").innerText =
                shuffle([a, b, c]).join(" + ");

            document.getElementById("answer").value = "";

            document.getElementById("btnVerify").disabled = true;
        }

        /**
         * ENABLE BUTTON
         */
        function checkAnswer() {

            const answer =
                document.getElementById("answer").value.trim();

            document.getElementById("btnVerify").disabled =
                answer === "";
        }

        /**
         * VERIFY
         */
        async function verify() {

            const name =
                document.getElementById("name").value.trim();

            const answer =
                document.getElementById("answer").value.trim();

            if (!name) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Nama lengkap wajib diisi'
                });

                return;
            }

            if (!answer) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Jawaban penjumlahan wajib diisi'
                });

                return;
            }

            /**
             * LOADING
             */
            const btn =
                document.getElementById("btnVerify");

            btn.disabled = true;

            btn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            Memverifikasi...
        `;

            try {

                /**
                 * FETCH API
                 */
                const res = await fetch(
                    "{{ route('password.check') }}", {
                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },

                        body: JSON.stringify({

                            /**
                             * DATA USER
                             */
                            name: name,

                            /**
                             * DATA CAPTCHA
                             */
                            number_1: a,
                            number_2: b,
                            number_3: c,

                            /**
                             * JAWABAN USER
                             */
                            answer: answer
                        })
                    }
                );

                const data = await res.json();

                if (!res.ok || !data.status) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message ||
                            'Data tidak ditemukan'
                    });

                    generateMath();

                    return;
                }

                /**
                 * SUCCESS
                 */
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message ||
                        'Verifikasi berhasil',
                    timer: 1800,
                    showConfirmButton: false
                });

                setTimeout(() => {

                    window.location.href =
                        data.redirect;

                }, 1800);

            } catch (err) {

                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Tidak dapat menghubungi server'
                });

            } finally {

                btn.disabled = false;

                btn.innerHTML =
                    'Verifikasi & Lanjut';
            }
        }

        /**
         * AUTO GENERATE
         */
        generateMath();
    </script>

</body>

</html>
