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
        gap: 20px;

        margin-bottom: 24px;

        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 1px solid #e5e7eb;
        border-radius: 22px;

        padding: 22px;
        position: relative;
        overflow: hidden;
    }

    .coa-header::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;

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
        gap: 16px;
    }

    .coa-title-icon {
        width: 58px;
        height: 58px;

        border-radius: 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(135deg,
                rgba(79, 70, 229, .14),
                rgba(99, 102, 241, .08));

        color: #4f46e5;
        font-size: 24px;

        flex-shrink: 0;
    }

    .coa-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 800;
        color: #111827;
    }

    .coa-header p {
        margin: 6px 0 0;
        font-size: 12px;
        line-height: 1.6;
        color: #6b7280;
    }

    /* =====================================================
   CARD
===================================================== */

    .coa-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;

        overflow: hidden;

        transition: .25s ease;
    }

    .coa-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 14px 30px rgba(15, 23, 42, .06);
    }

    .coa-card-header {
        padding: 16px 18px;

        background: linear-gradient(to bottom,
                #f8fafc,
                #eef2f7);

        border-bottom: 1px solid #e5e7eb;
    }

    .coa-card-header h5 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .coa-card-header small {
        color: #6b7280;
        font-size: 11px;
    }

    .coa-mini-icon {
        width: 40px;
        height: 40px;

        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: rgba(79, 70, 229, .12);
        color: #4f46e5;

        font-size: 16px;
    }

    /* =====================================================
   TABLE
===================================================== */

    .coa-table-scroll {
        overflow: auto;
        max-height: 700px;
    }

    .coa-table-scroll::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .coa-table-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .table {
        margin: 0;
        font-size: 12px;
    }

    .table thead th {
        position: sticky;
        top: 0;
        z-index: 5;

        background: #f8fafc;

        padding: 13px 14px;

        border-bottom: 1px solid #e5e7eb;
        border-right: 1px solid #eef2f7;

        font-size: 11px;
        font-weight: 700;
        color: #374151;

        white-space: nowrap;
    }

    .table tbody td {
        padding: 14px;
        vertical-align: middle;

        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f8fafc;

        color: #1f2937;
    }

    .table tbody tr:nth-child(even) td {
        background: #fbfcff;
    }

    .table tbody tr:hover td {
        background: #eef6ff !important;
    }

    /* =====================================================
   BADGE
===================================================== */

    .badge {
        font-size: 10px;
        font-weight: 700;

        padding: 6px 10px;

        border-radius: 999px;
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
        background: rgba(245, 158, 11, .12) !important;
        color: #b45309 !important;
    }

    .bg-danger {
        background: rgba(239, 68, 68, .12) !important;
        color: #dc2626 !important;
    }

    .bg-dark {
        background: rgba(15, 23, 42, .08) !important;
        color: #111827 !important;
    }

    /* =====================================================
   ACTION BUTTON
===================================================== */

    .coa-action-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .coa-icon-btn {
        width: 34px;
        height: 34px;

        border: none;
        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        transition: .2s ease;

        cursor: pointer;
    }

    .coa-icon-btn i {
        font-size: 13px;
    }

    .coa-icon-btn.warning {
        background: rgba(245, 158, 11, .12);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, .18);
    }

    .coa-icon-btn.warning:hover {
        background: #f59e0b;
        color: #fff;

        transform: translateY(-2px);

        box-shadow:
            0 10px 20px rgba(245, 158, 11, .24);
    }

    .coa-icon-btn.danger {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, .18);
    }

    .coa-icon-btn.danger:hover {
        background: #ef4444;
        color: #fff;

        transform: translateY(-2px);

        box-shadow:
            0 10px 20px rgba(239, 68, 68, .24);
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

        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    /* =====================================================
   MODAL BOX
===================================================== */

    .coa-modal-box {
        position: relative;
        z-index: 2;

        width: min(1400px, 95%);
        max-height: 92vh;

        background: #fff;

        border-radius: 26px;

        border: 1px solid #eef2f7;

        overflow: hidden;

        display: flex;
        flex-direction: column;

        box-shadow:
            0 25px 60px rgba(0, 0, 0, .18),
            0 10px 25px rgba(0, 0, 0, .08);

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

        padding: 22px 24px;

        border-bottom: 1px solid #eef2f7;

        background: #fff;
    }

    .coa-modal-title {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .coa-modal-icon {
        width: 50px;
        height: 50px;

        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(135deg,
                rgba(79, 70, 229, .14),
                rgba(99, 102, 241, .18));

        color: #4f46e5;
        font-size: 20px;

        flex-shrink: 0;
    }

    .coa-modal-title h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    .coa-modal-title p {
        margin: 4px 0 0;

        font-size: 12px;
        line-height: 1.5;

        color: #6b7280;
    }

    .coa-modal-close {
        width: 38px;
        height: 38px;

        border: none;
        border-radius: 12px;

        background: #f3f4f6;

        display: flex;
        align-items: center;
        justify-content: center;

        transition: .2s ease;

        cursor: pointer;
    }

    .coa-modal-close:hover {
        background: #fee2e2;
        color: #dc2626;

        transform: rotate(90deg);
    }

    /* =====================================================
   MODAL BODY
===================================================== */

    .coa-modal-body {
        flex: 1;

        overflow-y: auto;

        padding: 24px;

        background: #f5f7fb;

        scroll-behavior: smooth;
    }

    /* =====================================================
   FORM
===================================================== */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 20px;
    }

    .form-group {
        grid-column: span 6;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;

        margin-bottom: 8px;

        font-size: 12px;
        font-weight: 700;

        color: #374151;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 48px;

        border-radius: 14px !important;
        border: 1px solid #dbe2ea !important;

        font-size: 13px;

        background: #fff;

        transition: .2s ease;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
        padding-top: 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1 !important;

        box-shadow:
            0 0 0 4px rgba(99, 102, 241, .12) !important;
    }

    .form-hint {
        display: block;

        margin-top: 6px;

        font-size: 11px;
        line-height: 1.5;

        color: #94a3b8;
    }

    /* =====================================================
   MAPPING SECTION
===================================================== */

    .mapping-section {
        background: #fff;

        border: 1px solid #edf1f7;
        border-radius: 20px;

        padding: 24px;

        margin-bottom: 24px;
    }

    .mapping-header {
        display: flex;
        align-items: center;
        gap: 14px;

        margin-bottom: 20px;
    }

    .mapping-icon {
        width: 50px;
        height: 50px;

        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 20px;

        flex-shrink: 0;
    }

    .mapping-header.success .mapping-icon {
        background: #dcfce7;
        color: #15803d;
    }

    .mapping-header.primary .mapping-icon {
        background: #dbeafe;
        color: #2563eb;
    }

    .mapping-header.warning .mapping-icon {
        background: #fef3c7;
        color: #d97706;
    }

    .mapping-header.info .mapping-icon {
        background: #cffafe;
        color: #0891b2;
    }

    .mapping-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .mapping-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 12px;
    }

    /* =====================================================
   COA GRID
===================================================== */

    .coa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 14px;

        max-height: 340px;
        overflow-y: auto;

        padding: 4px;
    }

    .coa-grid::-webkit-scrollbar {
        width: 7px;
    }

    .coa-grid::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    /* =====================================================
   COA ITEM
===================================================== */

    .coa-item {
        position: relative;

        display: flex;
        align-items: flex-start;
        gap: 12px;

        padding: 14px;

        border: 1px solid #e5e7eb;
        border-radius: 16px;

        background: #fff;

        cursor: pointer;

        transition: .2s ease;
    }

    .coa-item:hover {
        border-color: #2563eb;
        background: #f8fbff;

        transform: translateY(-1px);
    }

    .coa-item input {
        margin-top: 4px;
        transform: scale(1.08);
    }

    .coa-item span {
        display: block;

        font-size: 13px;
        line-height: 1.5;

        color: #111827;
    }

    .coa-item small {
        display: block;

        margin-top: 4px;

        color: #6b7280;
        font-size: 11px;
    }

    .coa-item input:checked+span {
        color: #1d4ed8;
        font-weight: 700;
    }

    /* =====================================================
   DEFAULT ACCOUNT SECTION
===================================================== */

    .default-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    /* =====================================================
   AUTO STATUS
===================================================== */

    .coa-auto-status {
        height: 48px;

        display: flex;
        align-items: center;
        gap: 10px;

        padding: 0 16px;

        border-radius: 14px;

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
   MODAL ACTION
===================================================== */

    .modal-actions {
        position: sticky;
        bottom: 0;

        display: flex;
        justify-content: flex-end;
        gap: 12px;

        padding: 18px 24px;

        border-top: 1px solid #eef2f7;

        background: #fff;

        z-index: 10;
    }

    .coa-action-btn {
        height: 48px;

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

        transition: .25s ease;
    }

    .coa-action-btn i {
        font-size: 14px;
    }

    .cancel-btn {
        background: #fff;
        color: #374151;

        border: 1px solid #dbe2ea;
    }

    .cancel-btn:hover {
        background: #f9fafb;

        transform: translateY(-2px);

        box-shadow:
            0 10px 20px rgba(15, 23, 42, .06);
    }

    .submit-btn {
        color: #fff;

        background: linear-gradient(135deg,
                #4f46e5,
                #6366f1);

        box-shadow:
            0 10px 20px rgba(79, 70, 229, .22);
    }

    .submit-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 16px 28px rgba(79, 70, 229, .30);
    }

    .coa-action-btn:active {
        transform: scale(.98);
    }

    /* =====================================================
   MOBILE
===================================================== */

    @media (max-width:992px) {

        .default-grid {
            grid-template-columns: 1fr;
        }

        .form-group {
            grid-column: 1 / -1;
        }
    }

    @media (max-width:768px) {

        .coa-page {
            padding: 12px;
        }

        .coa-header {
            flex-direction: column;
            align-items: flex-start;

            padding: 18px;
            border-radius: 18px;
        }

        .coa-title-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .coa-header h2 {
            font-size: 20px;
        }

        .coa-card {
            border-radius: 18px;
        }

        .coa-modal {
            padding: 10px;
        }

        .coa-modal-box {
            width: 100%;
            max-height: 96vh;
            border-radius: 22px;
        }

        .coa-modal-header {
            padding: 18px;
        }

        .coa-modal-body {
            padding: 18px;
        }

        .mapping-section {
            padding: 18px;
        }

        .coa-grid {
            grid-template-columns: 1fr;
            max-height: none;
        }

        .table {
            min-width: 760px;
        }

        .modal-actions {
            flex-direction: column-reverse;
        }

        .coa-action-btn {
            width: 100%;
        }
    }

    /* =====================================================
   FUND TYPE MODAL ONLY
===================================================== */

    .fund-type-modal-box {
        position: relative;
        z-index: 2;

        width: 100%;
        max-width: 760px;

        background: #fff;

        border-radius: 24px;

        overflow: hidden;

        box-shadow:
            0 20px 60px rgba(15, 23, 42, .18),
            0 8px 24px rgba(15, 23, 42, .08);

        animation: fundModalShow .25s ease;
    }

    /* =====================================================
   BODY
===================================================== */

    .fund-type-modal-body {
        padding: 28px;
    }

    /* =====================================================
   GRID
===================================================== */

    .fund-type-modal .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
    }

    /*
|--------------------------------------------------------------------------
| FULL WIDTH
|--------------------------------------------------------------------------
*/

    .fund-type-modal .form-group.full {
        grid-column: 1 / -1;
    }

    /* =====================================================
   FORM GROUP
===================================================== */

    .fund-type-modal .form-group {
        display: flex;
        flex-direction: column;
    }

    /* =====================================================
   LABEL
===================================================== */

    .fund-type-modal .form-group label {
        font-size: 14px;
        font-weight: 700;

        color: #0f172a;

        margin-bottom: 10px;
    }

    /* =====================================================
   INPUT
===================================================== */

    .fund-type-modal .form-control {
        width: 100%;

        min-height: 48px;

        border-radius: 14px;
        border: 1px solid #dbe2ea;

        background: #fff;

        padding: 12px 16px;

        font-size: 14px;

        transition: .2s ease;
    }

    .fund-type-modal textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }

    /* =====================================================
   HINT
===================================================== */

    .fund-type-modal .form-hint {
        margin-top: 8px;

        font-size: 12px;
        line-height: 1.6;

        color: #64748b;
    }

    /* =====================================================
   STATUS BOX
===================================================== */

    .fund-type-modal .coa-auto-status {
        min-height: 48px;

        display: flex;
        align-items: center;
        gap: 10px;

        padding: 0 16px;

        border-radius: 14px;

        background: #ecfdf3;
        border: 1px solid #bbf7d0;

        color: #15803d;

        font-weight: 600;
    }

    /* =====================================================
   MOBILE
===================================================== */

    @media (max-width: 768px) {

        .fund-type-modal {
            padding: 16px;
        }

        .fund-type-modal-box {
            max-width: 100%;
            border-radius: 20px;
        }

        .fund-type-modal .form-grid {
            grid-template-columns: 1fr;
        }

        .fund-type-modal .form-group.full {
            grid-column: span 1;
        }

    }
</style>
