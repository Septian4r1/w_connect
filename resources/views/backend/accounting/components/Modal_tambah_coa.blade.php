    {{-- ===================================================== --}}
    {{-- MODAL --}}
    {{-- ===================================================== --}}
    <div class="coa-modal" id="coaModal">

        <div class="coa-modal-overlay"></div>

        <div class="coa-modal-box">

            {{-- HEADER --}}
            <div class="coa-modal-header">

                <div>

                    <h3>
                        Tambah Account
                    </h3>

                    <small>
                        Tambah struktur chart of account
                    </small>

                </div>

            </div>

            {{-- FORM --}}
            <form method="POST" action="" class="coa-modal-form">

                @csrf

                <div class="form-grid">

                    {{-- ========================================= --}}
                    {{-- ACCOUNT TYPE --}}
                    {{-- ========================================= --}}
                    <div class="form-group full">

                        <label>
                            Account Type
                        </label>

                        <select name="type" id="typeSelect" required>

                            <option value="">
                                -- Select Type --
                            </option>

                            <option value="asset">
                                Asset
                            </option>

                            <option value="liability">
                                Liability
                            </option>

                            <option value="equity">
                                Equity
                            </option>

                            <option value="revenue">
                                Revenue
                            </option>

                            <option value="expense">
                                Expense
                            </option>

                        </select>

                    </div>

                    {{-- ========================================= --}}
                    {{-- PARENT --}}
                    {{-- ========================================= --}}
                    <div class="form-group full">

                        <label>
                            Parent Account
                        </label>

                        <select name="parent_id" id="parentSelect">

                            <option value="">
                                -- Root Account --
                            </option>

                            {!! renderCoaOptions($accounts) !!}

                        </select>

                        <small class="form-hint">
                            Parent hanya bisa dipilih dengan tipe akun yang sama.
                        </small>

                    </div>

                    {{-- ========================================= --}}
                    {{-- ACCOUNT MODE --}}
                    {{-- ========================================= --}}
                    <div class="form-group full">

                        <label>
                            Account Mode
                        </label>

                        <div class="coa-account-mode">

                            {{-- HEADER --}}
                            <label class="coa-radio-card">

                                <input type="radio" name="account_mode" value="header" checked>

                                <div class="radio-content">

                                    <div class="radio-icon header">

                                        <i class="bi bi-diagram-3"></i>

                                    </div>

                                    <div class="radio-info">

                                        <strong>
                                            Header Account
                                        </strong>

                                        <small>
                                            Digunakan sebagai grouping /
                                            parent account dan tidak bisa
                                            menerima jurnal transaksi.
                                        </small>

                                    </div>

                                </div>

                            </label>

                            {{-- POSTABLE --}}
                            <label class="coa-radio-card">

                                <input type="radio" name="account_mode" value="postable">

                                <div class="radio-content">

                                    <div class="radio-icon postable">

                                        <i class="bi bi-cash-stack"></i>

                                    </div>

                                    <div class="radio-info">

                                        <strong>
                                            Postable Account
                                        </strong>

                                        <small>
                                            Bisa digunakan untuk transaksi
                                            jurnal dan posting accounting.
                                        </small>

                                    </div>

                                </div>

                            </label>

                        </div>

                    </div>

                    {{-- ========================================= --}}
                    {{-- CODE --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Account Code
                        </label>

                        <input type="text" name="code" placeholder="Example: 1110" required>

                        <small class="form-hint">
                            Gunakan kode unik sesuai struktur COA.
                        </small>

                    </div>

                    {{-- ========================================= --}}
                    {{-- NAME --}}
                    {{-- ========================================= --}}
                    <div class="form-group">

                        <label>
                            Account Name
                        </label>

                        <input type="text" name="name" placeholder="Example: Cash & Bank" required>

                    </div>

                    {{-- ========================================= --}}
                    {{-- NORMAL BALANCE --}}
                    {{-- ========================================= --}}
                    <div class="form-group full">

                        <label>
                            Normal Balance
                        </label>

                        <div class="coa-readonly-field" id="balancePreview">

                            Auto Detect

                        </div>

                        <input type="hidden" name="normal_balance" id="normalBalanceInput">

                        <small class="form-hint">

                            Balance otomatis mengikuti standar IFRS berdasarkan tipe akun.

                        </small>

                    </div>

                </div>

                {{-- ========================================= --}}
                {{-- ACTION --}}
                {{-- ========================================= --}}
                <div class="modal-actions">

                    <button type="button" class="coa-btn light" id="cancelModal">

                        Cancel

                    </button>

                    <button type="submit" class="coa-btn primary">

                        <i class="bi bi-check-lg"></i>

                        <span>
                            Save Account
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>
