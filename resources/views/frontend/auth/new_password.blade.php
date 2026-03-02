<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Password</title>
    <link rel="icon" type="image/gif" href="{{ asset('images/logo_w_connect_web.gif') }}">


    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* =============================
   SWEETALERT PREMIUM STYLE
============================= */

        .swal2-popup {
            width: 300px !important;
            border-radius: 20px !important;
            padding: 20px 16px !important;
            font-size: 14px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .swal2-title {
            font-size: 16px !important;
            font-weight: 600 !important;
        }

        .swal2-html-container {
            font-size: 13px !important;
        }

        .swal2-icon {
            transform: scale(0.8);
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }

        .swal2-confirm {
            background-color: #1abc9c !important;
            border-radius: 25px !important;
            padding: 8px 22px !important;
            font-size: 13px !important;
            box-shadow: none !important;
        }

        .swal2-timer-progress-bar {
            background: #1abc9c !important;
        }



        /* =============================
   LOADING OVERLAY
============================= */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            /* background redup */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: opacity .3s ease;
        }

        .loading-overlay.active {
            visibility: visible;
            opacity: 1;
        }

        /* ================================
        VERSION / FOOTER BLOCK - MINI
        =============================== */
        .version-block {
            position: fixed;
            bottom: 6px;
            /* sedikit naik dari bawah */
            left: 50%;
            transform: translateX(-50%);
            background: #d1f2eb;
            /* warna soft teal/abu muda */
            color: #1abc9c;
            /* teks hijau sesuai header/button */
            padding: 3px 8px;
            /* lebih kecil */
            border-radius: 8px;
            /* lebih ramping */
            font-size: 10px;
            /* lebih kecil */
            font-weight: 600;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            /* lebih subtle */
            z-index: 1000;
        }

        /* =============================
           GLOBAL
        ============================= */
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
            /* MATIKAN SCROLL */
        }

        body {
            margin: 0;
            background: linear-gradient(180deg, #e9f7f1, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* =============================
           APP FRAME (MOBILE STYLE)
        ============================= */
        .app {
            width: 100%;
            max-width: 400px;
            background: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* =============================
           HEADER
        ============================= */
        .header {
            text-align: center;
            padding: 40px 20px 20px;
        }

        .header img {
            width: 80px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #1abc9c;
            font-weight: 600;
        }

        /* =============================
           CONTENT
        ============================= */
        .content {
            flex: 1;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        /* =============================
           INPUT (Kecuali checkbox)
        ============================= */
        input:not([type="checkbox"]) {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #ddd;
            margin-top: 5px;
            font-size: 14px;
        }

        input:not([type="checkbox"]):focus {
            outline: none;
            border-color: #1abc9c;
            box-shadow: 0 0 0 2px rgba(26, 188, 156, .15);
        }

        /* =============================
           CHECKBOX STYLE
        ============================= */
        input[type="checkbox"] {
            width: auto;
            margin-right: 6px;
            accent-color: #1abc9c;
            /* warna centang */
            transform: scale(1.1);
        }

        /* =============================
           PASSWORD
        ============================= */
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        /* =============================
           REMEMBER & FORGOT
        ============================= */
        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-top: 6px;
        }

        .remember-row label {
            font-weight: 400;
            display: flex;
            align-items: center;
        }

        .remember-row a {
            color: #1abc9c;
            text-decoration: none;
            font-weight: 600;
        }

        /* =============================
           BUTTON
        ============================= */
        .btn-login {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: #1abc9c;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #17a589;
        }

        /* =============================
           FOOTER
        ============================= */
        .footer {
            text-align: center;
            font-size: 13px;
            margin-top: 15px;
        }

        .footer a {
            color: #1abc9c;
            font-weight: 600;
            text-decoration: none;
        }

        /* =============================
           SPINNER
        ============================= */
        .spinner {
            border: 3px solid #eee;
            border-top: 3px solid #1abc9c;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <div class="app">

        <!-- HEADER -->
        <div class="header">
            <img src="{{ asset('images/logo_w_connect_web.gif') }}">
            <h2>Buat Password Baru</h2>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <form id="newPasswordForm" method="POST" action="{{ route('forgotPassword.savePassword') }}">
                @csrf
                <div class="form-group">
                    <label>Password Baru</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" data-target="password">👁</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <span class="toggle-password" data-target="password_confirmation">👁</span>
                    </div>
                </div>

                <button class="btn-login" type="submit">Simpan Password</button>
            </form>
        </div>
    </div>

    <!-- VERSION -->
    <div class="version-block">
        by : AsthA production | versi 0.0.2
    </div>

    <!-- LOADER OVERLAY -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <!-- ALERT SESSION HANDLER -->
    @if (session('error') || session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) overlay.classList.remove('active');

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: "{{ session('error') }}",
                        timer: 2500,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                @endif

                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "{{ session('success') }}",
                        timer: 2000,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        // window.location.href = "{{ route('showlogin') }}";
                    });
                @endif
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('newPasswordForm');
            const overlay = document.getElementById('loadingOverlay');

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // stop submit dulu

                    Swal.fire({
                        icon: 'question',
                        title: 'Konfirmasi',
                        text: 'Apakah anda yakin ingin merubah password?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Ubah',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#1abc9c',
                        cancelButtonColor: '#aaa'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (overlay) overlay.classList.add('active');
                            form.submit(); // submit ke controller
                        }
                    });
                });
            }

        });
    </script>
</body>

</html>
