@extends('backend.layouts.app')

@section('content')
    <div class="coa-page">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <div class="coa-header">

            <div class="coa-header-left">

                <div class="coa-title-wrap">

                    <div class="coa-title-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>

                    <div>
                        <h2>Chart Of Accounts</h2>

                        <p>
                            Manajemen struktur akun keuangan,
                            grouping COA, dan klasifikasi accounting system
                        </p>
                    </div>

                </div>

            </div>

            <div class="coa-actions">

                <button type="button" class="coa-btn primary compact" id="btnAddAccount">

                    <i class="bi bi-plus-lg"></i>
                    <span>New Account</span>

                </button>

                <button type="button" class="coa-btn light compact">

                    <i class="bi bi-download"></i>
                    <span>Export</span>

                </button>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- STATS --}}
        {{-- ===================================================== --}}
        <div class="coa-stats">

            {{-- TOTAL --}}
            <div class="stat-card total">

                <div class="stat-icon">
                    <i class="bi bi-grid-1x2"></i>
                </div>

                <div class="stat-content">

                    <div class="stat-label">
                        Total Accounts
                    </div>

                    <div class="stat-value">
                        {{ number_format($stats['total']) }}
                    </div>

                    <div class="stat-desc">
                        Semua akun dalam sistem
                    </div>

                </div>

            </div>

            {{-- HEADER --}}
            <div class="stat-card header">

                <div class="stat-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>

                <div class="stat-content">

                    <div class="stat-label">
                        Header Accounts
                    </div>

                    <div class="stat-value">
                        {{ number_format($stats['header']) }}
                    </div>

                    <div class="stat-desc">
                        Akun induk / grouping
                    </div>

                </div>

            </div>

            {{-- ACTIVE --}}
            <div class="stat-card active">

                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div class="stat-content">

                    <div class="stat-label">
                        Active Accounts
                    </div>

                    <div class="stat-value">
                        {{ number_format($stats['active']) }}
                    </div>

                    <div class="stat-desc">
                        Akun aktif digunakan
                    </div>

                </div>

            </div>

            {{-- INACTIVE --}}
            <div class="stat-card inactive">

                <div class="stat-icon">
                    <i class="bi bi-slash-circle"></i>
                </div>

                <div class="stat-content">

                    <div class="stat-label">
                        Inactive Accounts
                    </div>

                    <div class="stat-value">
                        {{ number_format($stats['inactive']) }}
                    </div>

                    <div class="stat-desc">
                        Akun tidak aktif
                    </div>

                </div>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- FILTER --}}
        {{-- ===================================================== --}}
        <div class="coa-filter">

            <form method="GET" class="coa-filter-form">

                {{-- SEARCH --}}
                <div class="coa-search-wrapper">

                    <i class="bi bi-search"></i>

                    <input type="text" name="search" class="coa-search-input"
                        placeholder="Search code or account name..." value="{{ request('search') }}">

                </div>

                {{-- TYPE --}}
                <select name="type" class="coa-select">

                    <option value="">
                        All Type
                    </option>

                    <option value="asset" {{ request('type') == 'asset' ? 'selected' : '' }}>
                        Asset
                    </option>

                    <option value="liability" {{ request('type') == 'liability' ? 'selected' : '' }}>
                        Liability
                    </option>

                    <option value="equity" {{ request('type') == 'equity' ? 'selected' : '' }}>
                        Equity
                    </option>

                    <option value="revenue" {{ request('type') == 'revenue' ? 'selected' : '' }}>
                        Revenue
                    </option>

                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>
                        Expense
                    </option>

                </select>

                {{-- STATUS --}}
                <select name="status" class="coa-select">

                    <option value="">
                        All Status
                    </option>

                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>

                {{-- FILTER BUTTON --}}
                <button type="submit" class="coa-btn primary compact">

                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>

                </button>

                {{-- RESET --}}
                <a href="{{ url()->current() }}" class="coa-btn light compact">

                    <i class="bi bi-arrow-clockwise"></i>
                    <span>Reset</span>

                </a>

            </form>

        </div>

        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}
        <div class="coa-table-card">

            {{-- TABLE --}}
            <div class="coa-table-scroll">

                <table class="coa-table">

                    <thead>

                        <tr>

                            <th width="220">
                                Account Code
                            </th>

                            <th>
                                Account Name
                            </th>

                            <th width="140">
                                Type
                            </th>

                            <th width="130">
                                Normal Balance
                            </th>

                            <th width="120">
                                Status
                            </th>

                            <th width="140" class="text-center">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($accounts->get(null, collect()) as $account)
                            @include('backend.accounting.partials.coa-row', [
                                'account' => $account,
                                'accounts' => $accounts,
                                'level' => 0,
                            ])

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div class="coa-empty-state">

                                        <div class="empty-icon">

                                            <i class="bi bi-folder2-open"></i>

                                        </div>

                                        <h5>
                                            No Accounts Found
                                        </h5>

                                        <p>
                                            Tidak ada data chart of account
                                            yang tersedia.
                                        </p>


                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    @include('backend.accounting.components.Modal_tambah_coa')
    @include('backend.accounting.components.Modal_edit_coa')


    {{-- ===================================================== --}}
    {{-- MODAL DETAIL COA --}}
    {{-- ===================================================== --}}
    <div class="coa-modal" id="coaDetailModal">

        <div class="coa-modal-overlay"></div>

        <div class="coa-modal-box" style="max-width: 1000px;">

            {{-- HEADER --}}
            <div class="coa-modal-header">

                <div>

                    <h3 id="detailTitle">
                        Detail Chart of Account
                    </h3>

                    <small>
                        Informasi lengkap struktur akun dan hierarki COA
                    </small>

                </div>

            </div>

            {{-- BODY --}}
            <div class="coa-modal-body">

                <div class="coa-detail-grid">

                    {{-- ===================================== --}}
                    {{-- INFORMASI AKUN --}}
                    {{-- ===================================== --}}
                    <div class="coa-card">

                        <h4>
                            Informasi Akun
                        </h4>

                        <table class="coa-table">

                            <tr>
                                <td>Kode Akun</td>
                                <td id="d_code"></td>
                            </tr>

                            <tr>
                                <td>Nama Akun</td>
                                <td id="d_name"></td>
                            </tr>

                            <tr>
                                <td>Tipe Akun</td>
                                <td id="d_type"></td>
                            </tr>

                            <tr>
                                <td>Mode Akun</td>
                                <td id="d_mode"></td>
                            </tr>

                            <tr>
                                <td>Saldo Normal</td>
                                <td id="d_balance"></td>
                            </tr>

                            <tr>
                                <td>Status Akun</td>
                                <td id="d_status"></td>
                            </tr>

                            <tr>
                                <td>Level Akun</td>
                                <td id="d_level"></td>
                            </tr>

                            <tr>
                                <td>Path Akun</td>
                                <td id="d_path"></td>
                            </tr>

                        </table>

                    </div>
                    <br><br>

                    {{-- ===================================== --}}
                    {{-- HIERARCHY --}}
                    {{-- ===================================== --}}
                    <div class="coa-card">

                        <h4>
                            Struktur Hierarki
                        </h4>

                        <div id="coaTree"></div>

                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- FOOTER ACTION --}}
            {{-- ===================================================== --}}
            <div class="coa-modal-footer"
                style="display:flex; justify-content:flex-end; gap:10px; padding:15px; border-top:1px solid #eee;">

                <button type="button" class="coa-btn light" id="closeDetailModalFooter">

                    Tutup

                </button>

            </div>

        </div>

    </div>





    @include('backend.accounting.style_accounting')
    @include('backend.accounting.script_accounting')
@endsection
