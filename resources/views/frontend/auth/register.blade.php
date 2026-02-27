<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Warga Baru</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f6fa;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            padding: 40px 15px;
        }

        .phone-frame {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 30px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            padding: 30px 25px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h2 {
            font-weight: 600;
            font-size: 20px;
            color: #222;
        }

        .header p {
            font-size: 13px;
            color: #777;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            font-size: 13px;
            font-weight: 500;
            color: #333;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px;
            margin-top: 6px;
            border-radius: 12px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: 0.3s ease;
            background: #fafafa;
        }

        textarea {
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #d4af37;
            background: #fff;
            outline: none;
            box-shadow: 0 0 6px rgba(212, 175, 55, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            background: linear-gradient(45deg, #c9a227, #ffd700);
            color: #111;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(255, 215, 0, 0.35);
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
        }

        .footer a {
            color: #c9a227;
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .wrapper {
                padding: 0;
            }

            .phone-frame {
                border-radius: 0;
                box-shadow: none;
                min-height: 100vh;
                padding: 30px 20px;
            }
        }

        /* Spinner loader */
        .spinner {
            border: 3px solid rgba(243, 243, 243, 0.3);
            border-top: 3px solid #c9a227;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            margin: 0 auto;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="phone-frame">
            <div class="header">
                <h2>Daftar Warga Baru</h2>
                <p>Citra Swarna Riverside</p>
            </div>

            <form id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label>Blok</label>
                    <select name="block_id" required>
                        <option value="">-- Pilih Blok --</option>
                        @foreach ($blocks as $block)
                            <option value="{{ $block->id }}">{{ $block->nama_blok }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Nomor Rumah</label>
                    <input type="text" name="nomor_rumah" placeholder="A1/1" required>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" rows="3" placeholder="Opsional"></textarea>
                </div>

                <div class="form-group">
                    <label>Desa</label>
                    <input type="text" name="desa" value="Bojong" readonly>
                </div>

                <div class="form-group">
                    <label>Kelurahan</label>
                    <input type="text" name="kelurahan" value="Klapanunggal" readonly>
                </div>

                <div class="form-group">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" value="16710" readonly autocomplete="postal-code">
                </div>

                <div class="form-group">
                    <label>Status Hunian</label>
                    <select name="status_hunian" required>
                        <option value="">-- Pilih Status Hunian --</option>
                        @foreach ($statusHunianOptions as $value => $label)
                            <option value="{{ $value }}" {{ old('status_hunian') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" placeholder="***********" required
                            autocomplete="new-password" id="password">
                        <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password_confirmation" placeholder="***********" required
                            autocomplete="new-password" id="password_confirmation">
                        <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Daftar Sekarang</button>
            </form>

            <div class="footer">
                Sudah punya akun?
                <a href="{{ route('showlogin') }}">Masuk</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }




        const form = document.getElementById('registerForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const result = await Swal.fire({
                title: 'Konfirmasi Data',
                text: "Apakah data yang Anda masukkan sudah benar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, sudah benar',
                cancelButtonText: 'Periksa lagi',
                width: 300, // lebih kecil dari default
                padding: '1rem',
                allowOutsideClick: false,
                customClass: {
                    title: 'swal-title-small',
                    content: 'swal-text-small',
                    confirmButton: 'swal-button-small',
                    cancelButton: 'swal-button-small'
                }
            });

            if (!result.isConfirmed) return;

            // Loader spinner bulat, backdrop gelap
            Swal.fire({
                html: '<div class="spinner"></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                background: 'transparent',
                backdrop: 'rgba(0,0,0,0.5)',
                didOpen: () => Swal.showLoading()
            });

            try {
                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                let data = {};
                try {
                    data = await response.json();
                } catch {
                    data = {};
                }

                Swal.close();

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Sukses',
                        text: data.message || 'Registrasi berhasil!',
                        confirmButtonColor: '#c9a227',
                        width: 280,
                        padding: '1rem',
                        customClass: {
                            title: 'swal-title-small',
                            content: 'swal-text-small',
                            confirmButton: 'swal-button-small'
                        }
                    });

                    window.location.href = data.redirect || '{{ route('showlogin') }}';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan!',
                        confirmButtonColor: '#c9a227',
                        width: 280,
                        padding: '1rem',
                        customClass: {
                            title: 'swal-title-small',
                            content: 'swal-text-small',
                            confirmButton: 'swal-button-small'
                        }
                    });
                }
            } catch (err) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan server. Silakan coba lagi.',
                    confirmButtonColor: '#c9a227',
                    width: 280,
                    padding: '1rem',
                    customClass: {
                        title: 'swal-title-small',
                        content: 'swal-text-small',
                        confirmButton: 'swal-button-small'
                    }
                });
                console.error(err);
            }

            return false;
        });

        // Optional: Session flash messages dari Laravel
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: @json(session('success')),
                confirmButtonColor: '#c9a227',
                width: 280,
                padding: '1rem',
                customClass: {
                    title: 'swal-title-small',
                    content: 'swal-text-small',
                    confirmButton: 'swal-button-small'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonColor: '#c9a227',
                width: 280,
                padding: '1rem',
                customClass: {
                    title: 'swal-title-small',
                    content: 'swal-text-small',
                    confirmButton: 'swal-button-small'
                }
            });
        @endif
    </script>

    <style>
        /* Custom class untuk SweetAlert kecil */
        .swal-title-small {
            font-size: 1rem !important;
        }

        .swal-text-small {
            font-size: 0.8rem !important;
        }

        .swal-button-small {
            font-size: 0.75rem !important;
            padding: 5px 12px !important;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 30px;
            /* beri ruang untuk icon mata */
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            font-size: 16px;
        }
    </style>


</body>

</html>
