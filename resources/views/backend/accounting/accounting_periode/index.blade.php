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
        <div class="coa-filter">
            <form method="GET" action="{{ route('management.accounting_periode.index') }}">
                <div class="coa-search-wrapper">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="coa-search-input"
                        placeholder="Cari code / name...">
                </div>

                <select class="coa-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="OPEN" {{ request('status') == 'OPEN' ? 'selected' : '' }}>OPEN</option>
                    <option value="CLOSED" {{ request('status') == 'CLOSED' ? 'selected' : '' }}>CLOSED</option>
                    <option value="LOCKED" {{ request('status') == 'LOCKED' ? 'selected' : '' }}>LOCKED</option>
                </select>

                <button type="submit" class="coa-btn light">Filter</button>
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
                                <td>
                                    <span
                                        class="coa-status
                                    {{ $p->status === 'OPEN' ? 'active' : 'inactive' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>

                                {{-- IS CURRENT --}}
                                <td>
                                    @if ($p->is_current)
                                        <span class="coa-status active">YES</span>
                                    @else
                                        <span class="coa-status inactive">NO</span>
                                    @endif
                                </td>

                                {{-- TRANSACTION --}}
                                <td>
                                    @if ($p->allow_transaction)
                                        <span class="coa-status active">YES</span>
                                    @else
                                        <span class="coa-status inactive">NO</span>
                                    @endif
                                </td>

                                {{-- EDIT --}}
                                <td>
                                    @if ($p->allow_edit)
                                        <span class="coa-status active">YES</span>
                                    @else
                                        <span class="coa-status inactive">NO</span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td>
                                    <div class="coa-action-group">

                                        <button class="coa-icon-btn view">
                                            <i class="bx bx-show"></i>
                                        </button>

                                        <button class="coa-icon-btn">
                                            <i class="bx bx-edit"></i>
                                        </button>

                                        <button class="coa-icon-btn danger">
                                            <i class="bx bx-trash"></i>
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

    {{-- ===================================================== --}}
    {{-- MODAL PERIODE --}}
    {{-- ===================================================== --}}
    <div class="coa-modal" id="periodModal">
        <div class="coa-modal-overlay" onclick="closeModal()"></div>

        <div class="coa-modal-box">

            <div class="coa-modal-header">
                <div>
                    <h3>Tambah Accounting Period</h3>
                    <small>Membuat periode akuntansi baru untuk sistem ERP</small>
                </div>
            </div>

            <form id="periodForm" method="POST" action="{{ route('accounting.periods.store') }}"
                class="coa-modal-form">
                @csrf

                <div class="form-grid">

                    {{-- YEAR --}}
                    <div class="form-group">
                        <label>Year</label>
                        <select name="year" required>
                            <option value="">-- Select Year --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- MONTH --}}
                    <div class="form-group">
                        <label>Month</label>
                        <select name="month" required>
                            <option value="">-- Select Month --</option>
                            @foreach ($months as $num => $name)
                                <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ORGANIZATION --}}
                    <div class="form-group full">
                        <label>Organization</label>
                        <select name="organization_id" required>
                            <option value="">-- Select Organization --</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">
                                    {{ $org->code }} - {{ $org->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="form-group full">
                        <label>Status</label>
                        <select disabled>
                            <option>OPEN (System Default)</option>
                        </select>
                    </div>

                </div>

                <div class="modal-actions">
                    <button type="button" class="coa-btn light compact" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="coa-btn primary compact" id="saveBtn">
                        <span id="btnText">
                            <i class="bx bx-check"></i> Save Period
                        </span>

                        <span id="btnLoader" style="display:none;">
                            <i class="bx bx-loader-alt bx-spin"></i> Saving...
                        </span>
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- MODAL CREATE FISCAL YEAR --}}
    {{-- ===================================================== --}}

    <div class="coa-modal" id="fiscalModal">

        {{-- OVERLAY --}}
        <div class="coa-modal-overlay" onclick="closeFiscalModal()"></div>

        {{-- MODAL BOX --}}
        <div class="coa-modal-box">

            {{-- ===================================================== --}}
            {{-- HEADER (MODERN ERP IMPROVED) --}}
            {{-- ===================================================== --}}
            <div class="coa-modal-header">

                <div style="display:flex; align-items:center; gap:14px;">

                    {{-- ICON --}}
                    <div
                        style="
                    width:52px;
                    height:52px;
                    border-radius:16px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    background:linear-gradient(135deg,#4f46e5,#06b6d4);
                    color:#fff;
                    font-size:22px;
                    box-shadow:0 12px 28px rgba(79,70,229,.25);
                    flex-shrink:0;
                ">
                        <i class="bi bi-calendar2-range"></i>
                    </div>

                    {{-- TEXT --}}
                    <div>

                        <h3 style="margin:0; font-weight:800; letter-spacing:.2px;">
                            Tambah Fiscal Year
                        </h3>

                        <small style="color:#6b7280; display:flex; align-items:center; gap:6px;">
                            <i class="bi bi-info-circle"></i>
                            Membuat tahun fiskal untuk sistem akuntansi ERP
                        </small>

                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- FORM --}}
            {{-- ===================================================== --}}
            <form id="fiscalForm" method="POST" action="{{ route('accounting.fiscal.store') }}"
                class="coa-modal-form">

                @csrf

                <div class="form-grid">

                    {{-- YEAR --}}
                    <div class="form-group full">
                        <label>Year</label>
                        <select name="year" required>
                            <option value="">-- Select Year --</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- START DATE --}}
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" required>
                    </div>

                    {{-- END DATE --}}
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" required>
                    </div>

                    {{-- ORGANIZATION --}}
                    <div class="form-group full">
                        <label>Organization</label>
                        <select name="organization_id" required>
                            <option value="">-- Select Organization --</option>
                            @foreach ($organizations as $org)
                                <option value="{{ $org->id }}">
                                    {{ $org->code }} - {{ $org->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NOTES --}}
                    <div class="form-group full">
                        <label>Notes</label>
                        <textarea name="notes" rows="3" placeholder="Optional notes..."></textarea>
                    </div>

                    {{-- STATUS --}}
                    <div class="form-group full">
                        <label>Status</label>

                        <div class="coa-readonly-field"
                            style="
                        background: rgba(34,197,94,.10);
                        border: 1px solid rgba(34,197,94,.20);
                        color:#16a34a;
                        font-weight:400;
                    ">
                            <i class="bi bi-check-circle-fill"></i>
                            OPEN (System Default)
                        </div>

                        <input type="hidden" name="status" value="OPEN">
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="modal-actions">
                    <button type="button" class="coa-btn light compact" onclick="closeFiscalModal()">
                        Cancel
                    </button>
                    <button type="submit" class="coa-btn primary compact" id="saveFiscalBtn">
                        <i class="bi bi-check-lg"></i>
                        <span id="fiscalBtnText">
                            Save Fiscal
                        </span>
                        <span id="fiscalBtnLoader" style="display:none;">
                            <i class="bx bx-loader-alt bx-spin"></i> Saving...
                        </span>
                    </button>
                </div>

            </form>

        </div>

    </div>


    @push('scripts')
        <script>
            // =====================================================
            // MODAL CONTROL
            // =====================================================
            function openFiscalModal() {
                document.getElementById('fiscalModal').classList.add('active');
            }

            function closeFiscalModal() {
                document.getElementById('fiscalModal').classList.remove('active');
            }

            function openModal() {
                document.getElementById('periodModal').classList.add('active');
            }

            function closeModal() {
                document.getElementById('periodModal').classList.remove('active');
            }

            // =====================================================
            // MAIN SCRIPT
            // =====================================================
            document.addEventListener('DOMContentLoaded', function() {

                // =====================================================
                // PERIOD FORM (existing)
                // =====================================================
                const periodForm = document.getElementById('periodForm');

                if (periodForm) {

                    periodForm.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const formData = new FormData(periodForm);

                        const btn = document.getElementById('saveBtn');
                        const btnText = document.getElementById('btnText');
                        const btnLoader = document.getElementById('btnLoader');

                        // loading
                        btn.disabled = true;
                        btnText.style.display = 'none';
                        btnLoader.style.display = 'inline-block';

                        try {

                            const response = await fetch(periodForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            });

                            const text = await response.text();

                            let data;
                            try {
                                data = JSON.parse(text);
                            } catch (err) {
                                console.error("Invalid JSON:", text);
                                throw new Error("Server returned invalid response");
                            }

                            if (!response.ok) {
                                throw new Error(data.message || 'Request failed');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            closeModal();
                            periodForm.reset();

                            setTimeout(() => location.reload(), 800);

                        } catch (err) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: err.message
                            });

                        } finally {

                            btn.disabled = false;
                            btnText.style.display = 'inline-block';
                            btnLoader.style.display = 'none';
                        }
                    });
                }

                // =====================================================
                // FISCAL FORM (NEW CLEAN VERSION)
                // =====================================================
                const fiscalForm = document.getElementById('fiscalForm');

                if (fiscalForm) {

                    const btn = document.getElementById('saveFiscalBtn');
                    const btnText = document.getElementById('fiscalBtnText');
                    const btnLoader = document.getElementById('fiscalBtnLoader');

                    fiscalForm.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const formData = new FormData(fiscalForm);

                        // loading
                        btn.disabled = true;
                        btnText.style.display = 'none';
                        btnLoader.style.display = 'inline-block';

                        try {

                            const response = await fetch(fiscalForm.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            });

                            const text = await response.text();

                            let data;
                            try {
                                data = JSON.parse(text);
                            } catch (err) {
                                console.error("Invalid JSON:", text);
                                throw new Error("Server returned invalid response");
                            }

                            if (!response.ok) {
                                throw new Error(data.message || 'Failed to create fiscal year');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.message || 'Fiscal Year created successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            closeFiscalModal();
                            fiscalForm.reset();

                            setTimeout(() => location.reload(), 800);

                        } catch (err) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: err.message
                            });

                        } finally {

                            btn.disabled = false;
                            btnText.style.display = 'inline-block';
                            btnLoader.style.display = 'none';
                        }
                    });
                }

            });
        </script>
    @endpush

    @include('backend.accounting.style_accounting')
@endsection
