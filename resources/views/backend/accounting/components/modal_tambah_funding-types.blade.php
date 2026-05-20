    {{-- ===================================================== --}}
    {{-- MODAL CREATE FUND TYPE --}}
    {{-- ===================================================== --}}
    <div class="coa-modal" id="createFundTypeModal">

        {{-- OVERLAY --}}
        <div class="coa-modal-overlay closeCreateFundTypeModal"></div>

        {{-- MODAL BOX --}}
        <div class="coa-modal-box">

            {{-- ========================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ========================================= --}}
            <div class="coa-modal-header">

                <div class="coa-modal-title">

                    <div class="coa-modal-icon">
                        <i class="bi bi-tags"></i>
                    </div>

                    <div>

                        <h4>
                            Tambah Funding Type
                        </h4>

                        <p>
                            Tambahkan master jenis funding baru
                        </p>

                    </div>

                </div>

                <button type="button" class="coa-modal-close closeCreateFundTypeModal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            {{-- ========================================= --}}
            {{-- FORM --}}
            {{-- ========================================= --}}
            <form action="{{ route('management.funding-types.store') }}" method="POST">

                @csrf

                <div class="form-grid">

                    {{-- CODE --}}
                    <div class="form-group">

                        <label>
                            Code
                        </label>

                        <input type="text" name="code" class="form-control" placeholder="Contoh: RW-OPS" required>

                        <small class="form-hint">
                            Gunakan kode unik funding
                        </small>

                    </div>

                    {{-- STATUS --}}
                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <div class="coa-auto-status">
                            <i class="bi bi-check-circle-fill"></i>
                            Active
                        </div>

                        <input type="hidden" name="is_active" value="1">

                    </div>

                    {{-- NAME --}}
                    <div class="form-group full">

                        <label>
                            Funding Name
                        </label>

                        <input type="text" name="name" class="form-control" placeholder="Contoh: Dana RW"
                            required>

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-group full">

                        <label>
                            Description
                        </label>

                        <textarea name="description" rows="4" class="form-control" placeholder="Deskripsi funding type..."></textarea>

                    </div>

                </div>

                {{-- ACTION --}}
                <div class="modal-actions">

                    {{-- CANCEL --}}
                    <button type="button" class="coa-action-btn btn-sm cancel-btn closeCreateFundTypeModal">

                        <span class="btn-icon">
                            <i class="bi bi-x-lg"></i>
                        </span>

                        <span>
                            Batal
                        </span>

                    </button>

                    {{-- SUBMIT --}}
                    <button type="submit" class="coa-action-btn btn-sm submit-btn">

                        <span class="btn-icon">
                            <i class="bi bi-check2"></i>
                        </span>

                        <span>
                            Simpan Funding Type
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>
