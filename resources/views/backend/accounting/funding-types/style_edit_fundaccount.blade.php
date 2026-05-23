<style>
    /* =====================================================
       PAGE LAYOUT
    ===================================================== */
    .coa-page {
        padding: 24px;
        background: #f4f7fb;
        min-height: 100vh;
    }

    /* =====================================================
       HEADER
    ===================================================== */
    .coa-page-header {
        position: relative;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        margin-bottom: 24px;
        padding: 24px;

        border-radius: 24px;
        border: 1px solid #e7edf5;

        background:
            linear-gradient(135deg,
                #ffffff 0%,
                #f8fbff 100%);

        overflow: hidden;
    }

    .coa-page-header::before {
        content: "";

        position: absolute;
        inset: 0 0 auto 0;

        height: 4px;

        background:
            linear-gradient(90deg,
                #4f46e5,
                #6366f1,
                #06b6d4,
                #22c55e);

        background-size: 300% 100%;

        animation: coaHeaderGlow 6s linear infinite;
    }

    @keyframes coaHeaderGlow {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 100% 50%;
        }
    }

    .coa-page-title {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .coa-page-icon {
        width: 62px;
        height: 62px;

        border-radius: 20px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        font-size: 24px;

        color: #4f46e5;

        background:
            linear-gradient(135deg,
                rgba(79, 70, 229, .15),
                rgba(99, 102, 241, .08));
    }

    .coa-page-title h4 {
        margin: 0;

        font-size: 24px;
        font-weight: 800;

        color: #0f172a;
    }

    .coa-page-title p {
        margin: 6px 0 0;

        font-size: 13px;
        line-height: 1.7;

        color: #64748b;
    }

    /* =====================================================
       CARD
    ===================================================== */
    .coa-card {
        border: 1px solid #e7edf5 !important;
        border-radius: 24px !important;

        background: #fff;

        overflow: hidden;

        box-shadow:
            0 12px 30px rgba(15, 23, 42, .04);

        transition: .25s ease;
    }

    .coa-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 18px 38px rgba(15, 23, 42, .08);
    }

    .coa-card .card-body {
        padding: 28px;
    }

    /* =====================================================
       SCROLL WRAPPER
    ===================================================== */
    .coa-scroll-wrapper {
        overflow-x: auto;
        overflow-y: auto;

        max-height: calc(100vh - 220px);

        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .coa-scroll-wrapper::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .coa-scroll-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .coa-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    /* =====================================================
       SECTION
    ===================================================== */
    .mapping-section {
        margin-bottom: 24px;
        padding: 24px;

        border-radius: 22px;
        border: 1px solid #edf2f7;

        background: #fff;

        transition: .2s ease;
    }

    .mapping-section:last-child {
        margin-bottom: 0;
    }

    .mapping-section:hover {
        border-color: #dbe6f3;

        box-shadow:
            0 8px 20px rgba(15, 23, 42, .03);
    }

    /* =====================================================
       SECTION HEADER
    ===================================================== */
    .mapping-header {
        display: flex;
        align-items: center;
        gap: 16px;

        margin-bottom: 22px;
    }

    .mapping-icon {
        width: 54px;
        height: 54px;

        border-radius: 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        font-size: 20px;
    }

    .mapping-icon.asset {
        background: rgba(34, 197, 94, .12);
        color: #16a34a;
    }

    .mapping-icon.liability {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
    }

    .mapping-icon.revenue {
        background: rgba(6, 182, 212, .12);
        color: #0891b2;
    }

    .mapping-icon.expense {
        background: rgba(245, 158, 11, .14);
        color: #d97706;
    }

    .mapping-header h6 {
        margin: 0;

        font-size: 18px;
        font-weight: 800;

        color: #111827;
    }

    .mapping-header p {
        margin: 5px 0 0;

        font-size: 12px;
        line-height: 1.6;

        color: #64748b;
    }

    /* =====================================================
       FORM
    ===================================================== */
    .form-label {
        margin-bottom: 10px;

        font-size: 13px;
        font-weight: 700;

        color: #374151;
    }

    .form-control,
    .form-select {
        height: 50px;

        border-radius: 16px !important;
        border: 1px solid #dbe4ee !important;

        background: #fff;

        font-size: 13px;

        transition: .2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1 !important;

        box-shadow:
            0 0 0 4px rgba(99, 102, 241, .12) !important;
    }

    /* =====================================================
       GRID
    ===================================================== */
    .coa-grid {
        display: grid;

        grid-template-columns:
            repeat(auto-fill, minmax(280px, 1fr));

        gap: 14px;

        max-height: 360px;

        overflow-y: auto;

        padding-right: 4px;
    }

    .coa-grid::-webkit-scrollbar {
        width: 7px;
    }

    .coa-grid::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    /* =====================================================
       ITEM
    ===================================================== */
    .coa-item {
        position: relative;

        display: flex;
        align-items: flex-start;
        gap: 14px;

        padding: 16px;

        border-radius: 18px;
        border: 1px solid #e5e7eb;

        background: #fff;

        cursor: pointer;

        transition: .2s ease;
    }

    .coa-item:hover {
        transform: translateY(-1px);

        border-color: #6366f1;

        background: #f8fbff;
    }

    .coa-item input[type="checkbox"] {
        width: 18px;
        height: 18px;

        margin-top: 4px;

        cursor: pointer;

        flex-shrink: 0;
    }

    .coa-item-content {
        flex: 1;
        min-width: 0;
    }

    .coa-item-title {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.5;

        color: #111827;

        word-break: break-word;
    }

    .coa-item-subtitle {
        margin-top: 5px;

        font-size: 11px;
        line-height: 1.6;

        color: #64748b;

        word-break: break-word;
    }

    .coa-item input:checked~.coa-item-content .coa-item-title {
        color: #4338ca;
    }

    /* =====================================================
       DEFAULT BOX
    ===================================================== */
    .default-box {
        margin-top: 22px;
        padding: 20px;

        border-radius: 18px;
        border: 1px dashed #d7e2f0;

        background:
            linear-gradient(to bottom,
                #fafcff,
                #f8fbff);
    }

    .default-box small {
        display: block;

        margin-top: 10px;

        font-size: 11px;
        line-height: 1.6;

        color: #64748b;
    }

    /* =====================================================
       EMPTY STATE
    ===================================================== */
    .coa-empty {
        padding: 18px;

        border-radius: 16px;
        border: 1px dashed #dbe2ea;

        background: #f8fafc;

        text-align: center;

        font-size: 13px;

        color: #64748b;
    }

    /* =====================================================
       BUTTONS
    ===================================================== */
    .btn {
        height: 48px;

        border-radius: 14px !important;

        padding: 0 18px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;

        font-size: 13px;
        font-weight: 700;

        transition: .2s ease;
    }

    .btn-light {
        background: #fff !important;

        border: 1px solid #dbe2ea !important;

        color: #374151 !important;
    }

    .btn-light:hover {
        background: #f8fafc !important;

        transform: translateY(-2px);

        box-shadow:
            0 10px 20px rgba(15, 23, 42, .05);
    }

    .btn-primary {
        border: none !important;

        background:
            linear-gradient(135deg,
                #4f46e5,
                #6366f1) !important;

        box-shadow:
            0 12px 24px rgba(79, 70, 229, .24);
    }

    .btn-primary:hover {
        transform: translateY(-2px);

        box-shadow:
            0 18px 30px rgba(79, 70, 229, .30);
    }

    /* =====================================================
       FOOTER
    ===================================================== */
    .coa-footer-sticky {
        position: sticky;
        bottom: 0;
        z-index: 20;

        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;

        padding: 18px 24px;

        background: #fff;

        border-top: 1px solid #eef2f7;
    }

    /* =====================================================
       TABLET
    ===================================================== */
    @media (max-width: 992px) {

        .coa-grid {
            grid-template-columns:
                repeat(auto-fill, minmax(240px, 1fr));
        }

    }

    /* =====================================================
       MOBILE
    ===================================================== */
    @media (max-width: 768px) {

        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        .coa-page {
            padding: 10px;
        }

        .coa-page-header {
            flex-direction: column;
            align-items: flex-start;

            padding: 18px;

            border-radius: 18px;
        }

        .coa-page-title {
            align-items: flex-start;
        }

        .coa-page-icon {
            width: 54px;
            height: 54px;

            border-radius: 16px;

            font-size: 20px;
        }

        .coa-page-title h4 {
            font-size: 20px;
        }

        .coa-page-title p {
            font-size: 12px;
        }

        .coa-card {
            border-radius: 18px !important;
        }

        .coa-card .card-body {
            padding: 16px;
        }

        .mapping-section {
            padding: 16px;

            border-radius: 18px;
        }

        .mapping-header {
            align-items: flex-start;
        }

        .mapping-header h6 {
            font-size: 16px;
        }

        .mapping-header p {
            font-size: 11px;
        }

        .mapping-icon {
            width: 46px;
            height: 46px;

            font-size: 16px;
        }

        .coa-grid {
            grid-template-columns: 1fr;

            gap: 12px;

            max-height: none;
        }

        .coa-item {
            padding: 14px;

            border-radius: 16px;
        }

        .form-control,
        .form-select {
            height: 48px;

            font-size: 14px;
        }

        .coa-scroll-wrapper {
            max-height: none;
        }

        .coa-footer-sticky {
            flex-wrap: wrap;
            justify-content: flex-end;

            padding: 16px;
        }

        .coa-footer-sticky .btn {
            flex: 1;
            min-width: 140px;
        }

    }

    /* =====================================================
       EXTRA SMALL DEVICES
    ===================================================== */
    @media (max-width: 480px) {

        .coa-page-title {
            gap: 14px;
        }

        .coa-page-icon {
            width: 50px;
            height: 50px;

            font-size: 18px;
        }

        .coa-page-title h4 {
            font-size: 18px;
        }

        .coa-page-title p {
            font-size: 11px;
        }

        .mapping-icon {
            width: 42px;
            height: 42px;

            font-size: 15px;
        }

        .btn {
            width: 100%;
        }

        .coa-footer-sticky {
            flex-direction: column-reverse;
            align-items: stretch;
        }

    }

</style>
