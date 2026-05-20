<style>
    /* =====================================================
       FUNDING ACCOUNTING THEME
    ===================================================== */

    .coa-page {
        padding: 20px;
        background: #f4f6fb;
        min-height: 100vh;
        font-size: 13px;
        color: #111827;
    }

    /* =====================================================
       HEADER
    ===================================================== */

    .coa-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;

        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 1px solid #e5e7eb;
        border-radius: 18px;

        padding: 18px 20px;

        position: relative;
        overflow: hidden;
    }

    .coa-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;

        width: 100%;
        height: 4px;

        background: linear-gradient(90deg,
                #4f46e5,
                #6366f1,
                #06b6d4,
                #22c55e);

        background-size: 300% 100%;
        animation: coaGlow 6s linear infinite;
    }

    @keyframes coaGlow {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 100% 50%;
        }
    }

    .coa-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .coa-title-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(135deg,
                rgba(79, 70, 229, .12),
                rgba(99, 102, 241, .08));

        color: #4f46e5;
        font-size: 24px;
    }

    .coa-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #111827;
    }

    .coa-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12px;
        line-height: 1.6;
    }

    /* =====================================================
       CARD
    ===================================================== */

    .coa-card {
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        transition: all .25s ease;
    }

    .coa-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
    }

    /* =====================================================
       CARD HEADER
    ===================================================== */

    .coa-card-header {
        background: linear-gradient(to bottom,
                #f8fafc,
                #eef2f7);

        border-bottom: 1px solid #dbe3ec;
        padding: 14px 16px;
    }

    .coa-card-header h5 {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .coa-card-header small {
        font-size: 11px;
        color: #6b7280;
    }

    .coa-mini-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(79, 70, 229, .10);
        color: #4f46e5;
        font-size: 16px;
    }

    /* =====================================================
       PRIMARY BUTTON
    ===================================================== */

    .btn-primary {
        border: none;
        border-radius: 10px;

        background: linear-gradient(135deg,
                #4f46e5,
                #6366f1);

        box-shadow: 0 4px 10px rgba(79, 70, 229, .18);
        transition: all .2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 20px rgba(79, 70, 229, .22);
    }

    /* =====================================================
       TABLE
    ===================================================== */

    .table {
        margin: 0;
        font-size: 12px;
    }

    .table thead th {
        background: linear-gradient(to bottom,
                #f8fafc,
                #edf2f7);

        border-bottom: 1px solid #dbe3ec;
        border-right: 1px solid #edf2f7;

        padding: 12px 14px;

        font-size: 11px;
        font-weight: 700;
        color: #374151;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 13px 14px;
        vertical-align: middle;

        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f8fafc;

        color: #1f2937;
    }

    .table tbody tr:nth-child(even) td {
        background: #fafcff;
    }

    .table tbody tr:hover td {
        background: #edf6ff !important;
    }

    /* =====================================================
       BADGES
    ===================================================== */

    .badge {
        font-size: 10px;
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 999px;
        letter-spacing: .3px;
    }

    .bg-dark {
        background: rgba(15, 23, 42, .08) !important;
        color: #0f172a !important;
    }

    .bg-success {
        background: rgba(34, 197, 94, .12) !important;
        color: #15803d !important;
    }

    .bg-primary {
        background: rgba(79, 70, 229, .12) !important;
        color: #4338ca !important;
    }

    .bg-info {
        background: rgba(6, 182, 212, .12) !important;
        color: #0891b2 !important;
    }

    .bg-warning {
        background: rgba(245, 158, 11, .14) !important;
        color: #b45309 !important;
    }

    /* =====================================================
       TEXT
    ===================================================== */

    .fw-semibold {
        font-weight: 700 !important;
        color: #111827;
        margin-bottom: 2px;
    }

    .text-muted {
        color: #6b7280 !important;
        font-size: 11px;
        line-height: 1.5;
    }

    strong {
        color: #111827;
        font-weight: 700;
        font-family: Consolas, monospace;
    }

    /* =====================================================
       SCROLLBAR
    ===================================================== */

    .table-responsive::-webkit-scrollbar,
    .coa-table-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb,
    .coa-table-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .table-responsive::-webkit-scrollbar-track,
    .coa-table-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    /* =====================================================
       ACTION BUTTON TABLE
    ===================================================== */

    .coa-action-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .coa-icon-btn {
        width: 30px;
        height: 30px;

        border: none;
        border-radius: 10px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        transition: all .2s ease;
        cursor: pointer;
    }

    .coa-icon-btn i {
        font-size: 12px;
    }

    .coa-icon-btn.warning {
        background: rgba(245, 158, 11, .12);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, .2);
    }

    .coa-icon-btn.warning:hover {
        background: #f59e0b;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(245, 158, 11, .25);
    }

    .coa-icon-btn.danger {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, .2);
    }

    .coa-icon-btn.danger:hover {
        background: #ef4444;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(239, 68, 68, .25);
    }

    /* =====================================================
       TABLE SCROLL
    ===================================================== */

    .coa-table-scroll {
        max-height: 650px;
        overflow-y: auto;
        overflow-x: auto;
    }

    .coa-table-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    /* =====================================================
       MODAL ROOT
    ===================================================== */

    .coa-modal {
        position: fixed;
        inset: 0;

        display: none;

        align-items: center;
        justify-content: center;

        padding: 20px;

        z-index: 999999;
    }

    .coa-modal.active {
        display: flex;
    }

    .coa-modal-overlay {
        position: absolute;
        inset: 0;

        background: rgba(15, 23, 42, .55);

        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    /* =====================================================
       MODAL BOX
    ===================================================== */

    .coa-modal-box {
        position: relative;
        z-index: 2;

        width: 560px;
        max-width: 95%;

        background: #fff;

        border-radius: 22px;

        padding: 22px;

        border: 1px solid #eef2f7;

        box-shadow:
            0 25px 50px rgba(0, 0, 0, .15),
            0 10px 20px rgba(0, 0, 0, .08);

        animation: coaModalShow .22s ease;
    }

    @keyframes coaModalShow {
        from {
            opacity: 0;
            transform: translateY(10px) scale(.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* =====================================================
       MODAL HEADER
    ===================================================== */

    .coa-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;

        margin-bottom: 18px;
        padding-bottom: 14px;

        border-bottom: 1px solid #eef2f7;
    }

    .coa-modal-title {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .coa-modal-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(135deg,
                rgba(79, 70, 229, .12),
                rgba(99, 102, 241, .18));

        color: #4f46e5;
        font-size: 18px;

        flex-shrink: 0;
    }

    .coa-modal-title h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .coa-modal-title p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
    }

    .coa-modal-close {
        width: 36px;
        height: 36px;

        border: none;
        background: #f3f4f6;

        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;
        transition: .2s ease;
    }

    .coa-modal-close:hover {
        background: #fee2e2;
        color: #dc2626;
        transform: rotate(90deg);
    }

    /* =====================================================
       FORM
    ===================================================== */

    .form-control,
    .form-select {
        height: 46px;
        border-radius: 12px !important;
        border: 1px solid #dbe2ea !important;
        font-size: 13px;
        transition: .2s ease;
    }

    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .12) !important;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-hint {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 5px;
    }

    /* =====================================================
       AUTO STATUS
    ===================================================== */

    .coa-auto-status {
        height: 46px;

        display: flex;
        align-items: center;
        gap: 10px;

        padding: 0 14px;

        border-radius: 12px;

        background: rgba(34, 197, 94, .10);
        border: 1px solid rgba(34, 197, 94, .18);

        color: #16a34a;
        font-size: 13px;
        font-weight: 700;
    }

    .coa-auto-status i {
        font-size: 15px;
    }

    /* =====================================================
       MODAL ACTION BUTTON
    ===================================================== */

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;

        margin-top: 24px;
        padding-top: 18px;

        border-top: 1px solid #eef2f7;
    }

    .coa-action-btn {
        position: relative;

        height: 46px;
        padding: 0 18px;

        border: none;
        border-radius: 14px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;
        overflow: hidden;

        transition: all .25s ease;
    }

    .coa-action-btn .btn-icon {
        width: 26px;
        height: 26px;

        border-radius: 8px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 12px;

        transition: .25s ease;
    }

    /* CANCEL BUTTON */

    .cancel-btn {
        background: #fff;
        color: #374151;

        border: 1px solid #dbe2ea;

        box-shadow: 0 2px 6px rgba(15, 23, 42, .04);
    }

    .cancel-btn .btn-icon {
        background: #f3f4f6;
        color: #6b7280;
    }

    .cancel-btn:hover {
        background: #f9fafb;
        transform: translateY(-2px);

        box-shadow: 0 10px 20px rgba(15, 23, 42, .08);
    }

    .cancel-btn:hover .btn-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    /* SUBMIT BUTTON */

    .submit-btn {
        background: linear-gradient(135deg,
                #4f46e5,
                #6366f1);

        color: #fff;

        box-shadow:
            0 10px 20px rgba(79, 70, 229, .22),
            0 2px 6px rgba(79, 70, 229, .18);
    }

    .submit-btn .btn-icon {
        background: rgba(255, 255, 255, .16);
        color: #fff;
    }

    .submit-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 16px 28px rgba(79, 70, 229, .30),
            0 4px 10px rgba(79, 70, 229, .20);
    }

    .submit-btn:hover .btn-icon {
        transform: scale(1.08);
        background: rgba(255, 255, 255, .24);
    }

    .coa-action-btn:active {
        transform: scale(.98);
    }

    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 768px) {

        .coa-page {
            padding: 12px;
        }

        .coa-header {
            padding: 16px;
            border-radius: 16px;
        }

        .coa-title-wrap {
            align-items: flex-start;
        }

        .coa-title-icon {
            width: 46px;
            height: 46px;
            font-size: 20px;
            border-radius: 14px;
        }

        .coa-header h2 {
            font-size: 18px;
        }

        .coa-header p {
            font-size: 11px;
        }

        .coa-card {
            border-radius: 16px;
        }

        .coa-card-header {
            padding: 12px 14px;
        }

        .coa-mini-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            font-size: 14px;
        }

        .table {
            min-width: 720px;
        }

        .table thead th {
            font-size: 10px;
            padding: 10px;
        }

        .table tbody td {
            font-size: 10px;
            padding: 10px;
            white-space: nowrap;
        }

        .badge {
            font-size: 9px;
            padding: 5px 8px;
        }

        .modal-actions {
            flex-direction: column-reverse;
        }

        .coa-action-btn {
            width: 100%;
        }
    }
</style>
