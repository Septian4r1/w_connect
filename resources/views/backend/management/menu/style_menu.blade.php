<style>
    /* =========================
   GLOBAL CARD STYLE
========================= */
    .card {
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        border: none;
        background: #fff;
    }

    /* =========================
   HEADER
========================= */
    .card-header {
        border-bottom: 1px solid #f1f1f1 !important;
        background: #111827 !important;
        /* dark modern */
        color: #fff;
    }

    /* =========================
   TABLE BASE
========================= */
    table.dataTable {
        width: 100% !important;
        border-collapse: separate !important;
        border-spacing: 0 8px;
    }

    /* HEADER TABLE */
    table.dataTable thead th {
        font-size: 11px;
        letter-spacing: .5px;
        text-transform: uppercase;
        color: #000000;
        font-weight: 600;
        border-collapse: separate !important;
        border-spacing: 0 8px;
        padding: 10px 12px;
    }

    /* ROW */
    table.dataTable tbody tr {
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        transition: .2s ease;
        border-radius: 12px;
    }

    table.dataTable tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }

    /* CELL */
    table.dataTable td {
        border: none !important;
        padding: 12px;
        vertical-align: middle;
    }

    /* rounded row effect */
    table.dataTable tbody tr td:first-child {
        border-radius: 12px 0 0 12px;
    }

    table.dataTable tbody tr td:last-child {
        border-radius: 0 12px 12px 0;
    }

    /* =========================
   BUTTON
========================= */
    .btn-soft {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        border: 1px solid #D2D2D2;
        transition: .2s;
    }

    .btn-soft:hover {
        background: #000000;
        transform: translateY(-2px);
    }

    /* =========================
   BADGE MODERN
========================= */
    .badge-soft {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 8px;
        font-weight: 500;
    }

    .badge-success-soft {
        background: #ecfdf5;
        color: #059669;
    }

    .badge-danger-soft {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-info-soft {
        background: #eef2ff;
        color: #4f46e5;
    }

    /* =========================
   TREE STYLE (MENU HIERARCHY)
========================= */
    .tree-cell {
        position: relative;
        font-size: 13px;
    }

    .tree-line {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #e5e7eb;
    }

    .tree-branch {
        display: inline-block;
        width: 14px;
        height: 1px;
        background: #d1d5db;
        margin-right: 6px;
    }

    /* hover highlight */
    .tree-cell:hover {
        background: #f9fafb;
        border-radius: 8px;
    }

    /* =========================
   DATA TABLE UI CLEAN
========================= */
    .dataTables_wrapper {
        padding: 10px;
    }

    .dataTables_filter input {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 6px 10px;
        background: #f9fafb;
        font-size: 13px;
    }

    .dataTables_filter input:focus {
        background: #fff;
        border-color: #d1d5db;
    }

    /* hide default */
    .dataTables_info,
    .dataTables_length {
        display: none;
    }

    /* pagination */
    .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        padding: 5px 10px !important;
        font-size: 12px;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
    }

    .dataTables_paginate .paginate_button.current {
        background: #77FF00 !important;
        color: #fff !important;
        border: none !important;
    }

    /* =========================
   MOBILE CLEAN (IMPORTANT FIX)
========================= */
    @media (max-width:576px) {

        .container-fluid {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }

        .card {
            border-radius: 12px !important;
        }

        table.dataTable td {
            font-size: 11px;
            padding: 8px;
        }

        table.dataTable thead th {
            font-size: 10px;
        }

        .btn-soft {
            width: 22px;
            height: 22px;
        }

        .dataTables_filter input {
            width: 100%;
            font-size: 12px;
        }
    }

    /* =========================
   MODAL CLEAN STYLE
========================= */
    #modalEditMenu .modal-content {
        border-radius: 14px;
        overflow: hidden;
    }

    #modalEditMenu .form-control,
    #modalEditMenu .form-select {
        border-radius: 10px;
        font-size: 13px;
    }

    #modalEditMenu label {
        font-size: 11px;
        color: #6b7280;
    }

    /* =========================
   TABLE WRAPPER LOCK (ANTI SCROLL UI)
========================= */
    @media (max-width:576px) {

        /* WRAPPER TIDAK BOLEH SCROLL HORIZONTAL */
        .dataTables_wrapper {
            overflow-x: hidden !important;
            padding: 8px !important;
        }

        /* =========================
       SEARCH FIX (TIDAK IKUT SCROLL)
    ========================= */
        .dataTables_filter {
            width: 100% !important;
            float: none !important;
            text-align: left !important;
            margin-bottom: 8px;
        }

        .dataTables_filter input {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 10px;
        }

        /* =========================
       TABLE AREA (ONLY CONTENT SCROLLABLE)
    ========================= */
        .dataTables_scrollBody {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        /* =========================
       PAGINATION FIX (NO HORIZONTAL MOVE)
    ========================= */
        .dataTables_paginate {
            width: 100% !important;
            text-align: center !important;
            margin-top: 10px;
            overflow-x: hidden !important;
        }

        .dataTables_paginate .paginate_button {
            font-size: 11px !important;
            padding: 4px 8px !important;
        }

        /* =========================
       TABLE FONT MOBILE
    ========================= */
        table.dataTable td {
            font-size: 11px !important;
            padding: 8px !important;
            white-space: nowrap;
        }

        table.dataTable thead th {
            font-size: 10px !important;
            padding: 8px !important;
        }

        /* =========================
       CARD SAFETY
    ========================= */
        .card {
            border-radius: 12px !important;
            box-shadow: none !important;
        }
    }

    /* =========================
   TABLE SCROLL FIX (ONLY BODY SCROLL)
========================= */
    .table-wrapper-custom {
        width: 100%;
        overflow: hidden;
        /* 🔥 penting: lock UI luar */
    }

    /* DATA TABLE FORCE WIDTH (biar bisa scroll) */
    #menuTable {
        min-width: 900px !important;
    }

    /* WRAPPER DATA TABLE */
    .dataTables_wrapper {
        overflow: hidden !important;
    }

    /* =========================
   MOBILE ONLY SCROLL TABLE BODY
========================= */
    @media (max-width:576px) {

        /* SEARCH FIX (NO SCROLL) */
        .dataTables_filter {
            width: 100% !important;
            margin-bottom: 10px;
        }

        .dataTables_filter input {
            width: 100% !important;
            font-size: 12px;
        }

        /* PAGINATION FIX */
        .dataTables_paginate {
            text-align: center !important;
            width: 100% !important;
            margin-top: 10px;
        }

        /* 🔥 INI KUNCI SCROLL TABLE */
        .table-wrapper-custom {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        #menuTable {
            min-width: 900px !important;
        }

        /* FONT MOBILE */
        table.dataTable td {
            font-size: 10.5px !important;
            padding: 6px 8px !important;
            white-space: nowrap;
        }

        table.dataTable thead th {
            font-size: 9.5px !important;
            padding: 6px 8px !important;
            letter-spacing: .3px;
        }

        /* TREE TEXT (menu name) */
        .tree-cell {
            font-size: 11px !important;
        }

        /* BADGE */
        .badge-soft {
            font-size: 9px;
            padding: 2px 6px;
        }

        /* BUTTON */
        .btn-soft {
            width: 20px;
            height: 20px;
        }

        .btn-soft i {
            font-size: 10px;
        }

        /* SEARCH */
        .dataTables_filter input {
            font-size: 11px;
            padding: 5px 8px;
        }

        /* PAGINATION */
        .dataTables_paginate .paginate_button {
            font-size: 10px !important;
            padding: 3px 6px !important;
        }

        /* SCROLL AREA DATATABLE */
        .dataTables_scrollBody {
            overflow: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        /* FIX BIAR HEADER GA IKUT GESER */
        .dataTables_scrollHead {
            overflow: hidden !important;
        }

        /* MOBILE HEIGHT BIAR ENAK */
        @media (max-width:576px) {
            .dataTables_scrollBody {
                max-height: 55vh !important;
            }
        }
    }

    @media (max-width:576px) {
        .dataTables_filter {
            justify-content: flex-start;
            /* balik ke kiri */
        }

        .dataTables_filter input {
            width: 100% !important;
            /* full layar */
        }
    }

    /* =========================
   DESKTOP → KIRI
========================= */
    .dataTables_filter {
        float: none !important;
        /* matikan bawaan */
        display: flex !important;
        justify-content: flex-end !important;
        /* 🔥 kiri */
        width: 100%;
        margin-bottom: 12px;
    }

    /* input desktop */
    .dataTables_filter input {
        width: 260px;
        max-width: 100%;
    }

    @media (max-width:576px) {

        .dataTables_filter {
            justify-content: center !important;
            /* 🔥 kanan */
        }

        .dataTables_filter input {
            width: 180px !important;
            /* biar ga kepanjangan */
            text-align: center;
            /* opsional biar rapi */
        }
    }

    /* =========================
   PERMISSION TABLE FIX
========================= */

    #permissionsTable tbody tr {
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        transition: .2s;
    }

    #permissionsTable tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
    }

    #permissionsTable td {
        border: none !important;
        padding: 12px;
        vertical-align: middle;
    }

    /* rounded effect */
    #permissionsTable tbody tr td:first-child {
        border-radius: 12px 0 0 12px;
    }

    #permissionsTable tbody tr td:last-child {
        border-radius: 0 12px 12px 0;
    }
</style>
