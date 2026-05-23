{{-- ===================================================== --}}
{{-- MODAL EDIT FUND TYPE --}}
{{-- ===================================================== --}}
<div class="coa-modal fund-type-modal" id="editFundTypeModal">

    {{-- OVERLAY --}}
    <div class="coa-modal-overlay closeEditFundTypeModal"></div>

    {{-- MODAL BOX --}}
    <div class="coa-modal-box fund-type-modal-box">

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
                        Update data funding type untuk kebutuhan accounting,
                        operasional RW / RT, dana sosial, kas, donasi,
                        dan transaksi lainnya.
                    </p>

                </div>

            </div>

            {{-- CLOSE --}}
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

            {{-- ========================================= --}}
            {{-- BODY --}}
            {{-- ========================================= --}}
            <div class="fund-type-modal-body">

                <div class="form-grid">

                    {{-- ========================================= --}}
                    {{-- CODE --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Funding Code
                        </label>

                        <input type="text" name="code" id="edit_code" class="form-control"
                            placeholder="Contoh: SOSIAL-RW" required>

                        <small class="form-hint">

                            Gunakan kode unik untuk membedakan jenis dana.
                            Contoh:
                            RW-OPS,
                            RT-SOSIAL,
                            INFRA,
                            DONASI

                        </small>

                    </div>

                    {{-- ========================================= --}}
                    {{-- STATUS --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <select name="is_active" id="edit_is_active" class="form-select form-control">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                        <small class="form-hint">

                            Jika funding dinonaktifkan,
                            maka funding tidak dapat digunakan
                            pada transaksi baru atau mapping accounting.

                        </small>

                    </div>

                    {{-- ========================================= --}}
                    {{-- NAME --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Funding Name
                        </label>

                        <input type="text" name="name" id="edit_name" class="form-control"
                            placeholder="Contoh: Dana Sosial RW" required>

                        <small class="form-hint">

                            Nama funding akan muncul pada:
                            transaksi,
                            laporan,
                            jurnal,
                            mapping akun,
                            dan dashboard accounting.

                        </small>

                    </div>

                    {{-- ========================================= --}}
                    {{-- DESCRIPTION --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Description
                        </label>

                        <textarea name="description" id="edit_description" rows="5" class="form-control"
                            placeholder="Contoh:
Dana digunakan untuk kegiatan sosial, santunan warga, bantuan darurat, dan kebutuhan kemasyarakatan lainnya."></textarea>

                        <small class="form-hint">

                            Isi penjelasan detail fungsi dana agar mudah dipahami
                            oleh admin, bendahara, dan pengurus RW / RT.

                        </small>

                    </div>

                </div>

            </div>

            {{-- ========================================= --}}
            {{-- ACTION --}}
            {{-- ========================================= --}}
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
