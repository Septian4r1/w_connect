<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Check Data | W_connect</title>

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

        .swal2-container {
            z-index: 999999999 !important;
        }

        @media(max-width:576px) {

            .auth-body {
                padding: 22px 18px;
            }

            .password-icon {

                width: 62px;
                height: 62px;
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
                        Verifikasi Data
                    </h3>

                    <p class="desc-auth mb-0">
                        Masukkan tanggal lahir dan NIK KTP Anda
                        untuk melanjutkan reset password.
                    </p>

                </div>

                <!-- FORM -->
                <form id="formCheckData">

                    @csrf


                    <!-- TANGGAL LAHIR -->
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Tanggal Lahir
                        </label>

                        <input type="date" id="birth_date" class="form-control custom-input">

                        <div class="small-text mt-2">
                            Contoh: 2000-12-31
                        </div>

                    </div>

                    <!-- NIK -->
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            NIK KTP
                        </label>

                        <input type="number" id="nik" class="form-control custom-input"
                            placeholder="Masukkan NIK KTP">

                        <div class="small-text mt-2">
                            Contoh: 320XXXXXXXXXXXXX
                        </div>

                    </div>

                    <!-- CAPTCHA -->
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

                    </div>

                    <!-- BUTTON -->
                    <button type="button" id="btnVerify" onclick="verify()" class="btn btn-success custom-btn w-100"
                        disabled>

                        Verifikasi Data

                    </button>

                </form>

            </div>

        </div>

    </div>
    <script>
        /**
         * =========================================================
         * GLOBAL STATE
         * =========================================================
         */
        let number1 = 0;
        let number2 = 0;
        let number3 = 0;

        let verifiedUserId = null;
        let verifiedEmail = null;

        /**
         * =========================================================
         * RANDOM NUMBER GENERATOR
         * =========================================================
         */
        function randomNumber() {
            return Math.floor(Math.random() * 999) + 1;
        }

        /**
         * =========================================================
         * SHUFFLE ARRAY
         * =========================================================
         */
        function shuffle(array) {
            return array.sort(() => Math.random() - 0.5);
        }

        /**
         * =========================================================
         * GENERATE CAPTCHA
         * =========================================================
         */
        function generateMath() {

            number1 = randomNumber();
            number2 = randomNumber();
            number3 = randomNumber();

            const question = shuffle([
                number1,
                number2,
                number3
            ]).join(' + ');

            document.getElementById('math-question').innerText = question;
            document.getElementById('answer').value = '';
            document.getElementById('btnVerify').disabled = true;
        }

        /**
         * =========================================================
         * CHECK INPUT CAPTCHA
         * =========================================================
         */
        function checkAnswer() {

            const answer = document.getElementById('answer').value.trim();

            document.getElementById('btnVerify').disabled = answer === '';
        }

        /**
         * =========================================================
         * SWEETALERT HELPER
         * =========================================================
         */
        function showAlert(icon, title, text = '') {
            Swal.fire({
                icon,
                title,
                text
            });
        }

        /**
         * =========================================================
         * SHOW OTP MODAL (SWEETALERT CUSTOM UI)
         * =========================================================
         */
        function showOtpModal() {

            Swal.fire({

                width: 420,
                padding: '1.5rem',
                allowOutsideClick: false,
                showConfirmButton: false,

                html: `
            <div class="text-center">

                <div style="font-size:40px">📩</div>

                <h4 class="fw-bold mt-2">Verifikasi OTP</h4>

                <p class="text-muted small">
                    OTP dikirim ke:
                </p>

                <div class="fw-bold mb-3" style="word-break:break-word;">
                    ${verifiedEmail}
                </div>

                <div class="d-flex justify-content-center gap-2 mb-3">

                    ${[...Array(6)].map(() => `
                            <input type="text"
                                class="otp-input"
                                maxlength="1"
                                inputmode="numeric"
                                style="
                                    width:45px;
                                    height:55px;
                                    text-align:center;
                                    font-size:20px;
                                    border-radius:10px;
                                    border:1px solid #ddd;
                                ">
                        `).join('')}

                </div>

                <button id="btnOtpVerify"
                    class="btn btn-success w-100">

                    Verifikasi OTP

                </button>

            </div>
        `,

                didOpen: () => {

                    const inputs = document.querySelectorAll('.otp-input');

                    /**
                     * AUTO NEXT INPUT
                     */
                    inputs.forEach((input, i) => {

                        input.addEventListener('input', () => {
                            input.value = input.value.replace(/\D/g, '');

                            if (input.value && inputs[i + 1]) {
                                inputs[i + 1].focus();
                            }
                        });

                        /**
                         * BACKSPACE NAVIGATION
                         */
                        input.addEventListener('keydown', (e) => {
                            if (e.key === 'Backspace' && !input.value && inputs[i - 1]) {
                                inputs[i - 1].focus();
                            }
                        });
                    });

                    inputs[0].focus();

                    /**
                     * VERIFY OTP BUTTON
                     */
                    document.getElementById('btnOtpVerify')
                        .addEventListener('click', verifyOtp);
                }

            });
        }

        /**
         * =========================================================
         * VERIFY DATA (STEP 1)
         * =========================================================
         */
        async function verify() {

            const birthDate = document.getElementById('birth_date').value;
            const nik = document.getElementById('nik').value.trim();
            const answer = document.getElementById('answer').value.trim();

            if (!birthDate) return showAlert('warning', 'Tanggal lahir wajib diisi');
            if (!nik) return showAlert('warning', 'NIK wajib diisi');
            if (nik.length < 16) return showAlert('warning', 'NIK minimal 16 digit');
            if (!answer) return showAlert('warning', 'Captcha wajib diisi');

            const totalCaptcha = number1 + number2 + number3;

            if (parseInt(answer) !== totalCaptcha) {
                showAlert('error', 'Captcha salah');
                generateMath();
                return;
            }

            const btn = document.getElementById('btnVerify');

            btn.disabled = true;
            btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Memverifikasi...
    `;

            try {

                const response = await fetch(
                    "{{ route('password.verifyCheckData', ['id' => request()->route('id')]) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            birth_date: birthDate,
                            nik: nik,
                            number_1: number1,
                            number_2: number2,
                            number_3: number3,
                            answer: answer
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.status) {
                    showAlert('error', 'Verifikasi gagal', data.message);
                    generateMath();
                    return;
                }

                /**
                 * =================================================
                 * SUCCESS → OPEN OTP MODAL
                 * =================================================
                 */
                verifiedUserId = data.user_id;
                verifiedEmail = data.email;

                Swal.fire({
                    icon: 'success',
                    title: 'OTP dikirim',
                    text: data.message,
                    timer: 1200,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    showOtpModal();
                }, 1200);

            } catch (err) {
                console.error(err);
                showAlert('error', 'Server Error', 'Tidak dapat menghubungi server');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Verifikasi Data';
            }
        }

        /**
         * =========================================================
         * VERIFY OTP (STEP 2)
         * =========================================================
         */
        async function verifyOtp() {

            const inputs = document.querySelectorAll('.otp-input');

            let otp = '';
            inputs.forEach(i => otp += i.value);

            if (otp.length !== 6) {
                Swal.fire('Error', 'OTP harus 6 digit', 'error');
                return;
            }

            const btn = document.getElementById('btnOtpVerify');

            btn.disabled = true;
            btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Memverifikasi...
    `;

            try {

                const res = await fetch(
                    "{{ route('password.verify.otp', ['id' => request()->route('id')]) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            user_id: verifiedUserId,
                            otp: otp,
                            type: 'reset_forgot_password'
                        })
                    }
                );

                const data = await res.json();

                if (!res.ok || !data.status) {
                    Swal.fire('Error', data.message, 'error');
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);

            } catch (err) {
                Swal.fire('Error', 'Server Error', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Verifikasi OTP';
            }
        }

        /**
         * =========================================================
         * INPUT NIK LIMIT
         * =========================================================
         */
        document.getElementById('nik').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });

        /**
         * =========================================================
         * ENTER SUBMIT
         * =========================================================
         */
        document.getElementById('formCheckData').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                verify();
            }
        });

        /**
         * =========================================================
         * INIT CAPTCHA
         * =========================================================
         */
        generateMath();
    </script>

</body>

</html>
