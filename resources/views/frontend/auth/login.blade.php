<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warga</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
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
            <h2>Warga Login</h2>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label>No Rumah</label>
                    <input type="text" name="nomor_rumah" placeholder="Contoh: A12" required>
                </div>

                <div class="form-group">
                    <label>Password</label>

                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="********" required>
                        <span class="toggle-password" onclick="togglePassword()">👁</span>
                    </div>

                    <!-- REMEMBER & FORGOT -->
                    <div class="remember-row">
                        <label>
                            <input type="checkbox" name="remember">
                            Ingat saya
                        </label>
                        <a href="#">Lupa password?</a>
                    </div>
                </div>

                <button class="btn-login" type="submit">Masuk</button>
            </form>

            <div class="footer">
                Belum punya akun? <a href="{{ route('showregister') }}">Daftar</a>
            </div>

        </div>

    </div>
    <div class="version-block">
        by : AsthA production &nbsp;|&nbsp; versi 0.0.1
    </div>

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            pass.type = pass.type === "password" ? "text" : "password";
        }

        const form = document.getElementById('loginForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const result = await Swal.fire({
                title: 'Login?',
                text: 'Pastikan data sudah benar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1abc9c',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            Swal.fire({
                html: '<div class="spinner"></div>',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json().catch(() => ({}));
                Swal.close();

                // =========================
                // CEK STATUS RESPONSE
                // =========================
                if (data.status === 'success' && data.redirect) {
                    // LOGIN BERHASIL
                    Swal.fire('Sukses', data.message || 'Login berhasil', 'success')
                        .then(() => window.location.href = data.redirect);
                } else if (data.status === 'warning' && data.redirect) {
                    // DOUBLE LOGIN
                    Swal.fire({
                        title: 'Akun Sedang Login',
                        icon: 'warning',
                        html: `${data.message.replace(/\n/g, '<br>')}`,
                        showCancelButton: true,
                        confirmButtonText: 'Logout Semua Perangkat',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#e74c3c'
                    }).then(result => {
                        if (result.isConfirmed) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    // LOGIN GAGAL / PASSWORD SALAH / RUMAH TIDAK DITEMUKAN
                    Swal.fire('Gagal', data.message || 'Login gagal', 'error');
                }
            } catch (error) {
                Swal.close();
                Swal.fire('Error', 'Terjadi kesalahan, silakan coba lagi.', 'error');
            }
        });
    </script>

</body>

</html>
