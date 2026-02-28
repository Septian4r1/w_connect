<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W_Connect Splash</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #f0fff4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Animasi untuk TEXT saja */
        @keyframes bounceText {
            0% {
                transform: translateY(-30px);
                opacity: 0;
            }

            50% {
                transform: translateY(10px);
                opacity: 1;
            }

            100% {
                transform: translateY(0);
            }
        }

        .bounce-text {
            animation: bounceText 1.5s ease-out infinite alternate;
        }

        .logo-img {
            width: 120px;
            height: auto;
        }

        .text-logo {
            width: 200px;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="flex flex-col items-center justify-center space-y-6">

        <!-- LOGO (DIAM) -->
        <img src="{{ asset('images/logo_w_connect_web.gif') }}" alt="Logo W_Connect" class="logo-img" loading="eager">

        <!-- TEXT (BOUNCE) -->
        <img src="{{ asset('images/text_w_connect.png') }}" alt="Text W_Connect" class="text-logo bounce-text"
            loading="eager">

        <!-- CREDIT -->
        <div class="text-center text-gray-600 text-sm mt-4">
            <p class="font-semibold">By:AsthA Production</p>
            <p class="text-xs tracking-widest">Version 0.0.1</p>
        </div>

    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ route('showlogin') }}";
        }, 3000);
    </script>

</body>

</html>
