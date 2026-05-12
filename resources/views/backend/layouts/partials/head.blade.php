 <!-- Required meta tags -->
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <!-- 🔥 WAJIB UNTUK AJAX -->
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <!-- loader-->
 <link href="{{ asset('tamplate_management/assets/css/pace.min.css') }}" rel="stylesheet" />
 <script src="{{ asset('tamplate_management/assets/js/pace.min.js') }}"></script>

 <!--plugins-->
 <link href="{{ asset('tamplate_management/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}"
     rel="stylesheet" />
 <link href="{{ asset('tamplate_management/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
 <link href="{{ asset('tamplate_management/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />

 <!-- CSS Files -->
 <link href="{{ asset('tamplate_management/assets/css/bootstrap.min.css') }}" rel="stylesheet">
 <link href="{{ asset('tamplate_management/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
 <link href="{{ asset('tamplate_management/assets/css/style.css') }}" rel="stylesheet">
 <link href="{{ asset('tamplate_management/assets/css/icons.css') }}" rel="stylesheet">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
 <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

 <!--Theme Styles-->
 <link href="{{ asset('tamplate_management/assets/css/dark-theme.css') }}" rel="stylesheet" />
 <link href="{{ asset('tamplate_management/assets/css/semi-dark.css') }}" rel="stylesheet" />
 <link href="{{ asset('tamplate_management/assets/css/header-colors.css') }}" rel="stylesheet" />

 <!-- DataTables CSS & JS -->
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

 <!-- Select2 CSS -->
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <!-- Select2 Bootstrap 5 Theme -->
 <link
     href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css"rel="stylesheet" />
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

 <style>
     .dropdown-user-name,
     .dropdown-user-designation {
         white-space: nowrap;
         overflow: hidden;
         text-overflow: ellipsis;
     }

     .list-group-item:hover {
         background: #f8f9fa;
         transition: 0.2s;
     }
 </style>




 <title>W_connect Management</title>
