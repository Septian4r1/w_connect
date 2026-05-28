<div class="modal fade" id="editPeriodModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content coa-modal-style">

            <form id="editPeriodForm">

                @csrf
                @method('PUT')

                <!-- HEADER -->
                <div class="modal-header coa-modal-header">

                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="coa-modal-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>

                        <div>
                            <h5 class="modal-title mb-0 fw-bold">
                                Edit Accounting Period
                            </h5>
                            <small style="color:#6b7280;">
                                Update status & setting periode akuntansi
                            </small>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body coa-modal-body">

                    <input type="hidden" id="edit_id">

                    {{-- CODE --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-hash"></i> Code
                        </label>
                        <input type="text" id="edit_code" class="form-control" readonly>
                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Name
                        </label>
                        <input type="text" id="edit_name" class="form-control" readonly>
                    </div>

                    <div class="row">
                        {{-- STATUS --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-flag"></i> Status
                            </label>
                            <select id="edit_status" class="form-select">
                                <option value="OPEN">OPEN</option>
                                <option value="CLOSED">CLOSED</option>
                                <option value="LOCKED">LOCKED</option>
                                <option value="ARCHIVED">ARCHIVED</option>
                            </select>
                        </div>

                        {{-- CURRENT --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-star"></i> Current Period
                            </label>
                            <select id="edit_current" class="form-select">
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        {{-- TRANSACTION --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-cash-stack"></i> Allow Transaction
                            </label>
                            <select id="edit_transaction" class="form-select">
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        {{-- EDIT --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bi bi-pencil"></i> Allow Edit
                            </label>
                            <select id="edit_allow_edit" class="form-select">
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                    </div>

                    {{-- NOTES --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-chat-left-text"></i> Notes
                        </label>
                        <textarea id="edit_notes" rows="3" class="form-control" placeholder="Optional notes..."></textarea>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer coa-modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary coa-btn-primary">
                        <i class="bi bi-check-circle"></i>
                        Update Period
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
