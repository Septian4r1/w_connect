        {{-- ===================================================== --}}
        {{-- MODAL EDIT FUND ACCOUNT LINK --}}
        {{-- ===================================================== --}}

        <div class="coa-modal" id="createFundMappingModal">

            {{-- OVERLAY --}}
            <div class="coa-modal-overlay closeCreateFundMappingModal"></div>

            {{-- MODAL BOX --}}
            <div class="coa-modal-box">

                {{-- ===================================================== --}}
                {{-- HEADER --}}
                {{-- ===================================================== --}}
                <div class="coa-modal-header">

                    <div class="coa-modal-title">

                        <div class="coa-modal-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>

                        <div>
                            <h4>Fund Account Mapping</h4>
                            <p>Mapping COA berdasarkan Asset, Liability, Revenue, dan Expense</p>
                        </div>

                    </div>

                    <button type="button" class="coa-modal-close closeCreateFundMappingModal">
                        <i class="bi bi-x-lg"></i>
                    </button>

                </div>

                {{-- ===================================================== --}}
                {{-- FORM --}}
                {{-- ===================================================== --}}
                <form method="POST" action="" class="coa-modal-body" id="fundMappingForm">

                    @csrf
                    @method('PUT')



                    {{-- ===================================================== --}}
                    {{-- FUND CONFIGURATION --}}
                    {{-- ===================================================== --}}
                    <div class="mapping-section">

                        <div class="section-heading">
                            <div>
                                <h5>Fund Configuration</h5>
                                <p>Pilih fund dan organisasi yang berlaku</p>
                            </div>
                        </div>

                        <div class="row g-4">

                            <input type="hidden" id="editMode" value="create">
                            <input type="hidden" id="editFundTypeId">
                            <input type="hidden" id="editOrganizationId">

                            {{-- FUND TYPE --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">Fund Type</label>

                                <select name="fund_type_id" class="form-control" id="fundTypeSelect" required>

                                    <option value="">-- Pilih Fund Type --</option>

                                    @foreach ($fundTypes as $fund)
                                        <option value="{{ $fund->id }}">
                                            {{ $fund->code }} - {{ $fund->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            {{-- ORGANIZATIONS (REPLACE SCOPES) --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">Organizations</label>

                                <select name="organization_id" class="form-control" id="organizationSelect">

                                    <option value="">-- Pilih Organization --</option>

                                    @foreach ($organizations as $org)
                                        <option value="{{ $org->id }}">
                                            {{ strtoupper($org->type) }} - {{ $org->code }} - {{ $org->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                        {{-- DYNAMIC AREA (optional future filter RT/RW tree) --}}
                        <div id="scopeDynamicArea" class="mt-4"></div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- ASSET --}}
                    {{-- ===================================================== --}}
                    <div class="mapping-section">

                        <div class="mapping-header success">
                            <div class="mapping-icon">
                                <i class="bi bi-wallet2"></i>
                            </div>

                            <div>
                                <h5>Asset Accounts</h5>
                                <p>Pilih akun asset</p>
                            </div>
                        </div>

                        <div class="coa-grid">

                            @foreach ($accounts->flatten() as $coa)
                                @if (strtolower($coa->type) === 'asset' && $coa->is_postable)
                                    <label class="coa-item asset-item">
                                        <input type="checkbox" name="mapping[asset][]" value="{{ $coa->id }}"
                                            data-type="{{ strtolower($coa->type) }}"
                                            data-name="{{ $coa->code }} - {{ $coa->name }}">
                                        <span>{{ $coa->code }} - {{ $coa->name }}</span>
                                    </label>
                                @endif
                            @endforeach

                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold">Default Asset Account</label>
                            <select name="default_asset_id" class="form-control" id="defaultAssetSelect">
                                <option value="">-- Pilih Default Asset --</option>
                            </select>
                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- LIABILITY --}}
                    {{-- ===================================================== --}}
                    <div class="mapping-section">

                        <div class="mapping-header danger">
                            <div class="mapping-icon">
                                <i class="bi bi-bank2"></i>
                            </div>

                            <div>
                                <h5>Liability Accounts</h5>
                            </div>
                        </div>

                        <div class="coa-grid">
                            @foreach ($accounts->flatten() as $coa)
                                @if (strtolower($coa->type) === 'liability' && $coa->is_postable)
                                    <label class="coa-item liability-item">
                                        <input type="checkbox" name="mapping[liability][]" value="{{ $coa->id }}"
                                            data-type="liability"
                                            data-name="{{ $coa->code }} - {{ $coa->name }}">
                                        <span>{{ $coa->code }} - {{ $coa->name }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold">Default Liability Account</label>
                            <select name="default_liability_id" class="form-control" id="defaultLiabilitySelect">
                                <option value="">-- Pilih Default Liability --</option>
                            </select>
                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- REVENUE --}}
                    {{-- ===================================================== --}}
                    <div class="mapping-section">

                        <div class="mapping-header primary">
                            <div class="mapping-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>

                            <div>
                                <h5>Revenue Accounts</h5>
                            </div>
                        </div>

                        <div class="coa-grid">
                            @foreach ($accounts->flatten() as $coa)
                                @if (strtolower($coa->type) === 'revenue' && $coa->is_postable)
                                    <label class="coa-item revenue-item">
                                        <input type="checkbox" name="mapping[revenue][]" value="{{ $coa->id }}"
                                            data-type="revenue" data-name="{{ $coa->code }} - {{ $coa->name }}">
                                        <span>{{ $coa->code }} - {{ $coa->name }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold">Default Revenue Account</label>
                            <select name="default_revenue_id" class="form-control" id="defaultRevenueSelect">
                                <option value="">-- Pilih Default Revenue --</option>
                            </select>
                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- EXPENSE --}}
                    {{-- ===================================================== --}}
                    <div class="mapping-section">

                        <div class="mapping-header warning">
                            <div class="mapping-icon">
                                <i class="bi bi-receipt"></i>
                            </div>

                            <div>
                                <h5>Expense Accounts</h5>
                            </div>
                        </div>

                        <div class="coa-grid">
                            @foreach ($accounts->flatten() as $coa)
                                @if (strtolower($coa->type) === 'expense' && $coa->is_postable)
                                    <label class="coa-item expense-item">
                                        <input type="checkbox" name="mapping[expense][]" value="{{ $coa->id }}"
                                            data-type="expense"
                                            data-name="{{ $coa->code }} - {{ $coa->name }}">
                                        <span>{{ $coa->code }} - {{ $coa->name }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold">Default Expense Account</label>
                            <select name="default_expense_id" class="form-control" id="defaultExpenseSelect">
                                <option value="">-- Pilih Default Expense --</option>
                            </select>
                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- ACTION --}}
                    {{-- ===================================================== --}}
                    <div class="modal-actions">

                        <button type="button" class="coa-action-btn cancel-btn closeCreateFundMappingModal">
                            <i class="bi bi-x-lg"></i>
                            Batal
                        </button>

                        <button type="submit" id="submitMappingBtn" class="coa-action-btn submit-btn">
                            <i class="bi bi-check2"></i>
                            Simpan Mapping
                        </button>

                    </div>

                </form>

            </div>

        </div>
