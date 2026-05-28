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
                        <i class="bi bi-cash-coin"></i>
                    </div>

                    <div>

                        <h2>Funding Amount</h2>

                        <p>
                            Manajemen nominal iuran funding per organisasi
                        </p>

                    </div>

                </div>

            </div>

            <button type="button" class="coa-btn primary compact" data-bs-toggle="modal"
                data-bs-target="#createFundingAmountModal">

                <span class="btn-icon">
                    <i class="bi bi-plus-circle"></i>
                </span>

                <span>
                    Tambah Funding Amount
                </span>

            </button>


        </div>

        {{-- ===================================================== --}}
        {{-- STATS --}}
        {{-- ===================================================== --}}
        <div class="coa-stats">

            <div class="stat-card total">

                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Total Funding
                    </div>

                    <div class="stat-value">
                        {{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}
                    </div>

                    <div class="stat-desc">
                        Total seluruh nominal funding
                    </div>
                </div>

            </div>

            <div class="stat-card active">

                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Active Funding
                    </div>

                    <div class="stat-value">
                        {{ $summary['active_count'] ?? 0 }}
                    </div>

                    <div class="stat-desc">
                        Funding aktif
                    </div>
                </div>

            </div>

            <div class="stat-card inactive">

                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Inactive Funding
                    </div>

                    <div class="stat-value">
                        {{ $summary['inactive_count'] ?? 0 }}
                    </div>

                    <div class="stat-desc">
                        Funding nonaktif
                    </div>
                </div>

            </div>

            <div class="stat-card header">

                <div class="stat-icon">
                    <i class="bi bi-buildings"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Organizations
                    </div>

                    <div class="stat-value">
                        {{ $summary['organization_count'] ?? 0 }}
                    </div>

                    <div class="stat-desc">
                        Total organisasi
                    </div>
                </div>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- FILTER --}}
        {{-- ===================================================== --}}
        <div class="coa-filter"
            style="
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:14px 16px;
        margin-bottom:16px;
    ">

            <form method="GET" action="{{ route('management.funding-amount.index') }}"
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
                min-width:280px;
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
                        placeholder="Cari fund code / fund name / organization..."
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
                width:240px;
                height:42px;
                border:1px solid #d1d5db;
                border-radius:12px;
                padding:0 12px;
                font-size:13px;
                background:#fff;
                flex-shrink:0;
            ">

                    <option value="">
                        Semua Organization
                    </option>

                    @foreach ($organizations as $organization)
                        <option value="{{ $organization->id }}"
                            {{ request('organization_id') == $organization->id ? 'selected' : '' }}>

                            {{ $organization->code }}
                            -
                            {{ $organization->name }}

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

                    <option value="">
                        Semua Status
                    </option>

                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                        ACTIVE
                    </option>

                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                        INACTIVE
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

                {{-- RESET BUTTON --}}
                <a href="{{ route('management.funding-amount.index') }}"
                    style="
                height:42px;
                padding:0 18px;
                border-radius:12px;
                border:1px solid #fecaca;
                background:#fff1f2;
                color:#dc2626;
                font-size:13px;
                font-weight:600;
                display:flex;
                align-items:center;
                justify-content:center;
                gap:6px;
                white-space:nowrap;
                text-decoration:none;
                flex-shrink:0;
            ">

                    <i class="bx bx-reset"></i>
                    Reset

                </a>

            </form>

        </div>

        {{-- ===================================================== --}}
        {{-- TABLE --}}
        {{-- ===================================================== --}}
        <div class="coa-table-card">

            <div class="coa-table-toolbar">

                <div>

                    <div class="table-title">
                        Funding Amount List
                    </div>

                    <div class="table-count">
                        Total :
                        {{ $fundTypeAmounts->total() ?? 0 }}
                        Data
                    </div>

                </div>

            </div>

            <div class="coa-table-scroll">

                <table class="coa-table">

                    <thead>

                        <tr>
                            <th width="70">
                                No
                            </th>
                            <th width="120">
                                Fund Code
                            </th>
                            <th>
                                Fund Name
                            </th>
                            <th width="120" style="text-align:center;">
                                Organization
                            </th>
                            <th width="140" style="text-align:center;">
                                Amount
                            </th>
                            <th width="140" style="text-align:center;">
                                Status
                            </th>
                            <th width="180" style="text-align:center;">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($fundTypeAmounts as $item)
                            <tr>

                                {{-- NO --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                {{-- CODE --}}
                                <td>

                                    <span class="text-center">

                                        {{ $item->reference_no }}

                                    </span>

                                </td>

                                {{-- NAME --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $item->fundType?->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $item->description }}
                                    </small>
                                </td>
                                {{-- ORGANIZATION --}}
                                <td>
                                    <div class="fw-semibold" style="text-align:center;">
                                        {{ $item->organization?->code }}
                                    </div>
                                </td>

                                {{-- AMOUNT --}}
                                <td>
                                    <div class="fw-bold text-success" style="text-align:center;">
                                        Rp
                                        {{ number_format($item->amount, 0, ',', '.') }}
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td style="text-align:center;">

                                    @if ($item->is_active)
                                        <span class="coa-status active" style="text-align:center;">
                                            ACTIVE
                                        </span>
                                    @else
                                        <span class="coa-status inactive" style="text-align:center;">
                                            INACTIVE
                                        </span>
                                    @endif

                                </td>

                                {{-- ACTION --}}
                                <td>

                                    <div class="coa-action-group">

                                        {{-- EDIT --}}
                                        <button class="coa-icon-btn warning">

                                            <i class="bi bi-pencil-square"></i>

                                        </button>

                                        {{-- DELETE --}}
                                        <button class="coa-icon-btn danger">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5">

                                    <div class="text-muted">

                                        Belum ada data funding amount

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="p-3 border-top">
                {{ $fundTypeAmounts->links('pagination::bootstrap-5') }}
            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- MODAL CREATE FUNDING AMOUNT --}}
        {{-- ===================================================== --}}
        <div class="modal fade" id="createFundingAmountModal" tabindex="-1"
            aria-labelledby="createFundingAmountModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-xl modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg">

                    {{-- ========================================= --}}
                    {{-- MODAL HEADER --}}
                    {{-- ========================================= --}}
                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title fw-bold" id="createFundingAmountModalLabel">

                                <i class="bi bi-cash-coin me-2 text-primary"></i>
                                Tambah Funding Amount

                            </h5>

                            <small class="text-muted">

                                Tambahkan nominal dana untuk kebutuhan operasional,
                                kas RW / RT, sosial, pembangunan, dan transaksi accounting lainnya.

                            </small>

                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    {{-- ========================================= --}}
                    {{-- FORM --}}
                    {{-- ========================================= --}}
                    <form id="createFundingAmountForm" action="{{ route('management.funding-amount.store') }}"
                        method="POST">

                        @csrf

                        {{-- ========================================= --}}
                        {{-- MODAL BODY --}}
                        {{-- ========================================= --}}
                        <div class="modal-body">

                            <div class="row g-4">

                                {{-- ========================================= --}}
                                {{-- FUNDING TYPE --}}
                                {{-- ========================================= --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Funding Type
                                    </label>

                                    <select name="funding_type_id" class="form-select" required>

                                        <option value="">
                                            -- Pilih Funding Type --
                                        </option>

                                        @foreach ($fundingTypes as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->code }} - {{ $item->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <small class="text-muted">

                                        Pilih jenis dana yang akan digunakan
                                        pada funding amount ini.

                                    </small>

                                </div>

                                {{-- ========================================= --}}
                                {{-- ORGANIZATION --}}
                                {{-- ========================================= --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Organization
                                    </label>

                                    <select name="organization_id" class="form-select" required>

                                        <option value="">
                                            -- Pilih Organization --
                                        </option>

                                        @foreach ($organizations as $organization)
                                            <option value="{{ $organization->id }}">
                                                {{ $organization->code }} - {{ $organization->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    <small class="text-muted">

                                        Tentukan organisasi atau unit yang
                                        memiliki dana ini.

                                    </small>

                                </div>

                                {{-- ========================================= --}}
                                {{-- AMOUNT --}}
                                {{-- ========================================= --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Amount
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Rp
                                        </span>

                                        <input type="number" name="amount" class="form-control" placeholder="0"
                                            min="0" required>

                                    </div>

                                    <small class="text-muted">

                                        Masukkan nominal dana yang tersedia.

                                    </small>

                                </div>

                                {{-- ========================================= --}}
                                {{-- STATUS --}}
                                {{-- ========================================= --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Status
                                    </label>

                                    <select name="is_active" class="form-select">

                                        <option value="1">
                                            Active
                                        </option>

                                        <option value="0">
                                            Inactive
                                        </option>

                                    </select>

                                    <small class="text-muted">

                                        Funding aktif dapat digunakan
                                        pada transaksi accounting.

                                    </small>

                                </div>

                                {{-- ========================================= --}}
                                {{-- DATE --}}
                                {{-- ========================================= --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Funding Date
                                    </label>

                                    <input type="date" name="funding_date" class="form-control"
                                        value="{{ now()->format('Y-m-d') }}" required>

                                    <small class="text-muted">

                                        Tanggal pencatatan funding amount.

                                    </small>

                                </div>

                                {{-- ========================================= --}}
                                {{-- REFERENCE --}}
                                {{-- ========================================= --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Reference Number
                                    </label>

                                    <input type="text" class="form-control bg-light" value="Auto Generate By System"
                                        readonly>

                                    <small class="text-muted">
                                        Nomor referensi dibuat otomatis oleh sistem.
                                    </small>

                                </div>

                                {{-- ========================================= --}}
                                {{-- DESCRIPTION --}}
                                {{-- ========================================= --}}
                                <div class="col-12">

                                    <label class="form-label fw-semibold">
                                        Description
                                    </label>

                                    <textarea name="description" rows="5" class="form-control"
                                        placeholder="Contoh:
Dana operasional bulanan untuk kebutuhan kebersihan, keamanan, dan administrasi lingkungan."></textarea>

                                    <small class="text-muted">

                                        Isi keterangan detail funding amount
                                        agar mudah dipahami pengurus dan bagian accounting.

                                    </small>

                                </div>

                            </div>

                        </div>

                        {{-- ========================================= --}}
                        {{-- MODAL FOOTER --}}
                        {{-- ========================================= --}}
                        <div class="modal-footer">

                            <button type="button" class="coa-btn light compact" data-bs-dismiss="modal">

                                <i class="bi bi-x-lg me-1"></i>
                                Batal

                            </button>

                            <button type="submit" class="coa-btn primary compact" id="submitFundingAmountBtn">

                                <span class="default-text">

                                    <i class="bi bi-check2-circle me-1"></i>
                                    Simpan Funding Amount

                                </span>

                                {{-- LOADER --}}
                                <span class="loading-text d-none">

                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Menyimpan...

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- INCLUDE STYLE --}}
        {{-- ===================================================== --}}
        @include('backend.accounting.style_accounting')


        @push('scripts')
            <script>
                $(document).ready(function() {

                    /*
                    |--------------------------------------------------------------------------
                    | SUBMIT CREATE FUNDING AMOUNT
                    |--------------------------------------------------------------------------
                    */

                    $('#createFundingAmountForm').on('submit', function(e) {

                        e.preventDefault();

                        let form = $(this);
                        let submitBtn = $('#submitFundingAmountBtn');

                        submitBtn.prop('disabled', true);
                        submitBtn.find('.default-text').addClass('d-none');
                        submitBtn.find('.loading-text').removeClass('d-none');

                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            dataType: 'json',

                            success: function(response) {

                                submitBtn.prop('disabled', false);
                                submitBtn.find('.default-text').removeClass('d-none');
                                submitBtn.find('.loading-text').addClass('d-none');

                                $('#createFundingAmountModal').modal('hide');

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(() => {

                                    // 🔥 INI YANG PENTING
                                    location.reload();
                                });
                            },

                            error: function(xhr) {

                                submitBtn.prop('disabled', false);
                                submitBtn.find('.default-text').removeClass('d-none');
                                submitBtn.find('.loading-text').addClass('d-none');

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                                });
                            }
                        });
                    });
                });
            </script>
        @endpush

    </div>
@endsection
