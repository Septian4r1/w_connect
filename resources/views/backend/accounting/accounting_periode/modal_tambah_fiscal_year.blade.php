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
