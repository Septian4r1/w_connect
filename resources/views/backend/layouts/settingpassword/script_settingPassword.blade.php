@push('scripts')
    <script>
        let a, b, c;
        let verifiedUserId = null;
        let verifiedEmail = null;

        function randomNumber() {
            return Math.floor(Math.random() * 900) + 1;
        }

        function shuffle(arr) {
            return arr.sort(() => Math.random() - 0.5);
        }

        function generateMath() {

            a = randomNumber();
            b = randomNumber();
            c = randomNumber();

            document.getElementById("math-question").innerText =
                shuffle([a, b, c]).join(" + ");

            document.getElementById("answer").value = "";
            document.getElementById("btnVerify").disabled = true;
        }

        function checkAnswer() {

            const val = document.getElementById("answer").value;

            document.getElementById("btnVerify").disabled =
                val.trim() === "";
        }

        /**
         * 🔥 OTP MODAL STYLE
         */
        function showOtpModal() {

            Swal.fire({

                width: 420,
                padding: '1.5rem',
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-4'
                },

                html: `

            <div class="text-center">

                <div class="mb-3">

                    <div style="
                        width:72px;
                        height:72px;
                        margin:auto;
                        border-radius:50%;
                        background:#e8f5e9;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:32px;
                    ">
                        📩
                    </div>

                </div>

                <h4 class="fw-bold mb-2">
                    Verifikasi OTP
                </h4>

                <div class="text-muted small mb-4 px-2" style="line-height:1.6;">
                    OTP sudah dikirim ke email:
                    <br>

                    <div class="fw-semibold text-dark mt-2"
                        style="
                            word-break:break-word;
                            font-size:14px;
                        ">
                        ${verifiedEmail}
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 mb-4">

                    <input type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric">

                    <input type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric">

                    <input type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric">

                    <input type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric">

                    <input type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric">

                    <input type="text"
                        class="otp-input"
                        maxlength="1"
                        inputmode="numeric">

                </div>

                <button id="btnOtpVerify"
                    class="btn btn-success w-100 rounded-3 py-2 fw-semibold">

                    Verifikasi OTP

                </button>

            </div>

            <style>

                .otp-input{
                    width:48px;
                    height:56px;
                    border-radius:14px;
                    border:1px solid #dcdcdc;
                    text-align:center;
                    font-size:24px;
                    font-weight:700;
                    outline:none;
                    transition:.2s;
                }

                .otp-input:focus{
                    border-color:#198754;
                    box-shadow:0 0 0 4px rgba(25,135,84,.15);
                }

                @media(max-width:576px){

                    .otp-input{
                        width:42px;
                        height:52px;
                        font-size:22px;
                    }

                    .swal2-popup{
                        width:95% !important;
                        padding:1.2rem !important;
                        border-radius:20px !important;
                    }
                }

            </style>
        `,

                didOpen: () => {

                    const inputs = document.querySelectorAll('.otp-input');

                    /**
                     * 🔥 AUTO NEXT INPUT
                     */
                    inputs.forEach((input, index) => {

                        input.addEventListener('input', (e) => {

                            e.target.value =
                                e.target.value.replace(/[^0-9]/g, '');

                            if (e.target.value && index < inputs.length - 1) {
                                inputs[index + 1].focus();
                            }
                        });

                        /**
                         * 🔥 BACKSPACE
                         */
                        input.addEventListener('keydown', (e) => {

                            if (
                                e.key === 'Backspace' &&
                                !input.value &&
                                index > 0
                            ) {
                                inputs[index - 1].focus();
                            }
                        });

                        /**
                         * 🔥 PASTE OTP
                         */
                        input.addEventListener('paste', (e) => {

                            e.preventDefault();

                            const pasted =
                                (e.clipboardData || window.clipboardData)
                                .getData('text')
                                .replace(/\D/g, '')
                                .slice(0, 6);

                            pasted.split('').forEach((char, i) => {

                                if (inputs[i]) {
                                    inputs[i].value = char;
                                }
                            });

                            if (pasted.length === 6) {
                                inputs[5].focus();
                            }
                        });
                    });

                    inputs[0].focus();

                    /**
                     * 🔥 VERIFY OTP BUTTON
                     */
                    document
                        .getElementById('btnOtpVerify')
                        .addEventListener('click', verifyOtp);
                }
            });
        }

        /**
         * 🔥 VERIFY CAPTCHA
         */
        async function verify() {

            const name = document.getElementById("name").value.trim();
            const answer = document.getElementById("answer").value;

            if (!name) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Nama wajib diisi'
                });

                return;
            }

            if (!answer) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Jawaban wajib diisi'
                });

                return;
            }

            const btn = document.getElementById("btnVerify");

            btn.disabled = true;

            btn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            Memverifikasi...
        `;

            try {

                const res = await fetch(
                    "{{ route('management.verify.captcha') }}", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },

                        body: JSON.stringify({
                            name,
                            a,
                            b,
                            c,
                            answer
                        })
                    });

                const data = await res.json();

                /**
                 * ❌ GAGAL
                 */
                if (!res.ok || !data.status) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Verifikasi',
                        text: data.message || 'Terjadi kesalahan'
                    });

                    generateMath();

                    return;
                }

                /**
                 * ✅ SUCCESS
                 */
                verifiedUserId = data.user_id;
                verifiedEmail = data.email;

                showOtpModal();

            } catch (err) {

                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Tidak dapat menghubungi server'
                });

            } finally {

                btn.disabled = false;
                btn.innerHTML = 'Verifikasi & Lanjut';
            }
        }

        /**
         * 🔥 VERIFY OTP
         */
        async function verifyOtp() {

            const inputs =
                document.querySelectorAll('.otp-input');

            let otp = '';

            inputs.forEach(input => {
                otp += input.value;
            });

            if (otp.length < 6) {

                Swal.showValidationMessage(
                    'OTP harus 6 digit'
                );

                return;
            }

            const btn =
                document.getElementById('btnOtpVerify');

            btn.disabled = true;

            btn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            Memverifikasi...
        `;

            try {

                const res = await fetch(
                    "{{ route('management.verify.otp') }}", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },

                        body: JSON.stringify({
                            user_id: verifiedUserId,
                            otp: otp,
                            type: 'reset_password'
                        })
                    });

                const data = await res.json();

                /**
                 * ❌ OTP SALAH
                 */
                if (!res.ok || !data.status) {

                    Swal.showValidationMessage(
                        data.message || 'OTP tidak valid'
                    );

                    btn.disabled = false;
                    btn.innerHTML = 'Verifikasi OTP';

                    return;
                }

                /**
                 * ✅ SUCCESS
                 */
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'OTP berhasil diverifikasi',
                    timer: 1500,
                    showConfirmButton: false
                });
                setTimeout(() => {

                    window.location.href = data.redirect;

                }, 1500);

            } catch (err) {

                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Tidak dapat menghubungi server'
                });

            } finally {

                btn.disabled = false;
                btn.innerHTML = 'Verifikasi OTP';
            }
        }

        generateMath();
    </script>
@endpush
