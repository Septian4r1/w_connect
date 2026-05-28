<style>
    /* =========================================================
   ROOT
========================================================= */
    :root {
        --coa-primary: #4f46e5;
        --coa-primary-light: #6366f1;

        --coa-success: #16a34a;
        --coa-danger: #dc2626;
        --coa-warning: #d97706;
        --coa-info: #2563eb;

        --coa-bg: #f4f6fb;
        --coa-card: #ffffff;

        --coa-text: #111827;
        --coa-text-soft: #6b7280;

        --coa-border: #e5e7eb;
        --coa-border-dark: #cfd8e3;

        --coa-radius: 14px;

        --shadow-sm: 0 1px 2px rgba(0, 0, 0, .04);
        --shadow-md: 0 10px 25px rgba(0, 0, 0, .06);
    }

    /* =========================================================
   RESET
========================================================= */
    * {
        box-sizing: border-box;
    }

    .coa-page * {
        scrollbar-width: thin;
    }

    /* =========================================================
   PAGE
========================================================= */
    .coa-page {
        padding: 20px;
        background: var(--coa-bg);
        font-size: 13px;
        color: var(--coa-text);
    }

    /* =========================================================
   HEADER
========================================================= */
    .coa-header {
        position: relative;

        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;

        margin-bottom: 18px;
        padding: 16px 18px;

        background: linear-gradient(135deg, #ffffff, #f8fafc);

        border: 1px solid var(--coa-border);
        border-radius: 18px;

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
                #22c55e,
                #f59e0b);

        background-size: 300% 100%;
        animation: coaGlow 6s linear infinite;
    }

    @keyframes coaGlow {
        from {
            background-position: 0 50%;
        }

        to {
            background-position: 100% 50%;
        }
    }

    .coa-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--coa-text);
    }

    .coa-header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: var(--coa-text-soft);
    }

    .header-badge {
        display: inline-flex;
        align-items: center;

        padding: 5px 10px;

        border-radius: 999px;

        font-size: 11px;
        font-weight: 700;

        background: rgba(79, 70, 229, .10);
        color: var(--coa-primary);
    }

    /* =========================================================
   ACTIONS
========================================================= */
    .coa-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .coa-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        min-height: 40px;

        padding: 10px 14px;

        border: 1px solid transparent;
        border-radius: 12px;

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;

        transition: .2s ease;
    }

    .coa-btn:hover {
        transform: translateY(-1px);
    }

    .coa-btn.primary {
        color: #fff;
        background: linear-gradient(135deg,
                var(--coa-primary),
                var(--coa-primary-light));
    }

    .coa-btn.light {
        background: #fff;
        border-color: var(--coa-border);
    }

    .coa-btn.warning {
        color: #000;
        background: linear-gradient(135deg,
                #fff069,
                #ffe600);
    }

    .coa-btn.compact {
        min-height: auto;
        padding: 6px 10px;
        font-size: 12px;
    }

    /* =========================================================
   STATS
========================================================= */
    .coa-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;

        margin-bottom: 18px;
    }

    .stat-card {
        position: relative;

        display: flex;
        align-items: center;
        gap: 14px;

        padding: 16px;

        background: linear-gradient(135deg,
                #ffffff,
                #f9fafb);

        border: 1px solid var(--coa-border);
        border-radius: 18px;

        overflow: hidden;

        transition: .25s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::after {
        content: "";

        position: absolute;
        inset: auto 0 0 0;

        height: 4px;

        transform: scaleX(0);
        transform-origin: left;

        transition: .25s ease;
    }

    .stat-card:hover::after {
        transform: scaleX(1);
    }

    .stat-card.total::after {
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
    }

    .stat-card.active::after {
        background: linear-gradient(90deg, #22c55e, #4ade80);
    }

    .stat-card.inactive::after {
        background: linear-gradient(90deg, #ef4444, #f87171);
    }

    .stat-card.header::after {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    .stat-icon {
        width: 48px;
        height: 48px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        font-size: 20px;

        background: #f3f4f6;
    }

    .stat-card.total .stat-icon {
        color: #2563eb;
        background: rgba(59, 130, 246, .12);
    }

    .stat-card.active .stat-icon {
        color: #16a34a;
        background: rgba(34, 197, 94, .12);
    }

    .stat-card.inactive .stat-icon {
        color: #dc2626;
        background: rgba(239, 68, 68, .12);
    }

    .stat-card.header .stat-icon {
        color: #d97706;
        background: rgba(245, 158, 11, .12);
    }

    .stat-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--coa-text-soft);
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
    }

    .stat-desc {
        margin-top: 2px;

        font-size: 11px;
        color: #9ca3af;
    }

    /* =========================================================
   FILTER
========================================================= */
    .coa-filter {
        margin-bottom: 16px;
        padding: 14px;

        background: #fff;

        border: 1px solid var(--coa-border);
        border-radius: 14px;
    }

    .coa-filter form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .coa-search-wrapper {
        position: relative;
        flex: 1;
    }

    .coa-search-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;

        transform: translateY(-50%);
        color: #9ca3af;
    }

    .coa-search-input,
    .coa-select {
        width: 100%;
        height: 44px;

        border: 1px solid var(--coa-border);
        border-radius: 12px;

        font-size: 13px;
    }

    .coa-search-input {
        padding-left: 38px;
    }

    .coa-select {
        min-width: 150px;
        padding: 0 12px;
    }

    /* =========================================================
   TABLE CARD
========================================================= */
    .coa-table-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #d6dde6;
        border-radius: 16px;

        box-shadow: var(--shadow-sm);
    }

    .coa-table-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 12px 14px;

        background: #f8fafc;
        border-bottom: 1px solid var(--coa-border);
    }

    .table-title {
        font-size: 13px;
        font-weight: 700;
    }

    .table-count {
        font-size: 11px;
        color: var(--coa-text-soft);
    }

    /* =========================================================
   TABLE SCROLL
========================================================= */
    .coa-table-scroll {
        position: relative;

        max-height: 78vh;

        overflow: auto;

        isolation: isolate;

        -webkit-overflow-scrolling: touch;
    }

    /* =========================================================
   TABLE
========================================================= */
    .coa-table {
        width: 100%;
        min-width: 1400px;

        border-collapse: separate;
        border-spacing: 0;

        table-layout: auto;

        font-size: 12px;
        color: #1f2937;
    }

    /* =========================================================
   TABLE HEADER FIX STICKY
========================================================= */
    .coa-table thead {
        position: sticky;
        top: 0;
        z-index: 40;
    }

    .coa-table thead th {
        position: sticky;
        top: 0;

        z-index: 50;

        padding: 9px 10px;

        text-align: left;
        white-space: nowrap;

        font-size: 11px;
        font-weight: 800;
        color: #374151;

        background: #eef2f7 !important;

        border-bottom: 1px solid #cfd8e3;
        border-right: 1px solid #dde5ee;

        background-clip: padding-box;

        box-shadow:
            inset 0 -1px 0 #cfd8e3,
            0 1px 0 rgba(255, 255, 255, .7);
    }

    /* FIX STICKY BLINK / TRANSPARENT */
    .coa-table thead th::before {
        content: "";

        position: absolute;
        inset: 0;

        z-index: -1;

        background: linear-gradient(to bottom,
                #f8fafc,
                #e9eef5);
    }

    /* =========================================================
   TABLE BODY
========================================================= */
    .coa-table tbody td {
        position: relative;

        padding: 7px 10px;

        vertical-align: middle;

        background: #fff;

        border-bottom: 1px solid #edf2f7;
        border-right: 1px solid #f1f5f9;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .coa-table tbody tr:nth-child(even) td {
        background: #fafcff;
    }

    .coa-table tbody tr:hover td {
        background: #edf6ff !important;
    }

    /* =========================================================
   TREE LEVEL
========================================================= */
    .coa-row.level-0 td {
        background: #e8eef5 !important;
        font-weight: 700;
    }

    .coa-row.level-1 td {
        background: #f8fafc;
        font-weight: 600;
    }

    /* =========================================================
   TREE
========================================================= */
    .coa-code-cell {
        font-family: Consolas, monospace;
    }

    .coa-code-wrap {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .tree-lines {
        display: flex;
    }

    .tree-lines .v-line {
        position: relative;

        width: 16px;

        border-left: 1px solid #cbd5e1;
    }

    .tree-lines .v-line:last-child::after {
        content: "";

        position: absolute;
        top: 50%;
        left: 0;

        width: 9px;

        border-top: 1px solid #cbd5e1;
    }

    .coa-toggle-btn {
        width: 16px;
        height: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 0;

        border: 1px solid #cbd5e1;
        border-radius: 3px;

        background: #fff;

        cursor: pointer;
    }

    .coa-toggle-btn i {
        font-size: 9px;
    }

    .coa-leaf-dot {
        width: 5px;
        height: 5px;

        border-radius: 999px;

        background: #94a3b8;
    }

    /* =========================================================
   BADGE
========================================================= */
    .coa-type-badge,
    .coa-balance-badge,
    .coa-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 4px 8px;

        border-radius: 8px;

        font-size: 10px;
        font-weight: 700;
    }

    /* TYPE */
    .type-asset {
        background: rgba(59, 130, 246, .12);
        color: #2563eb;
    }

    .type-liability {
        background: rgba(239, 68, 68, .12);
        color: #dc2626;
    }

    .type-equity {
        background: rgba(139, 92, 246, .12);
        color: #7c3aed;
    }

    .type-revenue {
        background: rgba(34, 197, 94, .12);
        color: #16a34a;
    }

    .type-expense {
        background: rgba(249, 115, 22, .12);
        color: #ea580c;
    }

    /* BALANCE */
    .coa-balance-badge.debit {
        color: #2563eb;
        background: rgba(59, 130, 246, .12);
        border: 1px solid rgba(59, 130, 246, .2);
    }

    .coa-balance-badge.credit {
        color: #dc2626;
        background: rgba(239, 68, 68, .12);
        border: 1px solid rgba(239, 68, 68, .2);
    }

    /* STATUS */
    .coa-status.active {
        color: #166534;
        background: #dcfce7;
    }

    .coa-status.inactive {
        color: #991b1b;
        background: #fee2e2;
    }

    /* =========================================================
   ACTION BUTTON
========================================================= */
    .coa-action-group {
        display: flex;
        justify-content: center;
        gap: 6px;
    }

    .coa-icon-btn {
        width: 30px;
        height: 30px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;
        border: none;

        cursor: pointer;

        transition: .2s ease;
    }

    .coa-icon-btn:hover {
        transform: translateY(-2px);
    }

    .coa-icon-btn.primary {
        color: #2563eb;
        background: rgba(59, 130, 246, .12);
        border: 1px solid rgba(59, 130, 246, .2);
    }

    .coa-icon-btn.view {
        color: #2563eb;
        background: rgba(59, 130, 246, .12);
        border: 1px solid rgba(59, 130, 246, .2);
    }

    .coa-icon-btn.success {
        color: #16a34a;
        background: rgba(34, 197, 94, .12);
        border: 1px solid rgba(34, 197, 94, .2);
    }

    .coa-icon-btn.danger {
        color: #dc2626;
        background: rgba(239, 68, 68, .12);
        border: 1px solid rgba(239, 68, 68, .2);
    }

    .coa-icon-btn.warning {
        color: #000000;
        background: #f0e2a8;
        border: 1px solid #facc15;
    }

    .btn-warning-soft {
        color: #1f2937;

        background: #f0e2a8;
        border: 1px solid #facc15;

        box-shadow: 0 2px 6px rgba(250, 204, 21, .25);
    }

    .btn-warning-soft:hover {
        background: #eab308;
    }




    .coa-status.info {
        color: #2563eb;
        background: rgba(59, 130, 246, .12);
        border: 1px solid rgba(59, 130, 246, .2);
    }

    .coa-status.active {
        color: #16a34a;
        background: rgba(34, 197, 94, .12);
        border: 1px solid rgba(34, 197, 94, .2);
    }

    .coa-status.inactive {
        color: #dc2626;
        background: rgba(239, 68, 68, .12);
        border: 1px solid rgba(239, 68, 68, .2);
    }

    .coa-status.danger {
        color: #dc2626;
        background: rgba(239, 68, 68, .12);
        border: 1px solid rgba(239, 68, 68, .2);
    }

    .coa-status.warning {
        color: #A88700;
        background: #f0e2a8;
        border: 1px solid #FFDA46;
    }

    /* =========================================================
   MODAL
========================================================= */
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

        display: flex;
        flex-direction: column;
        gap: 12px;

        padding: 18px;

        background: #fff;

        border-radius: 18px;

        animation: coaModal .2s ease;
    }

    @keyframes coaModal {
        from {
            opacity: 0;
            transform: scale(.96);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* =========================================================
   FORM
========================================================= */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 12px;
        color: var(--coa-text-soft);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;

        padding: 10px 12px;

        border: 1px solid var(--coa-border);
        border-radius: 10px;

        font-size: 13px;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;

        border-color: var(--coa-primary);

        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;

        margin-top: 10px;
    }

    /* =========================================================
   PAGINATION
========================================================= */
    .coa-pagination {
        display: flex;
        justify-content: flex-end;

        margin-top: 15px;
    }

    /* =========================================================
   MOBILE
========================================================= */
    @media (max-width: 768px) {

        .coa-page {
            padding: 10px;
        }

        .coa-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .coa-header h2 {
            font-size: 18px;
        }

        .coa-actions {
            width: 100%;
            flex-direction: column;
        }

        .coa-btn {
            width: 100%;
        }

        .coa-stats {
            grid-template-columns: 1fr;
        }

        .coa-filter form {
            flex-direction: column;
        }

        .coa-search-wrapper,
        .coa-select {
            width: 100%;
        }

        .coa-table {
            min-width: 920px;
        }

        .coa-table thead th {
            font-size: 10px;
            padding: 8px;
        }

        .coa-table tbody td {
            font-size: 10px;
            padding: 6px 8px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .modal-actions {
            flex-direction: column-reverse;
        }

        .modal-actions .coa-btn {
            width: 100%;
        }
    }

    <style>.coa-status-list {
        display: grid;
        gap: 14px;
        margin-top: 10px;
    }

    .swal-status-btn {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
        text-align: left;
        background: white;
        cursor: pointer;
        transition: .2s;
    }

    .swal-status-btn:hover {
        border-color: #2563eb;
        transform: translateY(-2px);
    }

    .swal-status-btn.disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    .swal-status-header {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .swal-status-header i {
        font-size: 24px;
    }

    .status-desc {
        font-size: 13px;
        line-height: 1.7;
        color: #6b7280;
    }

    .coa-modal-style {
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .coa-modal-header {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 18px;
    }

    .coa-modal-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        font-size: 18px;
        box-shadow: 0 8px 20px rgba(79, 70, 229, .25);
    }

    .coa-modal-body {
        background: #f9fafb;
        padding: 18px;
    }

    .coa-modal-body .form-control,
    .coa-modal-body .form-select {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 13px;
    }

    .coa-modal-body .form-label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
    }

    .coa-modal-footer {
        background: #fff;
        border-top: 1px solid #e5e7eb;
    }

    .coa-btn-primary {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        border: none;
        font-weight: 700;
    }


    /* =========================================================
DETAIL MODAL
========================================================= */

    .coa-detail-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .coa-modal-box {
        max-height: 90vh;
        overflow: hidden;
    }

    .coa-modal-body {
        overflow-y: auto;
        max-height: calc(90vh - 140px);
    }

    /* table detail modal */
    .coa-card .coa-table {
        min-width: 100% !important;
        table-layout: fixed;
        border-collapse: collapse;
    }

    .coa-card .coa-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        white-space: normal;
        word-break: break-word;
    }

    .coa-card .coa-table td:first-child {
        width: 180px;
        font-weight: 700;
        color: #374151;
        background: #f9fafb;
    }

    .coa-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }

    .coa-card h4 {
        margin: 0;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8fafc;
        font-size: 14px;
        font-weight: 700;
        a
    }

    #coaTree {
        padding: 16px;
        max-height: 350px;
        overflow: auto;
        font-size: 13px;
        line-height: 1.7;
    }

    .coa-detail-modal {
        width: 1000px;
        max-width: 95vw;
    }
</style>
