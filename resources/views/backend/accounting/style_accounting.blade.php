<style>
    /* =====================================================
   BUTTON COMPACT
===================================================== */
    .coa-btn.compact {
        padding: 4px 8px;
        font-size: 12px;
        gap: 4px;
    }

    .coa-btn.compact i {
        font-size: 12px;
    }

    /* =====================================================
   TYPE BADGE
===================================================== */
    .coa-type-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
    }

    .coa-type-badge.type-asset {
        background: rgba(59, 130, 246, .12);
        color: #2563eb;
    }

    .coa-type-badge.type-liability {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
    }

    .coa-type-badge.type-equity {
        background: rgba(139, 92, 246, .12);
        color: #7c3aed;
    }

    .coa-type-badge.type-revenue {
        background: rgba(34, 197, 94, .12);
        color: #16a34a;
    }

    .coa-type-badge.type-expense {
        background: rgba(249, 115, 22, .12);
        color: #ea580c;
    }

    /* =====================================================
   BALANCE BADGE
===================================================== */
    .coa-balance-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
    }

    .coa-balance-badge.debit {
        background: rgba(59, 130, 246, .12);
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, .2);
    }

    .coa-balance-badge.credit {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, .2);
    }

    /* =====================================================
   PAGE
===================================================== */
    .coa-page {
        padding: 20px;
        background: #f4f6fb;
        font-size: 13px;
        color: #111827;
    }

    /* =====================================================
   HEADER (EYE CATCHING VERSION)
===================================================== */
    .coa-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        padding: 14px 16px;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        position: relative;
        overflow: hidden;
    }

    /* decorative glow line */
    .coa-header::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 4px;
        width: 100%;
        background: linear-gradient(90deg, #4f46e5, #6366f1, #22c55e, #f59e0b);
        background-size: 300% 100%;
        animation: headerGlow 6s linear infinite;
    }

    /* animated gradient */
    @keyframes headerGlow {
        0% {
            background-position: 0% 50%;
        }

        100% {
            background-position: 100% 50%;
        }
    }

    /* TITLE */
    .coa-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 0.3px;
        color: #111827;
    }

    /* SUB TEXT */
    .coa-header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #6b7280;
    }

    /* RIGHT ACTION AREA */
    .coa-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* OPTIONAL: badge kecil di header kalau mau */
    .coa-header .header-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
        font-weight: 600;
    }

    /* =====================================================
   ACTION BUTTONS
===================================================== */
    .coa-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    /* BASE BUTTON */
    .coa-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        transition: .25s;
    }

    /* PRIMARY */
    .coa-btn.primary {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: #FFFFFF;
    }

    /* LIGHT */
    .coa-btn.light {
        background: #fff;
        border: 1px solid #e5e7eb;
    }

    .coa-btn.warning {
        background: linear-gradient(135deg, #FFF069, #FFE600);
        color: #000000;
    }

    /* =====================================================
   STATS (EYE CATCHING DASHBOARD STYLE)
===================================================== */
    .coa-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 18px;
    }

    /* CARD BASE */
    .stat-card {
        background: linear-gradient(135deg, #ffffff, #f9fafb);
        border-radius: 18px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #e5e7eb;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    /* soft floating hover */
    .stat-card:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.08);
    }

    /* animated glow bottom line */
    .stat-card::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        transform: scaleX(0);
        transform-origin: left;
        transition: 0.35s ease;
    }

    .stat-card:hover::after {
        transform: scaleX(1);
    }

    /* ICON STYLE (BIG + SOFT BG) */
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        background: #f3f4f6;
        transition: 0.25s ease;
    }

    /* TEXT AREA */
    .stat-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #111827;
        letter-spacing: 0.3px;
    }

    .stat-desc {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 2px;
    }

    /* =====================================================
   COLOR THEMES (MAKING EACH CARD "POP")
===================================================== */

    /* TOTAL (BLUE TECH) */
    .stat-card.total::after {
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
    }

    .stat-card.total .stat-icon {
        background: rgba(59, 130, 246, 0.12);
        color: #2563eb;
    }

    /* HEADER (AMBER PREMIUM) */
    .stat-card.header::after {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    .stat-card.header .stat-icon {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
    }

    /* ACTIVE (GREEN SUCCESS) */
    .stat-card.active::after {
        background: linear-gradient(90deg, #22c55e, #4ade80);
    }

    .stat-card.active .stat-icon {
        background: rgba(34, 197, 94, 0.12);
        color: #16a34a;
    }

    /* INACTIVE (RED ALERT) */
    .stat-card.inactive::after {
        background: linear-gradient(90deg, #ef4444, #f87171);
    }

    .stat-card.inactive .stat-icon {
        background: rgba(239, 68, 68, 0.12);
        color: #dc2626;
    }

    /* =====================================================
   RESPONSIVE
===================================================== */
    @media (max-width: 1024px) {
        .coa-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .coa-stats {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 20px;
        }
    }

    /* =====================================================
   FILTER
===================================================== */
    .coa-filter {
        background: #fff;
        padding: 14px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        margin-bottom: 16px;
    }

    .coa-filter form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .coa-search-wrapper {
        flex: 1;
        position: relative;
    }

    .coa-search-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .coa-search-input {
        width: 100%;
        height: 44px;
        padding-left: 38px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .coa-select {
        height: 44px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        min-width: 130px;
    }

    /* =====================================================
   ERP ACCOUNTING TABLE STYLE
===================================================== */
    .coa-table-toolbar {
        padding: 10px 14px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-title {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
    }

    .table-count {
        font-size: 11px;
        color: #6b7280;
        margin-left: 10px;
    }

    .coa-table-card {
        background: #fff;
        border: 1px solid #d6dde6;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .03);
    }

    /* SCROLL */
    .coa-table-scroll {
        max-height: 78vh;
        overflow: auto;
    }

    /* TABLE */
    .coa-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 12px;
        color: #1f2937;
    }

    /* HEADER */
    .coa-table thead th {
        position: sticky;
        top: 0;
        z-index: 20;

        background: linear-gradient(to bottom, #f8fafc, #e9eef5);

        border-bottom: 1px solid #cfd8e3;
        border-right: 1px solid #dde5ee;

        padding: 7px 10px;

        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #374151;

        white-space: nowrap;
    }

    /* BODY */
    .coa-table tbody td {
        border-bottom: 1px solid #edf2f7;
        border-right: 1px solid #f1f5f9;

        padding: 5px 10px;

        vertical-align: middle;
        background: #fff;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* ZEBRA */
    .coa-table tbody tr:nth-child(even) td {
        background: #fafcff;
    }

    /* HOVER */
    .coa-table tbody tr:hover td {
        background: #edf6ff !important;
    }

    /* ROOT HEADER */
    .coa-row.level-0 td {
        background: #e8eef5 !important;
        font-weight: 700;
    }

    /* SUB HEADER */
    .coa-row.level-1 td {
        background: #f8fafc;
        font-weight: 600;
    }

    /* CODE */
    .coa-code-cell {
        font-family: Consolas, monospace;
        font-size: 12px;
        color: #111827;
    }

    /* TREE WRAP */
    .coa-code-wrap {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* TREE */
    .tree-lines {
        display: flex;
    }

    .tree-lines .v-line {
        width: 16px;
        border-left: 1px solid #cbd5e1;
        position: relative;
    }

    .tree-lines .v-line:last-child::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        width: 9px;
        border-top: 1px solid #cbd5e1;
    }

    /* TOGGLE */
    .coa-toggle-btn {
        width: 16px;
        height: 16px;

        border: 1px solid #cbd5e1;
        background: #fff;

        border-radius: 2px;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;
        padding: 0;
    }

    .coa-toggle-btn i {
        font-size: 9px;
    }

    /* LEAF */
    .coa-leaf-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #94a3b8;
    }

    /* NAME */
    .coa-name-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* BADGE */
    .coa-type-badge,
    .coa-balance-badge,
    .coa-status {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }

    /* ACTION */
    .coa-action-group {
        display: flex;
        justify-content: center;
        gap: 4px;
    }

    .coa-icon-btn {
        width: 24px;
        height: 24px;

        border: 1px solid #d1d5db;
        background: #fff;

        border-radius: 4px;

        cursor: pointer;
        transition: .2s;
    }

    .coa-icon-btn:hover {
        background: #f3f4f6;
    }

    .coa-icon-btn.danger:hover {
        background: #fee2e2;
    }

    /* MOBILE */
    @media (max-width: 768px) {

        .coa-table {
            min-width: 920px;
        }

        .coa-table thead th {
            font-size: 10px;
            padding: 6px;
        }

        .coa-table tbody td {
            font-size: 10px;
            padding: 5px 6px;
        }

    }

    /* =====================================================
   STATUS
===================================================== */
    .coa-status {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .coa-status.active {
        background: #dcfce7;
        color: #166534;
    }

    .coa-status.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    /* =====================================================
   ACTIONS
===================================================== */
    .coa-action-group {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
    }

    .coa-icon-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: transparent;
        border: none;
    }

    .coa-icon-btn:hover {
        background: #f3f4f6;
    }

    .coa-icon-btn.danger:hover {
        background: #fee2e2;
    }

    /* =====================================================
   MODAL (ONLY ONE CLEAN VERSION)
===================================================== */
    .coa-modal {
        position: fixed;
        inset: 0;
        display: none;
        z-index: 99999;
    }

    .coa-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coa-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .55);
        backdrop-filter: blur(4px);
    }

    .coa-modal-box {
        position: relative;
        width: 520px;
        max-width: 95%;
        background: #fff;
        border-radius: 16px;
        padding: 18px;

        display: flex;
        flex-direction: column;
        gap: 12px;

        animation: modalShow .18s ease forwards;
    }

    @keyframes modalShow {
        from {
            transform: scale(.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* FORM */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 12px;
        color: #6b7280;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .15);
    }

    /* ACTION */
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }

    /* MOBILE */
    @media (max-width:768px) {
        .coa-stats {
            grid-template-columns: 1fr;
        }

        .coa-filter form {
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* =====================================================
   MOBILE APP STYLE (ANDROID / IOS FEEL)
===================================================== */
    @media (max-width: 768px) {

        /* PAGE */
        .coa-page {
            padding: 10px;
            font-size: 11px;
            background: #f4f6fb;
        }

        /* =====================================================
       HEADER MOBILE
    ===================================================== */
        .coa-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 14px;
            border-radius: 16px;
        }

        .coa-header h2 {
            font-size: 16px;
            line-height: 1.3;
            font-weight: 700;
        }

        .coa-header p {
            font-size: 11px;
            line-height: 1.5;
            color: #6b7280;
        }

        /* ACTION BUTTON */
        .coa-actions {
            width: 100%;
            flex-direction: column;
            gap: 8px;
        }

        .coa-btn {
            width: 100%;
            justify-content: center;
            font-size: 11px;
            padding: 10px 12px;
            border-radius: 12px;
        }

        .coa-btn i {
            font-size: 12px;
        }

        /* =====================================================
       STATS MOBILE
    ===================================================== */
        .coa-stats {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .stat-card {
            padding: 12px;
            border-radius: 16px;
            gap: 12px;
            align-items: center;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 12px;
            font-size: 16px;
        }

        .stat-label {
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .stat-desc {
            font-size: 10px;
            line-height: 1.4;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* =====================================================
       FILTER MOBILE
    ===================================================== */
        .coa-filter {
            padding: 12px;
            border-radius: 14px;
        }

        .coa-filter form {
            flex-direction: column;
            gap: 8px;
        }

        .coa-search-wrapper,
        .coa-select,
        .coa-btn {
            width: 100%;
        }

        .coa-search-input,
        .coa-select {
            height: 42px;
            font-size: 11px;
            border-radius: 10px;
        }

        .coa-search-wrapper i {
            font-size: 12px;
        }

        /* =====================================================
       TABLE MOBILE
    ===================================================== */
        .coa-table-card {
            border-radius: 14px;
        }

        .coa-table-scroll {
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            min-width: 720px;
        }

        thead th {
            font-size: 10px;
            padding: 10px;
        }

        td {
            font-size: 10px;
            padding: 10px;
            white-space: nowrap;
        }

        /* TREE TEXT */
        .coa-code-text {
            font-size: 10px;
            font-weight: 600;
        }

        .coa-name-text {
            font-size: 10px;
        }

        /* TYPE BADGE */
        .coa-type-badge,
        .coa-balance-badge,
        .coa-status {
            font-size: 9px;
            padding: 3px 7px;
        }

        /* ACTION BUTTONS */
        .coa-action-group {
            gap: 4px;
        }

        .coa-icon-btn {
            width: 26px;
            height: 26px;
            border-radius: 8px;
        }

        .coa-icon-btn i {
            font-size: 11px;
        }

        /* =====================================================
       MODAL MOBILE
    ===================================================== */
        .coa-modal-box {
            width: calc(100% - 20px);
            max-width: 100%;
            border-radius: 18px;
            padding: 14px;
        }

        .coa-modal-header h3 {
            font-size: 15px;
        }

        .coa-modal-header small {
            font-size: 10px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .form-group.full {
            grid-column: span 1;
        }

        .form-group label {
            font-size: 10px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            font-size: 11px;
            padding: 10px;
            border-radius: 10px;
        }

        .modal-actions {
            flex-direction: column-reverse;
            gap: 8px;
        }

        .modal-actions .coa-btn {
            width: 100%;
        }


    }

    /* =====================================================
   ACCOUNT MODE
===================================================== */

    .coa-account-mode {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 10px;
    }

    .coa-radio-card {
        position: relative;
        border: 1px solid #e4e7ec;
        border-radius: 14px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all .25s ease;
        background: #fff;
    }

    .coa-radio-card:hover {
        border-color: #6366f1;
        background: #f8faff;
        transform: translateY(-1px);
    }

    .coa-radio-card input[type="radio"] {
        position: absolute;
        top: 18px;
        left: 16px;
        transform: scale(1.1);
    }

    .coa-radio-card .radio-content {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding-left: 28px;
    }

    .coa-radio-card .radio-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .coa-radio-card .radio-icon.header {
        background: #eef2ff;
        color: #4f46e5;
    }

    .coa-radio-card .radio-icon.postable {
        background: #ecfdf3;
        color: #16a34a;
    }

    .coa-radio-card .radio-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .coa-radio-card .radio-info strong {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .coa-radio-card .radio-info small {
        font-size: 12px;
        line-height: 1.5;
        color: #6b7280;
    }

    /* ACTIVE RADIO */
    .coa-radio-card:has(input:checked) {
        border-color: #4f46e5;
        background: #f5f7ff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .08);
    }

    /* FORM HINT */
    .form-hint {
        display: block;
        margin-top: 6px;
        font-size: 11px;
        color: #94a3b8;
        line-height: 1.5;
    }

    /* =========================================
   STATUS BUTTON
========================================= */

    .coa-icon-btn.success {
        background: rgba(34, 197, 94, .12);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, .25);
    }

    .coa-icon-btn.success:hover {
        background: #16a34a;
        color: #fff;
        transform: translateY(-2px);
    }

    .coa-icon-btn.danger {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, .25);
    }

    .coa-icon-btn.danger:hover {
        background: #dc2626;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-warning-soft {
        background: #f0e2a8;
        /* kuning soft */
        color: #1f2937;
        /* teks gelap */
        border: 1px solid #facc15;
        border-radius: 8px;
        width: 30px;
        height: 30px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(250, 204, 21, 0.25);
    }

    /* hover effect */
    .btn-warning-soft:hover {
        background: #eab308;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(250, 204, 21, 0.35);
    }

    /* icon */
    .btn-warning-soft i {
        font-size: 12px;
    }

    /* =========================================
   VIEW / INFO BUTTON (BLUE SOFT)
========================================= */

    .coa-icon-btn.view {
        background: rgba(59, 130, 246, .12);
        /* blue soft */
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, .25);
    }

    .coa-icon-btn.view:hover {
        background: #2563eb;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    }

    /* icon size konsisten */
    .coa-icon-btn.view i {
        font-size: 12px;
    }

    .coa-pagination {
        margin-top: 15px;
        display: flex;
        justify-content: flex-end;
    }
</style>
