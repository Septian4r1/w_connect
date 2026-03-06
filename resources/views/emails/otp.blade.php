<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kode OTP Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        /* Card Styles */
        .email-card {
            background-color: #000;
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(90deg, #0072c6, #00a2ff);
            padding: 20px;
            text-align: center;
            color: #fff;
        }

        .card-header img {
            width: 80px;
            margin-bottom: 10px;
        }

        .card-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .card-body {
            background-color: #fff;
            padding: 25px 20px;
            color: #333;
        }

        .card-body h1.otp {
            font-size: 36px;
            color: #0072c6;
            text-align: center;
            margin: 20px 0;
            letter-spacing: 4px;
        }

        .card-body p {
            font-size: 15px;
            margin: 10px 0;
            text-align: center;
        }

        .card-footer {
            background-color: #1a1a1a;
            color: #ccc;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }

        @media screen and (max-width: 600px) {
            .email-card {
                width: 90% !important;
            }

            .card-body h1.otp {
                font-size: 28px !important;
            }
        }
    </style>
</head>

<body>
    <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f5f5f5">
        <tr>
            <td align="center" valign="middle">
                <table class="email-card" cellpadding="0" cellspacing="0" border="0">
                    <!-- Header -->
                    <tr>
                        <td class="card-header">
                            <img src="{{ asset('images/logo_w_connect_web.gif') }}" alt="wConnect Logo">
                            <h2>Kode OTP Login</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="card-body">
                            <p>Halo {{ $user->name ?? 'User' }},</p>
                            <p>Berikut adalah <strong>kode OTP</strong> untuk login Anda:</p>
                            <h1 class="otp">{{ $otp }}</h1>
                            <p>Kode ini berlaku selama <strong>5 menit</strong>. Jangan bagikan kode ini ke siapapun.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="card-footer">
                            Jika Anda tidak melakukan permintaan login, silakan abaikan email ini.<br>
                            &copy; {{ date('Y') }} wConnect | By : AsthA Production
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
