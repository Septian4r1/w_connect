<!doctype html>
<html lang="en" class="semi-dark">

<head>
    @include('backend.layouts.partials.head')
</head>

<body>


    <!--start wrapper-->
    <div class="wrapper">

        <!--start sidebar -->
        @include('backend.layouts.partials.sidebar')
        <!--end sidebar -->

        <!--start top header-->
        @include('backend.layouts.partials.header')
        <!--end top header-->


        <!-- start page content wrapper-->
        <div class="page-content-wrapper">
            <!-- start page content-->
            <div class="page-content">

                <div class="container-fluid px-2">
                    @yield('content')
                </div>
            </div>
            <!-- end page content-->
        </div>

        <footer class="footer sticky-bottom">
            <span> By : AsthA production &nbsp;|&nbsp; Versi {{ config('app.version') }}</span>
        </footer>

        @include('backend.layouts.partials.theme_collor')


    </div>
    <!--end wrapper-->

    <!-- JS Files-->
    <script src="{{ asset('tamplate_management/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('tamplate_management/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('tamplate_management/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('tamplate_management/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <!--plugins-->
    <script src="{{ asset('tamplate_management/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

    <!-- Main JS-->
    <script src="{{ asset('tamplate_management/assets/js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <!-- Select2 JS -->
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif



    @stack('scripts')

    <div class="modal fade" id="settingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-gear me-2"></i> Setting Menu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="list-group">

                        {{-- PASSWORD --}}
                        <a href="{{ route('management.change_password') }}"
                            class="list-group-item list-group-item-action d-flex align-items-center gap-2">

                            <i class="bi bi-key"></i>
                            Ganti Password

                        </a>

                        {{-- QR CARD --}}
                        <a href="#"
                            class="list-group-item list-group-item-action d-flex align-items-center gap-2">

                            <i class="bi bi-qr-code"></i>
                            QR Card

                        </a>

                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- <script>
        (function() {

            // =====================================
            // CONFIG
            // =====================================
            let devtoolsOpen = false;

            // =====================================
            // HELPER ALERT
            // =====================================
            function block(msg) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses Ditolak',
                        text: msg,
                        timer: 1200,
                        showConfirmButton: false
                    });
                } else {
                    alert(msg);
                }
            }

            // =====================================
            // RIGHT CLICK BLOCK
            // =====================================
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                block('Klik kanan dinonaktifkan');
            });

            // =====================================
            // COPY / CUT / PASTE BLOCK
            // =====================================
            ['copy', 'cut', 'paste'].forEach(evt => {
                document.addEventListener(evt, function(e) {
                    e.preventDefault();
                    block(evt + ' tidak diizinkan');
                });
            });

            // =====================================
            // DRAG + SELECT BLOCK
            // =====================================
            document.addEventListener('dragstart', e => e.preventDefault());
            document.addEventListener('selectstart', e => e.preventDefault());

            // =====================================
            // KEYBOARD SHORTCUT BLOCK
            // =====================================
            document.addEventListener('keydown', function(e) {

                const key = e.key.toLowerCase();

                // DEVTOOLS
                if (
                    e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && ['i', 'j', 'c'].includes(key)) ||
                    (e.ctrlKey && key === 'u')
                ) {
                    e.preventDefault();
                    block('Developer tools diblokir');
                    return;
                }

                // SAVE / PRINT / REFRESH
                if (
                    (e.ctrlKey && key === 's') ||
                    (e.ctrlKey && key === 'p') ||
                    (e.ctrlKey && key === 'r') ||
                    e.key === 'F5'
                ) {
                    e.preventDefault();
                    block('Action diblokir');
                    return;
                }

                // COPY / SELECT ALL
                if (
                    (e.ctrlKey && key === 'c') ||
                    (e.ctrlKey && key === 'a')
                ) {
                    e.preventDefault();
                    block('Action diblokir');
                    return;
                }

                // ALT F4 (optional)
                if (e.altKey && e.key === 'F4') {
                    e.preventDefault();
                    block('Action diblokir');
                    return;
                }

            });

            // =====================================
            // DEVTOOLS DETECTION (ANTI INSPECT)
            // =====================================
            setInterval(() => {

                const widthDiff = window.outerWidth - window.innerWidth > 160;
                const heightDiff = window.outerHeight - window.innerHeight > 160;

                const detected = widthDiff || heightDiff;

                if (detected && !devtoolsOpen) {

                    devtoolsOpen = true;

                    document.body.style.filter = "blur(10px)";

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Inspect Terdeteksi',
                            text: 'Halaman dibatasi demi keamanan',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }

                }

                if (!detected && devtoolsOpen) {
                    devtoolsOpen = false;
                    document.body.style.filter = "none";
                }

            }, 1000);

        })();
    </script> --}}


</body>
