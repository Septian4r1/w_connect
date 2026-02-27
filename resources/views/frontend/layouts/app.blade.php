<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
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
           FRAME APP
        =============================== */
        .app-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            height: 100vh;
            border-radius: 25px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ===============================
           HEADER (FIXED)
        =============================== */
        .header {
            background: linear-gradient(180deg, #d9f3ea, #ffffff);
            padding: 20px;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            z-index: 1000;
        }

        /* ===============================
           CONTENT (SCROLL AREA)
        =============================== */
        .app-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            margin-top: 80px;
            /* tinggi header */
            margin-bottom: 65px;
            /* tinggi bottom nav */
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
           BOTTOM NAV (FIXED)
        =============================== */
        .bottom-nav {
            position: fixed;
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
           NAV ITEM
        =============================== */
        .nav-item {
            color: #777;
            text-decoration: none;
        }

        .nav-item.active {
            color: #1abc9c;
            font-weight: bold;
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

            .header,
            .bottom-nav {
                max-width: 100%;
            }
        }
    </style>

    <style>
        .banner-wrapper {
            width: 100%;
            overflow: hidden;
            border-radius: 16px;
            margin-bottom: 15px;
        }

        .banner-slider {
            display: flex;
            width: 300%;
            animation: slideBanner 12s infinite;
        }

        .banner-item {
            width: 100%;
            flex-shrink: 0;
            padding: 20px;
            color: #fff;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .banner-1 {
            background: linear-gradient(135deg, #1abc9c, #16a085);
        }

        .banner-2 {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .banner-3 {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .banner-item h5 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .banner-item small {
            opacity: 0.9;
        }

        @keyframes slideBanner {
            0% {
                transform: translateX(0);
            }

            30% {
                transform: translateX(0);
            }

            35% {
                transform: translateX(-100%);
            }

            65% {
                transform: translateX(-100%);
            }

            70% {
                transform: translateX(-200%);
            }

            95% {
                transform: translateX(-200%);
            }

            100% {
                transform: translateX(0);
            }
        }
    </style>
    <style>
        .program-wrapper {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        /* Kotak memanjang seperti JMO, dengan shadow lebih jelas dan efek hover */
        .program-item {
            background: white;
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;

            /* Shadow lebih tegas agar terlihat mengambang */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #2ecc71;

            transition: transform 0.2s, box-shadow 0.2s;
        }

        /* Hover effect */
        .program-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Judul */
        .program-title {
            font-weight: 600;
            font-size: 15px;
            color: #333;
        }

        /* Deskripsi (optional tetap kecil) */
        .program-text small {
            font-size: 12px;
            color: #666;
        }

        /* Checklist kanan */
        .program-check {
            font-size: 22px;
            color: #2ecc71;
        }
    </style>
    <style>
        /* ==============================
            GLOBAL LOADER
            ============================== */
        #globalLoader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            /* overlay tipis */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            display: none;
            /* default hidden */
        }

        #globalLoader .spinner {
            border: 4px solid #eee;
            border-top: 4px solid #1abc9c;
            /* hijau */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }


    </style>
</head>

<body>

    <!-- FRAME APP -->
    <div class="app-container">

        <!-- HEADER -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Citra Swarna Riverside </strong>
                    <div class="fw-bold">@yield('header-title', 'Beranda')</div>
                </div>
                <i class="bi bi-bell fs-5"></i>
            </div>
        </div>

        <!-- CONTENT (SCROLL ONLY THIS) -->
        <div class="app-content">
            @yield('content')
        </div>

    </div>

    <!-- BOTTOM NAV -->
    <div class="bottom-nav d-flex justify-content-around py-2">

        <a href="{{ route('homeWarga') }}"
            class="text-center nav-item {{ request()->routeIs('homeWarga') ? 'active' : '' }}">
            <i class="bi bi-house"></i><br>
            <small>Beranda</small>
        </a>

        <a href="{{ route('berita') }}" class="text-center nav-item {{ request()->routeIs('berita') ? 'active' : '' }}">
            <i class="bi bi-newspaper"></i><br>
            <small>Berita</small>
        </a>

        <a href="{{ route('kontak') }}" class="text-center nav-item {{ request()->routeIs('kontak') ? 'active' : '' }}">
            <i class="bi bi-chat-dots"></i><br>
            <small>Kontak</small>
        </a>

        <a href="{{ route('profil') }}" class="text-center nav-item {{ request()->routeIs('profil') ? 'active' : '' }}">
            <i class="bi bi-person"></i><br>
            <small>Profil</small>
        </a>

    </div>

    <!-- GLOBAL LOADER -->
    <div id="globalLoader">
        <div class="spinner"></div>
    </div>

    @if (!empty($layananApprovalPending) && $layananApprovalPending)
        <!-- MODAL PERSETUJUAN LAYANAN RESMI -->
        <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <!-- HEADER -->
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="approvalModalLabel">Persetujuan Layanan Resmi</h5>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">
                        <p>
                            Sebelum menggunakan layanan perumahan <strong>Citra Swarna Riverside</strong>, Anda
                            diwajibkan menyetujui <strong>syarat & ketentuan resmi</strong> berikut:
                        </p>

                        <ol>
                            <li>
                                Layanan ini hanya diperuntukkan bagi penghuni resmi yang terdaftar secara sah dalam
                                sistem manajemen perumahan.
                                Setiap penggunaan oleh pihak yang tidak berwenang dianggap sebagai akses tanpa hak.
                            </li>

                            <li>
                                Seluruh data pribadi dan informasi yang dimasukkan ke dalam sistem digunakan untuk
                                kepentingan administrasi,
                                pengelolaan layanan, serta kepentingan internal manajemen sesuai dengan peraturan
                                perundang-undangan yang berlaku.
                            </li>

                            <li>
                                Pengguna bertanggung jawab sepenuhnya atas keamanan akun, termasuk namun tidak terbatas
                                pada kerahasiaan
                                kata sandi (password), aktivitas yang dilakukan melalui akun tersebut, serta segala
                                akibat hukum yang timbul.
                            </li>

                            <li>
                                Setiap bentuk penyalahgunaan layanan, termasuk namun tidak terbatas pada akses tanpa
                                hak, manipulasi data,
                                pemalsuan identitas, gangguan terhadap sistem, penyebaran informasi palsu, atau tindakan
                                yang merugikan
                                manajemen dan/atau penghuni lainnya, dapat dikenakan sanksi administratif maupun
                                tindakan hukum sesuai
                                dengan ketentuan peraturan perundang-undangan yang berlaku di Republik Indonesia.
                            </li>

                            <li>
                                Manajemen berhak secara sepihak untuk menolak, membatasi, menangguhkan, atau
                                menghentikan akses layanan
                                tanpa pemberitahuan terlebih dahulu apabila ditemukan adanya pelanggaran terhadap
                                ketentuan ini,
                                peraturan perumahan, maupun ketentuan hukum yang berlaku.
                            </li>

                            <li>
                                Pengguna menyatakan dan menjamin bahwa seluruh data dan informasi yang diberikan adalah
                                benar, sah,
                                dan dapat dipertanggungjawabkan secara hukum. Apabila di kemudian hari ditemukan
                                ketidaksesuaian atau
                                unsur pelanggaran hukum, maka pengguna bersedia menanggung seluruh konsekuensi hukum
                                yang timbul.
                            </li>

                            <li>
                                Dengan menyetujui ketentuan ini, pengguna secara sadar dan tanpa paksaan menyatakan
                                tunduk dan terikat
                                pada seluruh syarat dan ketentuan, serta bersedia mempertanggungjawabkan setiap
                                pelanggaran baik secara
                                perdata maupun pidana sesuai hukum yang berlaku di wilayah hukum Negara Republik
                                Indonesia.
                            </li>

                            <li>
                                Apabila penyalahgunaan layanan menimbulkan kerugian materiil maupun immateriil bagi
                                manajemen atau pihak lain,
                                maka pengguna dapat dimintakan pertanggungjawaban ganti rugi sesuai ketentuan hukum yang
                                berlaku.
                            </li>
                        </ol>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="" id="agreeCheck">
                            <label class="form-check-label fw-bold" for="agreeCheck">
                                Saya telah membaca, memahami, dan menyetujui semua <strong>syarat & ketentuan
                                    resmi</strong> penggunaan layanan Citra Swarna Riverside.
                            </label>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnAgree" disabled>Setuju &
                            Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modalEl = document.getElementById('approvalModal');
        if (!modalEl) return;

        const approvalModal = new bootstrap.Modal(modalEl);
        const checkbox = document.getElementById('agreeCheck');
        const btnAgree = document.getElementById('btnAgree');

        // Tampilkan modal otomatis
        approvalModal.show();

        // Aktifkan tombol hanya jika checkbox dicentang
        checkbox.addEventListener('change', function() {
            btnAgree.disabled = !this.checked;
        });

        // Tombol Setuju & Lanjutkan
        btnAgree.addEventListener('click', function() {
            const rumahId = "{{ session('rumah_id') }}";

            if (!rumahId || rumahId === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'ID rumah tidak ditemukan',
                    text: 'Silakan login ulang.',
                });
                return;
            }

            fetch("{{ route('setujuLayanan') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        rumah_id: rumahId
                    })
                })
                .then(async res => {
                    const data = await res.json().catch(() => null);

                    if (!data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Server tidak merespon JSON. Cek log Laravel.',
                        });
                        return;
                    }

                    if (data.status === 'success') {
                        approvalModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            location.reload(); // reload supaya middleware update
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message ||
                                "Gagal menyimpan persetujuan. Silakan coba lagi.",
                        });
                    }
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Server',
                        text: 'Terjadi kesalahan server. Silakan coba lagi.',
                    });
                });
        });
    });
</script>

</html>
