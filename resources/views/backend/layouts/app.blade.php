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



</body>
