@extends('backend.layouts.app')

@section('content')
    <div class="coa-page">

        {{-- HEADER --}}
        <div class="coa-header">
            <div>
                <h2>Chart of Accounts</h2>
                <p>Manajemen struktur akun keuangan RW 016</p>
            </div>
            <div class="coa-actions">

                <a href="#" class="coa-btn primary">
                    <i class="bi bi-plus-lg"></i>
                    <span>New Account</span>
                </a>

                <button class="coa-btn light">
                    <i class="bi bi-download"></i>
                    <span>Export</span>
                </button>

            </div>
        </div>

        {{-- STATS --}}
        <div class="coa-stats">

            <div class="stat-card total">
                <div class="stat-icon">📊</div>
                <div>
                    <div class="stat-label">Total Accounts</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-desc">Semua akun dalam sistem</div>
                </div>
            </div>

            <div class="stat-card header">
                <div class="stat-icon">📁</div>
                <div>
                    <div class="stat-label">Header Accounts</div>
                    <div class="stat-value">{{ $stats['header'] }}</div>
                    <div class="stat-desc">Akun induk (non transaksi)</div>
                </div>
            </div>

            <div class="stat-card active">
                <div class="stat-icon">✅</div>
                <div>
                    <div class="stat-label">Active Accounts</div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                    <div class="stat-desc">Akun yang aktif digunakan</div>
                </div>
            </div>

            <div class="stat-card inactive">
                <div class="stat-icon">⛔</div>
                <div>
                    <div class="stat-label">Inactive Accounts</div>
                    <div class="stat-value">{{ $stats['inactive'] }}</div>
                    <div class="stat-desc">Akun nonaktif</div>
                </div>
            </div>

        </div>

        {{-- FILTER --}}
        <div class="coa-filter">

            <form method="GET" class="coa-filter-form">

                <div class="coa-search-wrapper">
                    <i class="bi bi-search"></i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search account, code, name..." class="coa-search-input">
                </div>

                <select name="type" class="coa-select">
                    <option value="">All Type</option>
                    <option value="Asset">Asset</option>
                    <option value="Liability">Liability</option>
                    <option value="Equity">Equity</option>
                </select>

                <select name="status" class="coa-select">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

                <button class="coa-btn">
                    Filter
                </button>

            </form>

        </div>

        {{-- TABLE --}}
        <div class="coa-table-card">
            <div class="coa-table-scroll">

                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($accounts[null] ?? [] as $account)
                            @include('backend.accounting.partials.coa-row', [
                                'account' => $account,
                                'accounts' => $accounts,
                                'level' => 0,
                            ])
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- ================= STYLE (TETAP PUNYAMU, TIDAK DIUBAH) ================= --}}
    <style>
        /* ================= TYPE ================= */
        .coa-type-badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* ASSET */
        .coa-type-badge.type-asset {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }

        /* LIABILITY */
        .coa-type-badge.type-liability {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        /* EQUITY */
        .coa-type-badge.type-equity {
            background: rgba(139, 92, 246, 0.12);
            color: #7c3aed;
        }

        /* REVENUE */
        .coa-type-badge.type-revenue {
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
        }

        /* EXPENSE */
        .coa-type-badge.type-expense {
            background: rgba(249, 115, 22, 0.12);
            color: #ea580c;
        }

        /* ================= BALANCE ================= */
        .coa-balance-badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* DEBIT (ASSET SIDE / LEFT SIDE) */
        .coa-balance-badge.debit {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        /* CREDIT (LIABILITY SIDE / RIGHT SIDE) */
        .coa-balance-badge.credit {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* ================= PAGE ================= */
        .coa-page {
            padding: 20px;
            background: #f4f6fb;
            font-size: 13px;
            color: #111827;
        }

        /* ================= HEADER ================= */
        .coa-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .coa-header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .coa-header p {
            margin: 0;
            color: #6b7280;
        }

        /* ================= STATS ================= */
        .coa-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: #fff;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 20px;
        }

        /* ================= FILTER ================= */
        .coa-filter {
            background: #fff;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 16px;
            border: 1px solid #e5e7eb;
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
            padding: 0 12px 0 38px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            outline: none;
        }

        .coa-search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .coa-select {
            height: 44px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 0 10px;
            min-width: 130px;
            background: #fff;
        }

        .coa-btn {
            height: 44px;
            padding: 0 16px;
            border-radius: 10px;
            background: #111827;
            color: #fff;
            border: none;
        }

        .coa-btn:hover {
            background: #1f2937;
        }

        /* ================= TABLE ================= */
        .coa-table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .coa-table-scroll {
            max-height: 70vh;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            position: sticky;
            top: 0;
            background: #f9fafb;
            z-index: 2;
            text-align: left;
            font-size: 12px;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .coa-row {
            transition: 0.15s;
        }

        .coa-row:hover {
            background: #f8fafc;
        }

        /* ================= TREE ================= */
        .coa-code-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        /* garis vertical halus */
        .coa-code-wrap::before {
            content: "";
            position: absolute;
            left: 10px;
            top: -14px;
            bottom: -14px;
            width: 1px;
            background: #e5e7eb;
            opacity: 0.6;
        }

        /* root tidak ada garis */
        .level-0 .coa-code-wrap::before {
            display: none;
        }

        /* horizontal line */
        .tree-line {
            position: absolute;
            left: 0;
            top: 50%;
            width: 14px;
            height: 1px;
            background: #d1d5db;
        }

        /* ================= BUTTON ================= */
        .coa-toggle-btn {
            width: 24px;
            height: 24px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .coa-toggle-btn:hover {
            background: #f3f4f6;
        }

        /* ================= DOT ================= */
        .coa-leaf-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #94a3b8;
        }

        /* ================= TEXT ================= */
        .coa-code-text {
            font-weight: 600;
            color: #111827;
        }

        .coa-name-text {
            font-weight: 500;
        }

        /* ================= BADGE ================= */
        .coa-badge {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 999px;
            background: #f3f4f6;
        }

        /* ================= STATUS ================= */
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

        /* ================= ACTION ================= */
        .coa-action-group {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
        }

        .coa-icon-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: none;
            background: transparent;
        }

        .coa-icon-btn:hover {
            background: #f3f4f6;
        }

        .coa-icon-btn.danger:hover {
            background: #fee2e2;
        }

        /* ================= STATS GRID ================= */
        .coa-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 18px;
        }

        /* ================= BASE CARD ================= */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.25s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        /* hover effect premium */
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        /* animated glow line */
        .stat-card::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .stat-card:hover::after {
            transform: scaleX(1);
        }

        /* ================= ICON ================= */
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: #f3f4f6;
        }

        /* ================= TEXT ================= */
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
        }

        .stat-desc {
            font-size: 11px;
            color: #9ca3af;
        }

        /* ================= COLOR THEMES ================= */

        /* TOTAL */
        .stat-card.total::after {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
        }

        .stat-card.total .stat-icon {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }

        /* HEADER */
        .stat-card.header::after {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .stat-card.header .stat-icon {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
        }

        /* ACTIVE */
        .stat-card.active::after {
            background: linear-gradient(90deg, #22c55e, #4ade80);
        }

        .stat-card.active .stat-icon {
            background: rgba(34, 197, 94, 0.12);
            color: #16a34a;
        }

        /* INACTIVE */
        .stat-card.inactive::after {
            background: linear-gradient(90deg, #ef4444, #f87171);
        }

        .stat-card.inactive .stat-icon {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }


        /* ================= ACTION WRAPPER ================= */
        .coa-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* ================= BASE BUTTON ================= */
        .coa-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        /* ICON */
        .coa-btn i {
            font-size: 14px;
            transition: transform 0.25s ease;
        }

        /* ================= PRIMARY BUTTON ================= */
        .coa-btn.primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.25);
        }

        /* hover primary */
        .coa-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.35);
        }

        /* icon bounce */
        .coa-btn.primary:hover i {
            transform: translateX(2px);
        }

        /* ================= LIGHT BUTTON ================= */
        .coa-btn.light {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #111827;
        }

        /* hover light */
        .coa-btn.light:hover {
            background: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.06);
        }

        /* icon rotate soft */
        .coa-btn.light:hover i {
            transform: rotate(-10deg);
        }

        /* ================= CLICK EFFECT ================= */
        .coa-btn:active {
            transform: scale(0.97);
        }
    </style>

    {{-- ================= TOGGLE FIXED ================= --}}
    <script>
        document.addEventListener('click', function(e) {

            const btn = e.target.closest('.coa-toggle-btn');
            if (!btn) return;

            const row = btn.closest('tr');
            const id = row.dataset.id;

            const children = document.querySelectorAll(`[data-parent="${id}"]`);

            if (children.length === 0) return;

            const isOpen = btn.dataset.state === 'open';

            children.forEach(child => {
                child.style.display = isOpen ? 'none' : '';
            });

            btn.dataset.state = isOpen ? 'closed' : 'open';

            const icon = btn.querySelector('i');

            if (icon) {
                icon.className = isOpen ?
                    'bi bi-chevron-right' :
                    'bi bi-chevron-down';
            }

        });
    </script>
@endsection
