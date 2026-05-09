<style>
    /* ================= CARD ================= */
    .card {
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06);
        border: none;
    }

    /* ================= TABLE MODERN ================= */
    .modern-table {
        border-collapse: separate !important;
        border-spacing: 0 8px;
    }

    .modern-table thead th {
        font-size: 11px;
        color: #9ca3af;
        border: none;
        padding: 6px 14px;
    }

    .modern-table tbody tr {
        background: #fff;
        box-shadow:
            0 1px 2px rgba(0, 0, 0, .04),
            0 4px 10px rgba(0, 0, 0, .06);
        transition: .2s;
    }

    .modern-table tbody tr:hover {
        transform: translateY(-2px);
        background: #f9fafb;
    }

    .modern-table td {
        border: none !important;
        padding: 12px 14px;
    }

    /* ================= BADGE ================= */
    .badge-soft {
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 500;
    }

    .badge-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    /* ================= MOBILE FIX ================= */
    @media (max-width: 768px) {

        .modern-table thead th {
            font-size: 9px;
            padding: 4px 8px;
        }

        .modern-table td {
            font-size: 11px;
            padding: 8px;
        }

        .badge-soft {
            font-size: 9px;
            padding: 4px 6px;
        }

        .card-header h6 {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {

        .modern-table thead th {
            font-size: 8px;
        }

        .modern-table td {
            font-size: 10px;
        }

        .badge-soft {
            font-size: 8px;
        }
    }

    /* ================= ICON BUTTON ================= */
    .btn-icon {
        width: 28px;
        height: 28px;
        padding: 0;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: .2s;
        font-size: 14px;
        line-height: 1;
    }

    .btn-icon i {
        font-size: inherit;
    }

    .btn-soft-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-soft-warning:hover {
        background: #fde68a;
        transform: scale(1.08);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
    }

    /* ================= PAGINATION ================= */
    .pagination {
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .pagination {
            justify-content: center;
            flex-wrap: wrap;
            gap: 4px;
        }
    }

    /* ================= SWEETALERT ================= */
    .swal-title-custom {
        font-size: 14px !important;
        line-height: 1.2 !important;
        text-align: center;
    }

    .swal-content-custom {
        font-size: 13px !important;
        line-height: 1.2 !important;
        text-align: center;
    }

    /* ================= DATATABLE WRAPPER ================= */
    .dataTables_wrapper {
        padding: 10px 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* ================= SEARCH ================= */
    .dataTables_wrapper .dataTables_filter {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .dataTables_wrapper .dataTables_filter label {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        font-size: 0;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 240px;
        max-width: 100%;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 6px 12px;
        font-size: 13px;
        background: #f9fafb;
        transition: .2s;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        background: #fff;
        border-color: #d1d5db;
        outline: none;
    }

    /* ================= PAGINATION ================= */
    .dataTables_wrapper .dataTables_paginate {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        padding: 5px 10px !important;
        margin: 0 2px;
        border: 1px solid #e5e7eb !important;
        background: #fff !important;
        font-size: 12px;
        transition: .2s;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #dc3545 !important;
        color: #fff !important;
        border-color: #dc3545 !important;
    }

    .dataTables_info,
    .dataTables_length {
        display: none !important;
    }

    /* ================= MOBILE DATATABLE ================= */
    @media (max-width: 768px) {

        /* SEARCH CENTER */
        .dataTables_wrapper .dataTables_filter {
            justify-content: center !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
            max-width: 260px;
            font-size: 12px;
            padding: 6px 10px;
        }

        /* PAGINATION CENTER + KECIL */
        .dataTables_wrapper .dataTables_paginate {
            justify-content: center !important;
            gap: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 3px 7px !important;
            font-size: 10px !important;
            border-radius: 6px !important;
        }
    }

    /* ================= EXTRA SMALL ================= */
    @media (max-width: 480px) {

        .dataTables_wrapper .dataTables_filter input {
            font-size: 11px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 2px 6px !important;
            font-size: 9px !important;
        }

        /* ICON BUTTON SUPER COMPACT */
        .btn-icon {
            width: 14px !important;
            height: 14px !important;
            font-size: 8px !important;
            border-radius: 4px !important;
        }
    }
</style>
