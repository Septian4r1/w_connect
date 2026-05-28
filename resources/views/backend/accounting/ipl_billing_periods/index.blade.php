@extends('backend.layouts.app')

@section('content')
    <div class="coa-page billing-period-page">

        {{-- =========================================================
        HEADER
        ========================================================= --}}
        <div class="coa-header">

            <div>
                <h2>IPL Billing Period</h2>

                <p>
                    Manajemen periode billing IPL,
                    generate invoice warga,
                    monitoring pembayaran,
                    dan kontrol penagihan berbasis accounting period.
                </p>
            </div>

            <div class="coa-actions">

                {{-- CREATE --}}
                <a href="" class="coa-btn primary compact">

                    <b>
                        <i class="bi bi-plus-lg"></i>
                    </b>

                    Billing Period
                </a>

                {{-- EXPORT --}}
                <button type="button" class="coa-btn light compact">

                    <b>
                        <i class="bi bi-download"></i>
                    </b>

                    <span>Export</span>
                </button>

            </div>

        </div>

        {{-- =========================================================
        STATS
        ========================================================= --}}
        <div class="coa-stats">

            {{-- TOTAL --}}
            <div class="stat-card total">

                <div class="stat-icon">
                    <i class="bx bx-calendar"></i>
                </div>

                <div>

                    <div class="stat-label">
                        TOTAL
                    </div>

                    <div class="stat-value">
                        {{ number_format($summary['total_periods'] ?? 0) }}
                    </div>

                    <div class="stat-desc">
                        Semua billing period
                    </div>

                </div>

            </div>

            {{-- ACTIVE --}}
            <div class="stat-card active">

                <div class="stat-icon">
                    <i class="bx bx-check-circle"></i>
                </div>

                <div>

                    <div class="stat-label">
                        ACTIVE
                    </div>

                    <div class="stat-value">
                        {{ number_format($summary['active_periods'] ?? 0) }}
                    </div>

                    <div class="stat-desc">
                        Billing aktif
                    </div>

                </div>

            </div>

            {{-- CLOSED --}}
            <div class="stat-card inactive">

                <div class="stat-icon">
                    <i class="bx bx-lock"></i>
                </div>

                <div>

                    <div class="stat-label">
                        CLOSED
                    </div>

                    <div class="stat-value">
                        {{ number_format($summary['closed_periods'] ?? 0) }}
                    </div>

                    <div class="stat-desc">
                        Billing ditutup
                    </div>

                </div>

            </div>

            {{-- TOTAL AMOUNT --}}
            <div class="stat-card header">

                <div class="stat-icon">
                    <i class="bx bx-wallet"></i>
                </div>

                <div>

                    <div class="stat-label">
                        TOTAL TAGIHAN
                    </div>

                    <div class="stat-value">
                        Rp {{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="stat-desc">
                        Total nominal billing
                    </div>

                </div>

            </div>

        </div>

        {{-- =========================================================
        FILTER
        ========================================================= --}}
        <div class="coa-filter"
            style="
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:14px 16px;
        margin-bottom:16px;
    ">

            <form method="GET" action="{{ route('management.iplbillingperiode.index') }}"
                style="
            display:flex;
            align-items:center;
            gap:10px;
            width:100%;
            flex-wrap:nowrap;
            overflow-x:auto;
        ">

                {{-- SEARCH --}}
                <div class="coa-search-wrapper"
                    style="
                position:relative;
                min-width:260px;
                flex:1;
            ">

                    <i class="bx bx-search"
                        style="
                    position:absolute;
                    left:14px;
                    top:50%;
                    transform:translateY(-50%);
                    color:#9ca3af;
                    font-size:14px;
                "></i>

                    <input type="text" name="search" value="{{ request('search') }}" class="coa-search-input"
                        placeholder="Cari code / name..."
                        style="
                    width:100%;
                    height:42px;
                    border:1px solid #d1d5db;
                    border-radius:12px;
                    padding:0 14px 0 40px;
                    font-size:13px;
                    background:#fff;
                ">
                </div>

                {{-- ORGANIZATION --}}
                <select name="organization_id" class="coa-select"
                    style="
                width:220px;
                height:42px;
                border:1px solid #d1d5db;
                border-radius:12px;
                padding:0 12px;
                font-size:13px;
                background:#fff;
                flex-shrink:0;
            ">

                    <option value="">Semua Organization</option>

                    @foreach ($organizations as $org)
                        <option value="{{ $org->id }}" {{ request('organization_id') == $org->id ? 'selected' : '' }}>

                            {{ $org->code }} - {{ $org->name }}

                        </option>
                    @endforeach

                </select>

                {{-- ACCOUNTING PERIOD --}}
                <select name="accounting_period_id" class="coa-select"
                    style="
                width:220px;
                height:42px;
                border:1px solid #d1d5db;
                border-radius:12px;
                padding:0 12px;
                font-size:13px;
                background:#fff;
                flex-shrink:0;
            ">

                    <option value="">Semua Accounting Period</option>

                    @foreach ($accountingPeriods as $acc)
                        <option value="{{ $acc->id }}"
                            {{ request('accounting_period_id') == $acc->id ? 'selected' : '' }}>

                            {{ $acc->code }}

                        </option>
                    @endforeach

                </select>

                {{-- STATUS --}}
                <select name="status" class="coa-select"
                    style="
                width:170px;
                height:42px;
                border:1px solid #d1d5db;
                border-radius:12px;
                padding:0 12px;
                font-size:13px;
                background:#fff;
                flex-shrink:0;
            ">

                    <option value="">Semua Status</option>

                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                        DRAFT
                    </option>

                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>
                        OPEN
                    </option>

                    <option value="generated" {{ request('status') == 'generated' ? 'selected' : '' }}>
                        GENERATED
                    </option>

                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>
                        CLOSED
                    </option>

                </select>

                {{-- BILLING TYPE --}}
                <select name="billing_type" class="coa-select"
                    style="
                width:160px;
                height:42px;
                border:1px solid #d1d5db;
                border-radius:12px;
                padding:0 12px;
                font-size:13px;
                background:#fff;
                flex-shrink:0;
            ">

                    <option value="">Semua Type</option>

                    <option value="IPL" {{ request('billing_type') == 'IPL' ? 'selected' : '' }}>
                        IPL
                    </option>

                    <option value="DENDA" {{ request('billing_type') == 'DENDA' ? 'selected' : '' }}>
                        DENDA
                    </option>

                    <option value="KHUSUS" {{ request('billing_type') == 'KHUSUS' ? 'selected' : '' }}>
                        KHUSUS
                    </option>

                    <option value="DLL" {{ request('billing_type') == 'DLL' ? 'selected' : '' }}>
                        DLL
                    </option>

                </select>

                {{-- FILTER BUTTON --}}
                <button type="submit" class="coa-btn light"
                    style="
                height:42px;
                padding:0 18px;
                border-radius:12px;
                border:1px solid #d1d5db;
                background:#fff;
                font-size:13px;
                font-weight:600;
                display:flex;
                align-items:center;
                justify-content:center;
                gap:6px;
                white-space:nowrap;
                flex-shrink:0;
            ">

                    <i class="bx bx-filter-alt"></i>
                    Filter

                </button>

            </form>

        </div>

        {{-- =========================================================
        TABLE
        ========================================================= --}}
        <div class="coa-table-card">

            {{-- TOOLBAR --}}
            <div class="coa-table-toolbar">

                <div>

                    <span class="table-title">
                        IPL Billing Periods
                    </span>

                    <span class="table-count">
                        ({{ $billingPeriods->total() ?? 0 }} data)
                    </span>

                </div>

            </div>

            {{-- TABLE --}}
            <div class="coa-table-scroll">

                <table class="coa-table">

                    <thead>

                        <tr>

                            <th width="200" style="text-align:center;">
                                Code
                            </th>

                            <th style="text-align:center;">
                                Billing Name
                            </th>

                            <th width="140" style="text-align:center;">
                                Organization
                            </th>

                            <th width="150" style="text-align:center;">
                                Accounting Period
                            </th>

                            <th width="120" style="text-align:center;">
                                Type
                            </th>

                            <th width="120" style="text-align:center;">
                                Category
                            </th>

                            <th width="120" style="text-align:center;">
                                Invoice Date
                            </th>

                            <th width="120" style="text-align:center;">
                                Due Date
                            </th>

                            <th width="120" style="text-align:center;">
                                Invoice
                            </th>

                            <th width="170" style="text-align:center;">
                                Amount
                            </th>

                            <th width="120" style="text-align:center;">
                                Paid
                            </th>

                            <th width="120" style="text-align:center;">
                                Status
                            </th>

                            <th width="100" style="text-align:center;">
                                Generated
                            </th>

                            <th width="160" style="text-align:center;">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($billingPeriods as $period)
                            <tr>

                                {{-- CODE --}}
                                <td class="coa-code-cell">

                                    {{ $period->code }}

                                </td>

                                {{-- NAME --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $period->name }}
                                    </div>

                                    @if ($period->description)
                                        <small class="text-muted">

                                            {{ Str::limit($period->description, 50) }}

                                        </small>
                                    @endif

                                </td>

                                {{-- ORGANIZATION --}}
                                <td style="text-align:center;">

                                    {{ $period->organization?->code }}

                                </td>

                                {{-- ACCOUNTING PERIOD --}}
                                <td style="text-align:center;">

                                    {{ $period->accountingPeriod?->code }}

                                </td>

                                {{-- TYPE --}}
                                <td style="text-align:center;">

                                    <span class="coa-status active">

                                        {{ $period->billing_type }}

                                    </span>

                                </td>

                                {{-- CATEGORY --}}
                                <td style="text-align:center;">

                                    {{ $period->category }}

                                </td>

                                {{-- INVOICE DATE --}}
                                <td style="text-align:center;">

                                    {{ optional($period->invoice_date)->format('Y-m-d') }}

                                </td>

                                {{-- DUE DATE --}}
                                <td style="text-align:center;">

                                    {{ optional($period->due_date)->format('Y-m-d') }}

                                </td>

                                {{-- TOTAL INVOICE --}}
                                <td style="text-align:center;">

                                    {{ number_format($period->total_invoices) }}

                                </td>

                                {{-- TOTAL AMOUNT --}}
                                <td style="text-align:right;">

                                    Rp
                                    {{ number_format($period->total_amount, 0, ',', '.') }}

                                </td>

                                {{-- TOTAL PAID --}}
                                <td style="text-align:right;">

                                    Rp
                                    {{ number_format($period->total_paid, 0, ',', '.') }}

                                </td>

                                {{-- STATUS --}}
                                {{-- STATUS --}}
                                <td style="text-align:center;">

                                    <span
                                        class="
                                        coa-status

                                        {{ $period->status == 'open' ? 'active' : '' }}

                                        {{ $period->status == 'closed' ? 'inactive' : '' }}

                                        {{ $period->status == 'draft' ? 'warning' : '' }}

                                        {{ $period->status == 'cancelled' ? 'danger' : '' }}

                                        {{ $period->status == 'generated' ? 'info' : '' }}
                                    ">

                                        {{ strtoupper($period->status) }}

                                    </span>

                                </td>

                                {{-- GENERATED --}}
                                <td style="text-align:center;">

                                    @if ($period->is_generated)
                                        <span class="coa-status active">
                                            YES
                                        </span>
                                    @else
                                        <span class="coa-status inactive">
                                            NO
                                        </span>
                                    @endif

                                </td>

                                {{-- ACTION --}}
                                <td>

                                    <div class="coa-action-group">

                                        {{-- VIEW --}}
                                        <a href="" class="coa-icon-btn view">

                                            <i class="bx bx-show"></i>

                                        </a>

                                        {{-- EDIT --}}
                                        <a href="" class="coa-icon-btn">

                                            <i class="bx bx-edit"></i>

                                        </a>

                                        {{-- GENERATE --}}
                                        @if (!$period->is_generated)
                                            <button class="coa-icon-btn success" title="Generate Invoice">

                                                <i class="bx bx-layer-plus"></i>

                                            </button>
                                        @endif

                                        {{-- DELETE --}}
                                        <button class="coa-icon-btn danger">

                                            <i class="bx bx-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="14" class="text-center py-5">

                                    <div class="text-muted">

                                        Tidak ada data IPL Billing Period

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="coa-pagination d-flex justify-content-end mt-3">

                {{ $billingPeriods->withQueryString()->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

    @include('backend.accounting.style_accounting')
@endsection
