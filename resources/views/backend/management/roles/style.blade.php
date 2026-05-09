<style>
    /* ================= CARD ================= */
    .card {
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        border: none;
    }

    /* ================= TABLE ================= */
    table.dataTable {
        width: 100% !important;
        border-collapse: separate !important;

    }

    table.dataTable thead th {
        font-size: 11px;
        letter-spacing: .6px;
        color: #000000;
        font-weight: 600;
        border: none !important;
        padding-bottom: 6px;
    }

    /* ROW STYLE */
    table.dataTable tbody tr {
        background: #fff;
        box-shadow:
            0 1px 2px rgba(0, 0, 0, 0.04),
            0 4px 10px rgba(0, 0, 0, 0.06);
        transition: all .18s ease;
    }

    table.dataTable tbody tr:hover {
        background: #f9fafb;
        box-shadow:
            0 6px 14px rgba(0, 0, 0, 0.08),
            0 10px 25px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    table.dataTable td {
        border: none !important;
        padding: 12px 14px;
    }

    table.dataTable tbody tr td:first-child {
        border-radius: 10px 0 0 10px;
    }

    table.dataTable tbody tr td:last-child {
        border-radius: 0 10px 10px 0;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    /* ================= HEADER ================= */
    .card-header {
        border-bottom: 1px solid #f1f1f1 !important;
    }

    /* ================= BUTTON ================= */
    .btn-soft {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        transition: .18s;
    }

    .btn-soft:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }

    .btn-soft i {
        font-size: 12px;
    }

    /* ================= DATATABLE ================= */
    .dataTables_wrapper {
        padding: 10px 14px;
    }

    .dataTables_filter {
        width: 100%;
        margin-bottom: 12px;
    }

    .dataTables_filter label {
        font-size: 0;
    }

    .dataTables_filter input {
        max-width: 180px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 6px 12px;
        font-size: 13px;
        background: #f9fafb;
    }

    .dataTables_filter input:focus {
        background: #fff;
        border-color: #d1d5db;
    }

    /* PAGINATION */
    .dataTables_paginate {
        margin-top: 10px;
    }

    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        padding: 5px 10px !important;
        margin: 0 3px;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        font-size: 12px;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #00ff48 !important;
        color: #fff !important;
    }

    /* HIDE DEFAULT */
    .dataTables_info,
    .dataTables_length {
        display: none;
    }

    /* ACTION FADE */
    tbody tr td:last-child {
        opacity: .5;
        transition: .2s;
    }

    tbody tr:hover td:last-child {
        opacity: 1;
    }

    /* ================= MOBILE ================= */
    /* ================= MOBILE ================= */
    @media (max-width:468px) {

        /* ================= FULL WIDTH ================= */
        .container-fluid {
            padding-left: 6px;
            padding-right: 6px;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        .col-md-3,
        .col-md-9 {
            padding-left: 4px;
            padding-right: 4px;
        }

        .card {
            border-radius: 14px;
        }

        /* 🔥 DEFAULT: hanya permission yg scroll */
        .card-body {
            padding: 8px;
            overflow-x: hidden;
        }

        /* ================= ROLES TABLE (NO SCROLL) ================= */
        #rolesTable {
            width: 100% !important;
            min-width: 100% !important;
            table-layout: fixed;
            font-size: 12px;
        }

        #rolesTable th,
        #rolesTable td {
            white-space: normal !important;
            /* biar wrap */
            padding: 8px;
        }

        #rolesTable td:last-child {
            width: 60px;
            text-align: center;
        }

        /* 🔥 penting: hilangkan efek min-width global */
        #rolesTable.dataTable {
            min-width: unset !important;
        }

        /* ================= PERMISSION TABLE (TETAP SCROLL) ================= */
        #permissionTable {
            min-width: 900px;
            /* hanya ini yg bisa geser */
            font-size: 11px;
        }

        /* bungkus scroll hanya di permission */
        #permissionTable_wrapper .dataTables_scrollBody,
        #permissionTable_wrapper {
            overflow-x: auto;
        }

        /* ================= TABLE UMUM ================= */
        table.dataTable thead {
            display: table-header-group !important;
        }

        table.dataTable tbody tr {
            display: table-row !important;
        }

        table.dataTable td {
            display: table-cell !important;
            padding: 6px 8px;
        }

        table.dataTable th {
            font-size: 10px;
            padding: 6px 8px;
        }

        /* ================= KECILIN UI ================= */
        .table-img {
            width: 30px;
            height: 30px;
        }

        .btn-soft {
            width: 22px;
            height: 22px;
        }

        .btn-soft i {
            font-size: 10px;
        }

        /* SEARCH FULL */
        .dataTables_filter input {
            width: 100%;
            max-width: 100%;
            font-size: 12px;
        }

        /* bikin semua kolom jadi full */
        .col-md-3,
        .col-md-9 {
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* hilangkan jarak antar kolom */
        .row {
            gap: 8px;
        }

        /* card full lebar */
        .card {
            width: 100% !important;
            margin: 0 !important;
        }

        /* biar mepet ke layar */
        .container-fluid {
            padding-left: 4px;
            padding-right: 4px;
        }

        /* optional: biar lebih "edge to edge" */
        .card-body {
            padding: 8px;
        }
    }

    /* ================= SWEETALERT = MODAL BOOTSTRAP STYLE ================= */
    .swal-popup-mini {
        border-radius: 8px !important;
        /* 🔥 ini kunci (bukan 18px lagi) */
        padding: 0 !important;
        overflow: hidden !important;

        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.15) !important;

        background: #fff !important;
    }

    /* HEADER (seperti modal-header) */
    .swal2-title {
        font-size: 14px !important;
        font-weight: 600 !important;
        text-align: left !important;

        padding: 12px 16px !important;
        margin: 0 !important;

        border-bottom: 1px solid #f1f1f1;
    }

    /* BODY */
    .swal2-html-container {
        text-align: left !important;
        padding: 14px 16px !important;
        margin: 0 !important;
    }

    /* LABEL */
    .swal-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    /* INPUT */
    .swal-input-mini {
        width: 100%;
        border-radius: 6px;
        /* lebih kotak */
        border: 1px solid #dee2e6;
        background: #fff;
        padding: 8px 10px;
        font-size: 13px;
    }

    .swal-input-mini:focus {
        border-color: #dc3545;
        outline: none;
    }

    /* FOOTER (button area) */
    .swal2-actions {
        margin: 0 !important;
        padding: 12px 16px !important;
        border-top: 1px solid #f1f1f1;

        justify-content: flex-end !important;
        gap: 6px;
    }

    /* BUTTON STYLE */
    .swal2-confirm {
        background: #dc3545 !important;
        color: #fff !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
    }

    .swal2-cancel {
        background: #f1f3f5 !important;
        color: #333 !important;
        border-radius: 6px !important;
        padding: 6px 14px !important;
        font-size: 13px !important;
    }

    /* 🔥 HILANGKAN SCROLL KANAN KIRI TOTAL */
    .swal2-html-container {
        overflow-x: hidden !important;
        overflow-y: hidden !important;
        width: 100% !important;
    }

    /* 🔥 PASTIKAN POPUP TIDAK MELEBAR */
    .swal2-popup {
        overflow-x: hidden !important;
    }

    /* 🔥 FIX INPUT AGAR TIDAK OVERFLOW */
    .swal2-input,
    .swal-input-mini {
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* 🔥 FIX BODY AGAR RAPAT */
    .swal2-html-container>* {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .swal2-html-container {
        padding: 14px 16px !important;
    }

    /* hilangkan gap aneh bawaan swal */
    .swal2-input {
        margin: 0 !important;
    }

    /* ================= FIX TABLE WIDTH ================= */
    #permissionTable {
        table-layout: fixed;
        width: 100%;
    }

    /* biar text tidak ancur */
    #permissionTable th,
    #permissionTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ================= KOLOM PROPORSI ================= */
    #permissionTable th:nth-child(1),
    #permissionTable td:nth-child(1) {
        width: 40px;
        text-align: center;
    }

    #permissionTable th:nth-child(2),
    #permissionTable td:nth-child(2) {
        width: 70px;
        text-align: center;
    }

    #permissionTable th:nth-child(3),
    #permissionTable td:nth-child(3) {
        width: 160px;
    }

    #permissionTable th:nth-child(4),
    #permissionTable td:nth-child(4) {
        width: 100px;
    }

    #permissionTable th:nth-child(5),
    #permissionTable td:nth-child(5) {
        width: 120px;
    }

    #permissionTable th:nth-child(6),
    #permissionTable td:nth-child(6) {
        width: 110px;
    }

    #permissionTable th:nth-child(7),
    #permissionTable td:nth-child(7) {
        width: 130px;
    }

    #permissionTable th:nth-child(8),
    #permissionTable td:nth-child(8) {
        width: 90px;
        text-align: center;
    }

    #permissionTable th:nth-child(9),
    #permissionTable td:nth-child(9) {
        width: 90px;
        text-align: center;
    }

    #permissionTable th:nth-child(10),
    #permissionTable td:nth-child(10) {
        width: 90px;
        text-align: center;
    }

    /* IMAGE TABLE */
    .table-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 8px;
    }

    .text-wrap {
        white-space: normal !important;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 4px 10px;
    }

    .select2-container--default .select2-selection__rendered {
        line-height: 28px;
        font-size: 14px;
    }

    .select2-container--default .select2-selection__arrow {
        height: 36px;
    }

    /* ================= TREE STYLE ================= */
    .tree-cell {
        position: relative;
        font-size: 13px;
    }

    /* garis vertikal */
    .tree-line {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #e5e7eb;
    }

    /* garis horizontal */
    .tree-branch {
        display: inline-block;
        width: 18px;
        height: 1px;
        background: #d1d5db;
        margin-right: 6px;
        vertical-align: middle;
    }

    /* ================= BADGE MODERN ================= */
    .badge-soft {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 8px;
        font-weight: 500;
        display: inline-block;
    }

    /* status */
    .badge-success-soft {
        background: #ecfdf5;
        color: #059669;
    }

    .badge-secondary-soft {
        background: #FFECCD;
        color: #000000;
    }

    /* permission */
    .badge-info-soft {
        background: #eef2ff;
        color: #4338ca;
    }

    /* ================= ICON STYLE ================= */
    .menu-icon {
        font-size: 14px;
        color: #6b7280;
    }

    .tree-cell:hover {
        background: #f9fafb;
        border-radius: 8px;
    }

    /* highlight parent-child saat hover */
    tbody tr:hover .tree-cell {
        background: #f9fafb;
        border-radius: 6px;
    }

    /* =========================================
   MOBILE FIX KHUSUS MENU TABLE
========================================= */
    @media (max-width:576px) {

        /* ================= TABLE ================= */
        #menuTable {
            font-size: 11px;
        }

        #menuTable th {
            font-size: 10px;
            padding: 6px 6px;
            white-space: nowrap;
        }

        #menuTable td {
            font-size: 11px;
            padding: 6px 6px;
            vertical-align: middle;
        }

        /* ================= KOLOM PROPORSI ================= */
        #menuTable th:nth-child(1),
        #menuTable td:nth-child(1) {
            min-width: 140px;
        }

        #menuTable th:nth-child(2),
        #menuTable td:nth-child(2) {
            min-width: 120px;
        }

        #menuTable th:nth-child(3),
        #menuTable td:nth-child(3) {
            width: 40px;
            text-align: center;
        }

        #menuTable th:nth-child(4),
        #menuTable td:nth-child(4) {
            display: none;

        }

        #menuTable th:nth-child(5),
        #menuTable td:nth-child(5) {
            width: 50px;
            text-align: center;
        }

        #menuTable th:nth-child(6),
        #menuTable td:nth-child(6) {
            width: 70px;
            text-align: center;
        }

        #menuTable th:nth-child(7),
        #menuTable td:nth-child(7) {
            display: none;

        }

        #menuTable th:nth-child(8),
        #menuTable td:nth-child(8) {
            width: 60px;
            text-align: center;
        }

        /* ================= TREE ================= */
        .tree-cell {
            font-size: 11px;
        }

        .tree-branch {
            width: 10px;
            margin-right: 4px;
        }

        .tree-line {
            left: 6px !important;
        }

        /* ================= ICON ================= */
        .menu-icon {
            font-size: 12px;
        }

        /* ================= BADGE ================= */
        .badge-soft {
            font-size: 9px;
            padding: 2px 6px;
        }

        /* ================= BUTTON ================= */
        .btn-soft {
            width: 20px;
            height: 20px;
        }

        .btn-soft i {
            font-size: 10px;
        }

        /* ================= SEARCH ================= */
        .dataTables_filter input {
            font-size: 11px;
            padding: 5px 8px;
        }

        /* ================= PAGINATION ================= */
        .dataTables_paginate .paginate_button {
            font-size: 10px;
            padding: 4px 6px !important;
        }

        /* ================= CARD ================= */
        .card-body {
            padding: 6px;
        }
    }


    /* =========================================
   MODAL MOBILE FIX
========================================= */
    @media (max-width:576px) {

        #modalEditMenu .modal-dialog {
            margin: 10px;
            max-width: 100%;
        }

        #modalEditMenu .modal-content {
            border-radius: 10px;
        }

        #modalEditMenu .form-control,
        #modalEditMenu .form-select {
            font-size: 12px;
            padding: 6px 8px;
        }

        #modalEditMenu label {
            font-size: 11px;
        }

        #modalEditMenu .btn {
            font-size: 11px;
            padding: 5px 10px;
        }

        /* permission badge */
        #selectedPermissions .badge-soft {
            font-size: 10px;
            padding: 4px 6px;
        }
    }

    /* ================= FULL WIDTH MOBILE FIX ================= */
    @media (max-width:576px) {

        /* container benar-benar full */
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* hilangkan padding bootstrap row */
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* kolom full */
        .col-md-12 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        /* 🔥 CARD FULL EDGE */
        .card {
            border-radius: 0 !important;
            /* biar nempel */
            margin: 0 !important;
            width: 100% !important;
        }

        /* body rapat */
        .card-body {
            padding: 10px !important;
        }

        /* header rapat */
        .card-header {
            padding: 10px !important;
        }
    }

    @media (max-width:576px) {

        body {
            background: #f3f4f6;
        }

        .card {
            box-shadow: none !important;
            /* hilangkan shadow */
            border-bottom: 1px solid #e5e7eb;
        }
    }

    /* =========================================
MOBILE PERMISSION TABLE
========================================= */

    .permission-table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #menuTable {
        min-width: 100%;
        border-collapse: separate;
    }

    #menuTable th {
        white-space: nowrap;
        font-size: 11px;
    }

    #menuTable td {
        vertical-align: top;
    }

    .permission-group {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .permission-item {
        background: #f3f4f6;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 11px;
    }

    @media (max-width:576px) {

        #menuTable {
            font-size: 11px;
        }

        #menuTable th:nth-child(1) {
            min-width: 140px;
        }

        #menuTable th:nth-child(2) {
            min-width: 120px;
        }

        #menuTable th:nth-child(3) {
            min-width: 260px;
        }

        #menuTable td {
            padding: 8px 6px;
        }

        .form-check {
            margin-bottom: 4px;
        }

        .form-check-label {
            font-size: 10px;
        }

        .permission-checkbox {
            transform: scale(.9);
        }
    }
</style>
