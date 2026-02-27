<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Perumahan</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            background: url("{{ asset('images/frontend/fotoprofile/foto utama.jpg') }}") no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Dark elegant overlay */
        .overlay {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        /* Glass luxury card */
        .box {
            text-align: center;
            padding: 60px 40px;
            width: 90%;
            max-width: 420px;
            border-radius: 20px;
            background: rgba(0, 0, 0, 0.4);

            /* Garis emas */
            border: 2px solid #ffd700;

            /* Glow emas halus */
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.3);

            animation: float 4s ease-in-out infinite;
        }

        h1 {
            color: #ffd700;
            font-size: 26px;
            margin-bottom: 35px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Buttons */
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border-radius: 50px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            letter-spacing: 1px;
        }

        /* Gold Button */
        /* Default button (clean elegant) */
        .btn-daftar,
        .btn-login {
            background: transparent;
            border: 2px solid #ffffff;
            color: #ffffff;
            transition: all 0.4s ease;
        }

        /* Hover jadi emas */
        .btn-daftar:hover,
        .btn-login:hover {
            background: linear-gradient(45deg, #c9a227, #ffd700);
            color: #111;
            border: 2px solid #ffd700;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.5);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .box {
                padding: 30px 20px;
            }

            h1 {
                font-size: 20px;
            }

            .btn {
                font-size: 14px;
                padding: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="overlay">
        <div class="box">
            <h1>Citra Swarna Riverside</h1>

            <a href="{{ route('showregister') }}" class="btn btn-daftar">
                Warga Baru
            </a>
            <a href="{{ route('showlogin') }}" class="btn btn-login">
                Masuk
            </a>

        </div>
    </div>

</body>

</html>
