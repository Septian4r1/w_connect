<!doctype html>
<html lang="en" class="light-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Bootstrap & Template -->
    <link rel="stylesheet" href="{{ asset('tamplate_management/assets/css/pace.min.css') }}">
    <script src="{{ asset('tamplate_management/assets/js/pace.min.js') }}"></script>
    <link href="{{ asset('tamplate_management/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('tamplate_management/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tamplate_management/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('tamplate_management/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('tamplate_management/assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <title>W_Connect Management</title>
    <link rel="icon" type="image/gif" href="{{ asset('images/logo_w_connect_web.gif') }}">

    <style>
        /* ===============================
        LOGIN PAGE STYLE
        =============================== */

        /* ===============================
   Input OTP kecil di SweetAlert
=============================== */
        .swal2-popup .otp-small {
            width: 120px;
            /* Lebar kecil */
            margin: 0 auto;
            /* Center */
            font-size: 14px;
            /* Font lebih kecil */
            text-align: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header img {
            width: 120px;
            height: auto;
            margin-bottom: 10px;
        }

        .login-header h2 {
            font-size: 20px;
            font-weight: 600;
        }

        /* Spinner di tombol agar tidak merusak ukuran tombol */
        .btn-login .spinner-border {
            width: 1rem;
            height: 1rem;
        }
    </style>
</head>

<body>
    <div class="login-bg-overlay au-sign-in-basic"></div>
    <div class="wrapper">
        <div class="container">
            <div class="row mt-5">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto mt-5">
                    <div class="card radius-10 mt-5">

                        <!-- CARD BODY -->
                        <div class="card-body p-4 ">
                            <div class="login-header">
                                <img src="{{ asset('images/logo_w_connect_web.gif') }}" alt="Logo">
                                <h2>Management Login</h2>
                            </div>

                            <!-- FORM LOGIN -->
                            <form class="form-body row g-3" id="loginForm" method="POST"
                                action="{{ route('management.login.process') }}">
                                @csrf

                                <div class="col-12">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="inputEmail"
                                            placeholder="abc@example.com" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="inputPassword" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="inputPassword"
                                            placeholder="Your password" required>
                                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckRemember">
                                        <label class="form-check-label">Remember Me</label>
                                    </div>
                                    <a href="#" class="small">Forgot Password?</a>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <!-- TOMBOL LOGIN DENGAN SPINNER -->
                                        <button type="submit"
                                            class="btn btn-dark btn-login d-flex align-items-center justify-content-center">
                                            <span class="btn-text">Sign In</span>
                                            <div class="spinner-border spinner-border-sm text-light ms-2 d-none"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- CARD FOOTER -->
                        <div class="card-footer text-center small text-muted">
                            by : AsthA production &nbsp;|&nbsp; Versi {{ config('app.version') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ===============================
            // 👁️ TOGGLE PASSWORD
            // ===============================
            const passwordInput = document.getElementById('inputPassword');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            toggleIcon.addEventListener('click', function() {
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });

            // ===============================
            // 🔐 LOGIN AJAX + SWEETALERT + SPINNER + OTP INPUT KECIL
            // ===============================
            const loginForm = document.getElementById('loginForm');

            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const email = document.getElementById('inputEmail').value;
                const password = document.getElementById('inputPassword').value;
                const remember = document.getElementById('flexSwitchCheckRemember').checked;
                const csrf = document.querySelector('input[name="_token"]').value;

                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const btnText = submitBtn.querySelector('.btn-text'); // Teks tombol
                const spinner = submitBtn.querySelector('.spinner-border'); // Spinner

                // TOMBOL DISABLED DAN TAMPILKAN SPINNER
                submitBtn.disabled = true;
                btnText.textContent = 'Signing in...';
                spinner.classList.remove('d-none');

                try {
                    const response = await fetch('/management/login', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email,
                            password,
                            remember
                        })
                    });

                    const result = await response.json();

                    // ❌ GAGAL LOGIN
                    if (!response.ok || result.status === false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Login Gagal',
                            text: result.message ?? 'Email atau password salah',
                            width: 300,
                            padding: '1rem',
                            confirmButtonColor: '#212529',
                            customClass: {
                                title: 'swal2-title-mini',
                                content: 'swal2-content-mini'
                            }
                        });
                        submitBtn.disabled = false;
                        btnText.textContent = 'Sign In';
                        spinner.classList.add('d-none');
                        return;
                    }

                    // ✅ LOGIN BERHASIL DENGAN OTP
                    if (result.otpSent) {
                        submitBtn.disabled = false;
                        btnText.textContent = 'Sign In';
                        spinner.classList.add('d-none');

                        Swal.fire({
                            title: 'Masukkan OTP',
                            html: `
                        <p>Kode OTP sudah dikirim ke email:<br><strong>${result.email}</strong></p>
                        <input type="text" id="otpInput" class="swal2-input otp-small" placeholder="OTP">
                    `,
                            showCancelButton: true,
                            confirmButtonText: 'Verifikasi',
                            cancelButtonText: 'Batal',
                            width: 300,
                            padding: '1rem',
                            confirmButtonColor: '#212529',
                            preConfirm: () => {
                                const otp = Swal.getPopup().querySelector('#otpInput')
                                    .value;
                                if (!otp) Swal.showValidationMessage(
                                    'Mohon masukkan kode OTP');
                                return otp;
                            }
                        }).then(async (otpResult) => {
                            if (otpResult.isConfirmed) {
                                const otpCode = otpResult.value;
                                try {
                                    const verifyResp = await fetch(
                                        '/management/verify-otp', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': csrf,
                                                'Accept': 'application/json',
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                email,
                                                otp: otpCode
                                            })
                                        });
                                    const verifyResult = await verifyResp.json();

                                    if (verifyResp.ok && verifyResult.status === true) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'OTP Valid',
                                            text: 'Login berhasil!',
                                            timer: 1500,
                                            showConfirmButton: false,
                                            timerProgressBar: true
                                        }).then(() => {
                                            if (verifyResult.redirect) window
                                                .location.href = verifyResult
                                                .redirect;
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'OTP Salah',
                                            text: verifyResult.message ??
                                                'Kode OTP tidak valid'
                                        });
                                    }
                                } catch (err) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Terjadi kesalahan saat verifikasi OTP'
                                    });
                                }
                            }
                        });

                        return;
                    }

                    // ✅ LOGIN FULL (tanpa OTP)
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: result.message ?? 'Login berhasil',
                        width: 300,
                        padding: '1rem',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        customClass: {
                            title: 'swal2-title-mini',
                            content: 'swal2-content-mini'
                        }
                    }).then(() => {
                        if (result.redirect) window.location.href = result.redirect;
                    });

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan server',
                        width: 300,
                        padding: '1rem',
                        confirmButtonColor: '#212529',
                        customClass: {
                            title: 'swal2-title-mini',
                            content: 'swal2-content-mini'
                        }
                    });
                    submitBtn.disabled = false;
                    btnText.textContent = 'Sign In';
                    spinner.classList.add('d-none');
                }
            });
        });
    </script>

    <style>

    </style>

</body>

</html>
