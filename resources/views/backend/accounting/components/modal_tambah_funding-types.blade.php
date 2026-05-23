{{-- ===================================================== --}}
{{-- MODAL CREATE FUND TYPE --}}
{{-- ===================================================== --}}
<div class="coa-modal fund-type-modal" id="createFundTypeModal">

    {{-- OVERLAY --}}
    <div class="coa-modal-overlay closeCreateFundTypeModal"></div>

    {{-- MODAL BOX --}}
    <div class="coa-modal-box fund-type-modal-box">

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
                        Tambahkan master jenis funding baru untuk kebutuhan accounting,
                        operasional RW / RT, dana sosial, kas, donasi, dan transaksi lainnya.
                    </p>

                </div>

            </div>

            {{-- CLOSE --}}
            <button type="button" class="coa-modal-close closeCreateFundTypeModal">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        {{-- ========================================= --}}
        {{-- FORM --}}
        {{-- ========================================= --}}
        <form action="{{ route('management.funding-types.store') }}" method="POST">

            @csrf

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

                        <input type="text" name="code" class="form-control" placeholder="Contoh: SOSIAL-RW"
                            required>

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

                        <div class="coa-auto-status">

                            <i class="bi bi-check-circle-fill"></i>

                            Active

                        </div>

                        <small class="form-hint">
                            Funding aktif dapat digunakan pada transaksi dan mapping COA.
                        </small>

                        <input type="hidden" name="is_active" value="1">

                    </div>

                    {{-- ========================================= --}}
                    {{-- NAME --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Funding Name
                        </label>

                        <input type="text" name="name" class="form-control" placeholder="Contoh: Dana Sosial RW"
                            required>

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

                        <textarea name="description" rows="5" class="form-control"
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
