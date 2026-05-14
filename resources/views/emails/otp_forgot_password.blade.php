<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kode OTP Verifikasi Password</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            width: 100%;
            padding: 30px 0;
        }

        .container {
            width: 100%;
            max-width: 520px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* HEADER */
        .header {
            background: linear-gradient(90deg, #198754, #22c55e);
            padding: 25px 20px;
            text-align: center;
            color: #fff;
        }

        .header img {
            width: 70px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        /* BODY */
        .body {
            padding: 25px 22px;
            color: #111827;
        }

        .body p {
            font-size: 14px;
            line-height: 1.7;
            text-align: center;
            margin: 10px 0;
        }

        .otp-box {
            margin: 25px auto;
            text-align: center;
        }

        .otp {
            display: inline-block;
            font-size: 34px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #198754;
            background: #ecfdf5;
            padding: 12px 25px;
            border-radius: 10px;
            border: 1px dashed #22c55e;
        }

        .info-box {
            margin-top: 20px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }

        .warning {
            color: #ef4444;
            font-weight: 600;
        }

        /* FOOTER */
        .footer {
            background: #111827;
            color: #9ca3af;
            text-align: center;
            padding: 15px;
            font-size: 11px;
            line-height: 1.6;
        }

        /* MOBILE */
        @media screen and (max-width: 600px) {

            .container {
                width: 90% !important;
            }

            .otp {
                font-size: 28px;
                letter-spacing: 4px;
                padding: 10px 18px;
            }

            .body {
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="container">

            <!-- HEADER -->
            <div class="header">
                <img src="{{ asset('images/logo_w_connect_web.gif') }}" alt="Logo">
                <h2>Verifikasi Reset Password</h2>
            </div>

            <!-- BODY -->
            <div class="body">

                <p>
                    Halo <strong>{{ $user->name ?? 'User' }}</strong>,
                </p>

                <p>
                    Kami menerima permintaan untuk melakukan
                    <strong>reset password akun Anda</strong>.
                </p>

                <p>
                    Gunakan kode OTP berikut untuk melanjutkan proses verifikasi:
                </p>

                <!-- OTP -->
                <div class="otp-box">
                    <div class="otp">
                        {{ $otp }}
                    </div>
                </div>

                <p>
                    Kode OTP ini hanya berlaku selama
                    <strong>5 menit</strong>.
                </p>

                <p class="warning">
                    Jangan pernah membagikan kode ini kepada siapa pun.
                </p>

                <div class="info-box">
                    Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini atau segera amankan akun Anda.
                </div>

            </div>

            <!-- FOOTER -->
            <div class="footer">

                Email ini dikirim otomatis oleh sistem keamanan W_Connect.<br>

                © {{ date('Y') }} W_Connect | AsthA Production

            </div>

        </div>

    </div>

</body>

</html>
