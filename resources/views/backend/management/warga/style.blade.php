<style>
    /* =========================
   CARD (GLOBAL)
========================= */
    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    /* =========================
   TABLE GLOBAL
========================= */
    .table {
        min-width: 900px;
    }

    .table td,
    .table th {
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-hover tbody tr:hover {
        background-color: #f9fafb;
        transition: 0.2s;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    thead a:hover {
        color: #dc3545 !important;
    }

    /* =========================
   BADGE
========================= */
    .badge-soft {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .badge-blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .badge-green {
        background: #ecfdf5;
        color: #059669;
    }

    .badge-gray {
        background: #f3f4f6;
        color: #6b7280;
    }

    .badge-orange {
        background: #fff7ed;
        color: #ea580c;
    }

    .badge-dark {
        background: #f1f5f9;
        color: #334155;
    }

    /* =========================
   BUTTON ICON
========================= */
    .btn-icon {
        width: 28px;
        height: 28px;
        padding: 0 !important;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-icon i {
        font-size: 13px;
    }

    .btn-icon:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    /* warna tombol */
    .btn-info {
        background: #e0f2fe;
        color: #0284c7;
        border: none;
    }

    .btn-warning {
        background: #fef3c7;
        color: #d97706;
        border: none;
    }

    .btn-danger {
        background: #fee2e2;
        color: #dc2626;
        border: none;
    }

    /* =========================
    HEADER DESKTOP (🔥 FIX UTAMA)
    ========================= */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* search desktop */
    .search-wrapper {
        max-width: 250px;
        width: 100%;
    }

    .search-input {
        height: 36px;
        font-size: 14px;
        border-radius: 8px;
    }

    /* =========================
    PAGINATION DESKTOP
    ========================= */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .pagination .page-link {
        border: none;
        border-radius: 6px;
        color: #374151;
        background: #f9fafb;
        font-size: 13px;
        padding: 6px 10px;
    }

    .pagination .page-item.active .page-link {
        background: #2563eb;
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
    }

    /* =========================
    MOBILE VERSION (🔥 AMAN)
    ========================= */
    @media (max-width: 768px) {

        /* container full */
        .container-fluid {
            padding: 0 !important;
        }

        /* card jadi flat */
        .card {
            border-radius: 0;
            box-shadow: none;
        }

        /* 🔥 HEADER JADI COLUMN (TIDAK GANGGU DESKTOP) */
        .card-header {
            flex-direction: column;
            align-items: stretch;
            padding: 16px !important;
        }

        /* 🔥 SEARCH FULL */
        .search-wrapper {
            max-width: 100%;
        }

        .search-input {
            width: 100% !important;
            height: 30px;

            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 12px;

        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08);
        }

        /* 🔥 BUTTON FULL */
        .card-header .btn {
            width: 100%;
            height: 32px;
            /* samakan dengan input */
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 8px;
        }

        /* table kecil */
        .table {
            font-size: 12px;
        }

        .table th,
        .table td {
            padding: 8px;
        }

        /* foto */
        .table img {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50%;
            object-fit: cover;
        }

        /* badge */
        .badge-soft {
            font-size: 10px;
            padding: 3px 8px;
        }

        /* icon kecil */
        .btn-icon {
            width: 26px;
            height: 26px;
        }

        .btn-icon i {
            font-size: 12px;
        }

        /* action spacing */
        td .d-flex {
            gap: 4px !important;
        }

        /* hide kolom */
        th:nth-child(6),
        td:nth-child(6),
        th:nth-child(7),
        td:nth-child(7),
        th:nth-child(8),
        td:nth-child(8) {
            display: none;
        }

        /* pagination */
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .pagination {
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .pagination .page-link {
            font-size: 12px;
            padding: 5px 9px;
        }

        #infoData {
            font-size: 11px;
            margin-bottom: 6px;
        }
    }

    /* =========================
   LOADING
========================= */
    .loading-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.7);
        z-index: 9999;

        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .loading-overlay.show {
        display: flex;
    }

    /* =========================
MODAL MODERN
========================= */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    /* header */
    .modal-header {
        padding: 14px 18px;
    }

    .modal-title {
        font-size: 16px;
        font-weight: 600;
    }

    /* body */
    .modal-body {
        padding: 16px 18px;
        font-size: 14px;
    }

    /* footer */
    .modal-footer {
        padding: 12px 18px;
    }

    /* =========================
PROFILE BOX
========================= */
    .profile-box img {
        width: 70px;
        height: 70px;
        object-fit: cover;
    }

    .profile-info h5 {
        font-size: 16px;
        margin-bottom: 4px;
    }

    .profile-info p {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 2px;
    }

    /* =========================
TAB
========================= */
    .nav-tabs .nav-link {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .nav-tabs .nav-link.active {
        background: #2563eb;
        color: #fff;
    }

    /* =========================
TABLE DALAM MODAL
========================= */
    .modal-body table th {
        width: 140px;
        font-weight: 500;
        font-size: 13px;
        color: #6b7280;
    }

    .modal-body table td {
        font-size: 13px;
        color: #111827;
    }

    /* =========================
DOKUMEN IMAGE
========================= */
    #tab-dokumen img {
        width: 100%;
        max-height: 180px;
        object-fit: cover;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.2s;
    }

    #tab-dokumen img:hover {
        transform: scale(1.03);
    }

    /* =========================
MOBILE FIX 🔥 (HALUS & NO SCROLL)
========================= */
    @media (max-width: 768px) {

        /* modal full */
        .modal-dialog {
            margin: 8px;
        }

        .modal-content {
            border-radius: 12px;
        }

        /* header */
        .modal-header {
            padding: 12px 14px;
        }

        .modal-title {
            font-size: 14px;
        }

        /* body */
        .modal-body {
            padding: 14px;
        }

        /* profile jadi column */
        .profile-box {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
        }

        .profile-box img {
            width: 58px;
            height: 58px;
        }

        .profile-info h5 {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .profile-info p {
            font-size: 12px;
            margin-bottom: 1px;
        }

        /* =========================
    TAB (🔥 NO SCROLL)
    ========================= */
        .nav-tabs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* jadi 2 kolom */
            gap: 6px;
            border: none;
        }

        .nav-tabs .nav-item {
            width: 100%;
        }

        .nav-tabs .nav-link {
            width: 100%;
            text-align: center;
            font-size: 12px;
            padding: 6px;
            border-radius: 8px;
            background: #f3f4f6;
            border: none;
        }

        .nav-tabs .nav-link.active {
            background: #2563eb;
            color: #fff;
        }

        /* =========================
    TABLE (BIAR GA MELEBAR)
    ========================= */
        .modal-body table {
            width: 100%;
        }

        .modal-body table th,
        .modal-body table td {
            display: block;
            width: 100%;
        }

        .modal-body table tr {
            display: block;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .modal-body table th {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .modal-body table td {
            font-size: 13px;
            font-weight: 500;
        }

        /* =========================
    DOKUMEN
    ========================= */
        #tab-dokumen .row {
            gap: 10px;
        }

        #tab-dokumen .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        #tab-dokumen img {
            max-height: 140px;
            border-radius: 8px;
        }

        /* =========================
    BUTTON
    ========================= */
        .modal-footer {
            padding: 10px 14px;
        }

        .modal-footer .btn {
            width: 100%;
            font-size: 12px;
            padding: 8px;
            border-radius: 8px;
        }
    }

    /* =========================
FIX KHUSUS MODAL SAJA 🔥 (ANTI SCROLL SAMPING)
========================= */
    #viewWargaModal .modal-content,
    #viewWargaModal .modal-body,
    #viewWargaModal .tab-content,
    #viewWargaModal .tab-pane {
        overflow-x: hidden;
    }

    /* table di dalam modal tidak ikut min-width global */
    #viewWargaModal .table {
        min-width: unset !important;
        width: 100%;
    }

    /* text panjang tidak dorong layout */
    #viewWargaModal td {
        word-break: break-word;
        white-space: normal !important;
    }

    /* kalau ada table-responsive di modal */
    #viewWargaModal .table-responsive {
        overflow-x: hidden !important;
    }

    .modern-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }

    .modern-input {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        font-size: 14px;
    }

    .modern-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
    }

    .modern-input-group {
        border-radius: 10px;
        overflow: hidden;
    }

    .modern-input-group .input-group-text {
        background: #f9fafb;
        cursor: pointer;
    }

    .modern-btn {
        border-radius: 12px;
        padding: 10px;
        font-weight: 600;
    }

    /* =========================
PREVIEW FOTO KK (RESPONSIVE)
========================= */
    .preview-wrapper {
        width: 100%;
        display: none;
        /* default hidden */
    }

    .preview-wrapper img {
        width: 100%;
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        object-fit: cover;
        aspect-ratio: 16 / 9;
        /* 🔥 biar ga kepotong */
    }

    /* MOBILE FIX */
    @media (max-width: 768px) {
        .preview-wrapper img {
            max-height: 250px;
        }
    }

    .preview {
        display: none;
    }

    .btn-remove-img {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ff3b3b;
        color: #fff;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .preview-wrapper {
        display: inline-block;
    }

    /* =========================================================
   SWEETALERT GLOBAL MINI SYSTEM STYLE
========================================================= */

    .swal-popup-mini {
        border-radius: 12px !important;
        padding: 14px !important;
        font-size: 13px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    }

    /* TITLE */
    .swal-title-mini {
        font-size: 15px !important;
        font-weight: 600 !important;
        color: #111827;
    }

    /* TEXT CONTENT */
    .swal2-html-container {
        font-size: 12.5px !important;
        margin-top: 6px !important;
        line-height: 1.4 !important;
        color: #374151;
    }

    /* BUTTONS */
    .swal2-confirm,
    .swal2-cancel {
        font-size: 12px !important;
        padding: 6px 14px !important;
        border-radius: 8px !important;
        font-weight: 500 !important;
    }

    /* LOADING */
    .swal2-loader {
        width: 2em !important;
        height: 2em !important;
    }

    /* ICON */
    .swal2-icon {
        transform: scale(0.9);
    }

    @media (max-width: 576px) {

        .swal-mobile-popup {
            border-radius: 12px !important;
        }

        .swal-mobile-title {
            font-size: 16px !important;
        }

        .swal2-radio {
            font-size: 14px !important;
            text-align: left;
        }

        .swal2-confirm,
        .swal2-cancel {
            font-size: 13px !important;
            padding: 8px 14px !important;
        }
    }

    .hover-option {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .hover-option:hover {
        background: #f8f9fa;
        transform: scale(1.02);
        border-color: #0d6efd;
    }

    .swal-title-sm {
        font-size: 16px !important;
        font-weight: 600;
    }

    @media (max-width: 576px) {
        .swal-title-sm {
            font-size: 14px !important;
        }
    }

    .is-invalid {
        border: 1px solid red !important;
    }
</style>
