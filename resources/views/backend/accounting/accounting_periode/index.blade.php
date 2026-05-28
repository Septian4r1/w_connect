@extends('backend.layouts.app')

@section('content')
    <div class="coa-page accounting_periode-page">

        {{-- HEADER --}}
        <div class="coa-header">
            <div>
                <h2>Accounting Period</h2>
                <p>Manajemen periode akuntansi untuk tutup buku dan pembukuan sistem</p>
            </div>

            <div class="coa-actions">

                <button class="coa-btn warning compact" onclick="openFiscalModal()">
                    <b><i class="bi bi-plus-lg"></i></b>
                    Periode Tahunan
                </button>

                {{-- <button class="coa-btn primary compact" onclick="openModal()">
                    <b><i class="bi bi-plus-lg"></i></b>
                    Periode Bulan
                </button> --}}

                <button type="button" class="coa-btn light compact">
                    <b><i class="bi bi-download"></i></b>
                    <span>Export</span>
                </button>

            </div>
        </div>

        {{-- STATS (OPTIONAL nanti dari DB) --}}
        <div class="coa-stats">

            <div class="stat-card total">
                <div class="stat-icon"><i class="bx bx-calendar"></i></div>
                <div>
                    <div class="stat-label">Total</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-desc">Semua periode</div>
                </div>
            </div>

            <div class="stat-card active">
                <div class="stat-icon"><i class="bx bx-check-circle"></i></div>
                <div>
                    <div class="stat-label">OPEN</div>
                    <div class="stat-value">{{ $stats['open'] }}</div>
                    <div class="stat-desc">Periode aktif</div>
                </div>
            </div>

            <div class="stat-card inactive">
                <div class="stat-icon"><i class="bx bx-lock"></i></div>
                <div>
                    <div class="stat-label">CLOSED</div>
                    <div class="stat-value">{{ $stats['closed'] }}</div>
                    <div class="stat-desc">Sudah ditutup</div>
                </div>
            </div>

            <div class="stat-card header">
                <div class="stat-icon"><i class="bx bx-time"></i></div>
                <div>
                    <div class="stat-label">LOCKED</div>
                    <div class="stat-value">{{ $stats['locked'] }}</div>
                    <div class="stat-desc">Terkunci</div>
                </div>
            </div>

        </div>

        {{-- FILTER --}}
        <div class="coa-filter"
            style="
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:14px 16px;
        margin-bottom:16px;
    ">

            <form method="GET" action="{{ route('management.accounting_periode.index') }}"
                style="
            display:flex;
            align-items:center;
            gap:12px;
            width:100%;
        ">

                {{-- SEARCH --}}
                <div class="coa-search-wrapper"
                    style="
                position:relative;
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

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari code / name..."
                        class="coa-search-input"
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

                {{-- STATUS --}}
                <select name="status" class="coa-select"
                    style="
                width:180px;
                height:42px;
                border:1px solid #d1d5db;
                border-radius:12px;
                padding:0 12px;
                font-size:13px;
                background:#fff;
                flex-shrink:0;
            ">

                    <option value="">Semua Status</option>

                    <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>
                        OPEN
                    </option>

                    <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>
                        CLOSED
                    </option>

                    <option value="LOCKED" {{ request('status') == 'LOCKED' ? 'selected' : '' }}>
                        LOCKED
                    </option>

                </select>

                {{-- BUTTON --}}
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
                gap:6px;
                flex-shrink:0;
            ">

                    <i class="bx bx-filter-alt"></i>
                    Filter

                </button>

            </form>
        </div>

        {{-- TABLE --}}
        <div class="coa-table-card">

            <div class="coa-table-toolbar">
                <div>
                    <span class="table-title">Accounting Periods</span>
                    <span class="table-count">({{ $periods->count() ?? 0 }} data)</span>
                </div>
            </div>

            <div class="coa-table-scroll">
                <table class="coa-table">
                    <thead>
                        <tr>
                            <th style="text-align:center;"width="200">Code</th>
                            <th style="text-align:center;">Name</th>
                            <th style="text-align:center;" width="120">Year</th>
                            <th style="text-align:center;" width="120">Month</th>
                            <th style="text-align:center;" width="120">Start</th>
                            <th style="text-align:center;" width="120">End</th>
                            <th style="text-align:center;" width="120">Status</th>
                            <th style="text-align:center;" width="120">Current</th>
                            <th style="text-align:center;" width="120">Transaction</th>
                            <th style="text-align:center;" width="120">Edit</th>
                            <th style="text-align:center;" width="150">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($periods ?? [] as $p)
                            <tr>

                                {{-- CODE --}}
                                <td class="coa-code-cell">{{ $p->code }}</td>

                                {{-- NAME --}}
                                <td>{{ $p->name }}</td>

                                {{-- YEAR --}}
                                <td style="text-align:center;">{{ $p->year }}</td>

                                {{-- MONTH --}}
                                <td style="text-align:center;">{{ $p->month }}</td>

                                {{-- DATE --}}
                                <td style="text-align:center;">{{ $p->start_date->format('Y-m-d') }}</td>
                                <td style="text-align:center;">{{ $p->end_date->format('Y-m-d') }}</td>

                                {{-- STATUS --}}
                                <td style="text-align:center;">
                                    <span
                                        class="coa-status
                                        @if ($p->status === 'OPEN') active
                                        @elseif($p->status === 'CLOSED')
                                            warning
                                        @elseif($p->status === 'LOCKED')
                                            inactive
                                        @elseif($p->status === 'ARCHIVED')
                                            info @endif
                                    ">
                                        {{ $p->status }}
                                    </span>
                                </td>

                                {{-- IS CURRENT --}}
                                <td style="text-align:center;">
                                    @if ($p->is_current)
                                        <span class="coa-status active">YES</span>
                                    @else
                                        <span class="coa-status inactive">NO</span>
                                    @endif
                                </td>

                                {{-- TRANSACTION --}}
                                <td style="text-align:center;">
                                    @if ($p->allow_transaction)
                                        <span class="coa-status active">YES</span>
                                    @else
                                        <span class="coa-status inactive">NO</span>
                                    @endif
                                </td>

                                {{-- EDIT --}}
                                <td style="text-align:center;">
                                    @if ($p->allow_edit)
                                        <span class="coa-status active">YES</span>
                                    @else
                                        <span class="coa-status inactive">NO</span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td>
                                    <div class="coa-action-group">

                                        <button class="coa-icon-btn view view-btn" data-id="{{ $p->id }}"
                                            data-fiscal-code="{{ $p->fiscalYear->code ?? '-' }}"
                                            data-fiscal-name="{{ $p->fiscalYear->name ?? '-' }}"
                                            data-code="{{ $p->code }}" data-name="{{ $p->name }}"
                                            data-year="{{ $p->year }}" data-month="{{ $p->month }}"
                                            data-start_date="{{ \Carbon\Carbon::parse($p->start_date)->format('Y-m-d') }}"
                                            data-end_date="{{ \Carbon\Carbon::parse($p->end_date)->format('Y-m-d') }}"
                                            data-organization_name="{{ $p->organization->name ?? '-' }}"
                                            data-status="{{ $p->status }}" data-current="{{ $p->is_current }}"
                                            data-closed="{{ $p->is_closed }}" data-closed_at="{{ $p->closed_at }}"
                                            data-closed_by_name="{{ $p->closedBy->name ?? '-' }}"
                                            data-locked_at="{{ $p->locked_at }}"
                                            data-locked_by_name="{{ $p->lockedBy->name ?? '-' }}"
                                            data-transaction="{{ $p->allow_transaction }}"
                                            data-edit="{{ $p->allow_edit }}" data-notes="{{ $p->notes }}"
                                            data-created_at="{{ $p->created_at }}"
                                            data-updated_at="{{ $p->updated_at }}">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button class="btn-warning-soft edit-btn" data-id="{{ $p->id }}"
                                            data-code="{{ $p->code }}" data-name="{{ $p->name }}"
                                            data-status="{{ $p->status }}" data-current="{{ $p->is_current }}"
                                            data-transaction="{{ $p->allow_transaction }}"
                                            data-edit="{{ $p->allow_edit }}" data-notes="{{ $p->notes }}">
                                            <i class="bx bx-edit"></i>
                                        </button>

                                        <button class="coa-icon-btn {{ $p->status_class }}" title="{{ $p->status }}"
                                            onclick="changePeriodStatus(
                                             {{ $p->id }},
                                            '{{ $p->status }}',
                                            '{{ \Carbon\Carbon::parse($p->start_date)->format('F Y') }}'
                                        )">
                                            <i class="bx {{ $p->status_icon }}"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    Tidak ada data accounting period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="coa-pagination d-flex justify-content-end mt-3">
                {{ $periods->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>

    @include('backend.accounting.accounting_periode.modal_tambah_periode')
    @include('backend.accounting.accounting_periode.modal_tambah_fiscal_year')
    @include('backend.accounting.accounting_periode.modal_edit_period')


    <div class="modal fade" id="viewPeriodModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-show"></i>
                        Detail Accounting Period
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-sm">

                            <tbody>

                                {{-- <tr>
                                    <th style="width:200px;">ID</th>
                                    <td id="view_id"></td>
                                </tr> --}}

                                <tr>
                                    <th>Fiscal Code</th>
                                    <td id="view_fiscal_year_code"></td>
                                </tr>

                                <tr>
                                    <th>Fiscal Name</th>
                                    <td id="view_fiscal_year_name"></td>
                                </tr>
                                <tr>
                                    <th>Periode Code</th>
                                    <td id="view_code"></td>
                                </tr>

                                <tr>
                                    <th>Name</th>
                                    <td id="view_name"></td>
                                </tr>

                                <tr>
                                    <th>Year</th>
                                    <td id="view_year"></td>
                                </tr>

                                <tr>
                                    <th>Month</th>
                                    <td id="view_month"></td>
                                </tr>

                                <tr>
                                    <th>Start Date</th>
                                    <td id="view_start_date"></td>
                                </tr>

                                <tr>
                                    <th>End Date</th>
                                    <td id="view_end_date"></td>
                                </tr>

                                <tr>
                                    <th>Organization ID</th>
                                    <td id="view_organization_id"></td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td id="view_status"></td>
                                </tr>

                                <tr>
                                    <th>Is Current</th>
                                    <td id="view_is_current"></td>
                                </tr>

                                <tr>
                                    <th>Is Closed</th>
                                    <td id="view_is_closed"></td>
                                </tr>

                                <tr>
                                    <th>Closed At</th>
                                    <td id="view_closed_at"></td>
                                </tr>

                                <tr>
                                    <th>Closed By</th>
                                    <td id="view_closed_by"></td>
                                </tr>

                                <tr>
                                    <th>Locked At</th>
                                    <td id="view_locked_at"></td>
                                </tr>

                                <tr>
                                    <th>Locked By</th>
                                    <td id="view_locked_by"></td>
                                </tr>

                                <tr>
                                    <th>Allow Transaction</th>
                                    <td id="view_allow_transaction"></td>
                                </tr>

                                <tr>
                                    <th>Allow Edit</th>
                                    <td id="view_allow_edit"></td>
                                </tr>

                                <tr>
                                    <th>Notes</th>
                                    <td id="view_notes"></td>
                                </tr>

                                <tr>
                                    <th>Created At</th>
                                    <td id="view_created_at"></td>
                                </tr>

                                <tr>
                                    <th>Updated At</th>
                                    <td id="view_updated_at"></td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>


    @include('backend.accounting.accounting_periode.script_accounting_periode')

    @include('backend.accounting.style_accounting')
@endsection
