<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <!-- WAJIB: supaya ukuran mengikuti layar HP -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===============================
           GLOBAL
        =============================== */
        body {
            background: #f5f7fb;
            display: flex;
            justify-content: center;
            margin: 0;
        }

        /* ===============================
           FRAME APP (MODE DESKTOP)
        =============================== */
        .app-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            height: 100vh;
            /* tinggi penuh layar */
            border-radius: 25px;
            overflow: hidden;
            display: flex;
            /* penting untuk layout flex */
            flex-direction: column;
            /* header - content */
        }

        /* ===============================
           HEADER (TETAP)
        =============================== */
        .header {
            background: linear-gradient(180deg, #d9f3ea, #ffffff);
            padding: 20px;
            flex-shrink: 0;
            /* supaya tidak mengecil */
        }

        /* ===============================
           CONTENT (AREA SCROLL)
        =============================== */
        .app-content {
            flex: 1;
            /* ambil sisa tinggi layar */
            overflow-y: auto;
            /* HANYA BAGIAN INI SCROLL */
            padding: 1rem;
        }

        /* ===============================
           CARD PROGRAM
        =============================== */
        .card-program {
            border-radius: 15px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
        }

        /* ===============================
           ICON MENU
        =============================== */
        .service-icon {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            background: #e8f5f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .service-icon i {
            font-size: 22px;
            color: #1abc9c;
        }

        /* ===============================
           BOTTOM NAV (TETAP)
        =============================== */
        .bottom-nav {
            position: fixed;
            /* selalu di bawah layar */
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-top: 1px solid #ddd;
            z-index: 999;
        }

        /* ===============================
           MODE HP ASLI
        =============================== */
        @media (max-width: 576px) {
            body {
                background: #fff;
            }

            .app-container {
                max-width: 100%;
                border-radius: 0;
            }

            .bottom-nav {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- ===============================
     CONTAINER APP
=============================== -->
    <div class="app-container">

        <!-- HEADER (TETAP) -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>JMO</strong>
                    <div class="fw-bold">Program Layanan</div>
                </div>
                <i class="bi bi-bell fs-5"></i>
            </div>
        </div>

        <!-- CONTENT (SCROLL) -->
        <div class="app-content">

            <!-- PROGRAM LAYANAN -->
            <div class="card card-program p-3 mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-shield-check fs-4 text-success me-3"></i>
                    <div class="flex-grow-1">
                        <strong>Jaminan Hari Tua</strong>
                        <div class="text-muted small">Anda sudah terdaftar</div>
                    </div>
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>

                <div class="d-flex align-items-center">
                    <i class="bi bi-briefcase fs-4 text-primary me-3"></i>
                    <div class="flex-grow-1">
                        <strong>Jaminan Kecelakaan Kerja</strong>
                        <div class="text-muted small">Anda sudah terdaftar</div>
                    </div>
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
            </div>

            <!-- LAYANAN LAINNYA -->
            <h6 class="fw-bold mb-3">Layanan Lainnya</h6>

            <div class="row text-center g-3">
                @php
                    $menus = [
                        ['icon' => 'cash-stack', 'title' => 'Pembayaran'],
                        ['icon' => 'tag', 'title' => 'Promo'],
                        ['icon' => 'arrow-repeat', 'title' => 'Pengkinian Data'],
                        ['icon' => 'people', 'title' => 'Mitra'],
                        ['icon' => 'info-circle', 'title' => 'Info Program'],
                        ['icon' => 'clipboard-data', 'title' => 'Pelaporan'],
                        ['icon' => 'building', 'title' => 'Kantor Cabang'],
                        ['icon' => 'megaphone', 'title' => 'Pengaduan'],
                    ];
                @endphp

                @foreach ($menus as $m)
                    <div class="col-3">
                        <div class="service-icon mb-1">
                            <i class="bi bi-{{ $m['icon'] }}"></i>
                        </div>
                        <small>{{ $m['title'] }}</small>
                    </div>
                @endforeach
            </div>

            <!-- TAMBAHAN (biar kelihatan efek scroll, opsional) -->
            <div class="mt-4">
                @for ($i = 0; $i < 3; $i++)
                    <div class="card p-3 mb-2">Konten tambahan {{ $i + 1 }}</div>
                @endfor
            </div>

        </div>
    </div>

    <!-- ===============================
     BOTTOM NAV (TETAP)
=============================== -->
    <div class="bottom-nav d-flex justify-content-around py-2">
        <div class="text-center">
            <i class="bi bi-house"></i><br>
            <small>Beranda</small>
        </div>
        <div class="text-center">
            <i class="bi bi-newspaper"></i><br>
            <small>Berita</small>
        </div>
        <div class="text-center">
            <i class="bi bi-chat-dots"></i><br>
            <small>Kontak</small>
        </div>
        <div class="text-center">
            <i class="bi bi-person"></i><br>
            <small>Profil</small>
        </div>
    </div>

</body>

</html>
