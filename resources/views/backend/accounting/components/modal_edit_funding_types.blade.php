    {{-- ===================================================== --}}
    {{-- MODAL EDIT FUND TYPE --}}
    {{-- ===================================================== --}}
    <div class="coa-modal" id="editFundTypeModal">

        {{-- OVERLAY --}}
        <div class="coa-modal-overlay closeEditFundTypeModal"></div>

        {{-- MODAL BOX --}}
        <div class="coa-modal-box">

            {{-- ========================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ========================================= --}}
            <div class="coa-modal-header">

                <div class="coa-modal-title">

                    <div class="coa-modal-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>

                        <h4>
                            Edit Funding Type
                        </h4>

                        <p>
                            Update data funding type
                        </p>

                    </div>

                </div>

                <button type="button" class="coa-modal-close closeEditFundTypeModal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            {{-- ========================================= --}}
            {{-- FORM --}}
            {{-- ========================================= --}}
            <form id="editFundTypeForm" method="POST">

                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- CODE --}}
                    <div class="form-group">

                        <label>
                            Code
                        </label>

                        <input type="text" name="code" id="edit_code" class="form-control"
                            placeholder="Contoh: RW-OPS" required>

                        <small class="form-hint">
                            Gunakan kode unik funding
                        </small>

                    </div>

                    {{-- STATUS --}}
                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <select name="is_active" id="edit_is_active" class="form-select">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                    </div>

                    {{-- NAME --}}
                    <div class="form-group full">

                        <label>
                            Funding Name
                        </label>

                        <input type="text" name="name" id="edit_name" class="form-control"
                            placeholder="Contoh: Dana RW" required>

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-group full">

                        <label>
                            Description
                        </label>

                        <textarea name="description" id="edit_description" rows="4" class="form-control"
                            placeholder="Deskripsi funding type..."></textarea>

                    </div>

                </div>

                {{-- ACTION --}}
                <div class="modal-actions">

                    {{-- CANCEL --}}
                    <button type="button" class="coa-action-btn cancel-btn closeEditFundTypeModal">

                        <span class="btn-icon">
                            <i class="bi bi-x-lg"></i>
                        </span>

                        <span>
                            Batal
                        </span>

                    </button>

                    {{-- SUBMIT --}}
                    <button type="submit" class="coa-action-btn submit-btn">

                        <span class="btn-icon">
                            <i class="bi bi-check2"></i>
                        </span>

                        <span>
                            Update Funding Type
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>
