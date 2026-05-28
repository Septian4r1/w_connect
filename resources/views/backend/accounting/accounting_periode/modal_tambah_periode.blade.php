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
