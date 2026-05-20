@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // =====================================================
            // CREATE MODAL
            // =====================================================
            const createModal = document.getElementById(
                'createFundTypeModal'
            );

            const createForm = createModal?.querySelector('form');

            const openCreateBtn = document.getElementById(
                'openCreateFundTypeModal'
            );

            const createCloseButtons = document.querySelectorAll(
                '.closeCreateFundTypeModal'
            );

            // =====================================================
            // EDIT MODAL
            // =====================================================
            const editModal = document.getElementById(
                'editFundTypeModal'
            );

            const editForm = document.getElementById(
                'editFundTypeForm'
            );

            const editButtons = document.querySelectorAll(
                '.btnEditFundType'
            );

            const editCloseButtons = document.querySelectorAll(
                '.closeEditFundTypeModal'
            );

            // =====================================================
            // OPEN CREATE MODAL
            // =====================================================
            function openCreateModal() {

                if (!createModal || !createForm) return;

                // RESET FORM
                createForm.reset();

                // DEFAULT STATUS
                const statusInput = createForm.querySelector(
                    'input[name="is_active"]'
                );

                if (statusInput) {

                    statusInput.value = 1;

                }

                // SHOW MODAL
                createModal.classList.add('active');

                document.body.style.overflow = 'hidden';

                // AUTO FOCUS
                setTimeout(() => {

                    createForm.querySelector(
                        'input[name="code"]'
                    )?.focus();

                }, 150);

            }

            // =====================================================
            // CLOSE CREATE MODAL
            // =====================================================
            function closeCreateModal() {

                if (!createModal || !createForm) return;

                createModal.classList.remove('active');

                document.body.style.overflow = 'auto';

                // RESET FORM
                createForm.reset();

                // RESET STATUS
                const statusInput = createForm.querySelector(
                    'input[name="is_active"]'
                );

                if (statusInput) {

                    statusInput.value = 1;

                }

                // RESET BUTTON
                const submitBtn = createForm.querySelector(
                    'button[type="submit"]'
                );

                if (submitBtn) {

                    submitBtn.disabled = false;

                    submitBtn.innerHTML = `
                        <span class="btn-icon">
                            <i class="bi bi-check2"></i>
                        </span>

                        <span>
                            Simpan Funding Type
                        </span>
                    `;
                }

            }

            // =====================================================
            // OPEN EDIT MODAL
            // =====================================================
            function openEditModal(button) {

                if (!editModal || !editForm) return;

                // =============================================
                // GET DATA
                // =============================================
                const code = button.dataset.code;

                const name = button.dataset.name;

                const description = button.dataset.description;

                const status = button.dataset.status;

                const updateUrl = button.dataset.updateUrl;

                // =============================================
                // SET FORM ACTION
                // =============================================
                editForm.action = updateUrl;

                // =============================================
                // SET VALUE
                // =============================================
                document.getElementById(
                    'edit_code'
                ).value = code ?? '';

                document.getElementById(
                    'edit_name'
                ).value = name ?? '';

                document.getElementById(
                    'edit_description'
                ).value = description ?? '';

                document.getElementById(
                    'edit_is_active'
                ).value = status ?? 1;

                // =============================================
                // SHOW MODAL
                // =============================================
                editModal.classList.add('active');

                document.body.style.overflow = 'hidden';

                // AUTO FOCUS
                setTimeout(() => {

                    document.getElementById(
                        'edit_code'
                    )?.focus();

                }, 150);

            }

            // =====================================================
            // CLOSE EDIT MODAL
            // =====================================================
            function closeEditModal() {

                if (!editModal || !editForm) return;

                editModal.classList.remove('active');

                document.body.style.overflow = 'auto';

                // RESET FORM
                editForm.reset();

                // RESET BUTTON
                const submitBtn = editForm.querySelector(
                    'button[type="submit"]'
                );

                if (submitBtn) {

                    submitBtn.disabled = false;

                    submitBtn.innerHTML = `
                        <span class="btn-icon">
                            <i class="bi bi-check2"></i>
                        </span>

                        <span>
                            Update Funding Type
                        </span>
                    `;
                }

            }

            // =====================================================
            // OPEN CREATE BUTTON
            // =====================================================
            openCreateBtn?.addEventListener('click', function() {

                openCreateModal();

            });

            // =====================================================
            // CLOSE CREATE BUTTONS
            // =====================================================
            createCloseButtons.forEach(button => {

                button.addEventListener('click', function() {

                    closeCreateModal();

                });

            });

            // =====================================================
            // OPEN EDIT BUTTON
            // =====================================================
            editButtons.forEach(button => {

                button.addEventListener('click', function(e) {

                    e.preventDefault();

                    openEditModal(this);

                });

            });

            // =====================================================
            // CLOSE EDIT BUTTONS
            // =====================================================
            editCloseButtons.forEach(button => {

                button.addEventListener('click', function() {

                    closeEditModal();

                });

            });

            // =====================================================
            // CLICK OUTSIDE CREATE MODAL
            // =====================================================
            createModal?.addEventListener('click', function(e) {

                if (e.target === createModal) {

                    closeCreateModal();

                }

            });

            // =====================================================
            // CLICK OUTSIDE EDIT MODAL
            // =====================================================
            editModal?.addEventListener('click', function(e) {

                if (e.target === editModal) {

                    closeEditModal();

                }

            });

            // =====================================================
            // ESC CLOSE
            // =====================================================
            document.addEventListener('keydown', function(e) {

                if (e.key === 'Escape') {

                    closeCreateModal();

                    closeEditModal();

                }

            });

            // =====================================================
            // CREATE SUBMIT
            // =====================================================
            createForm?.addEventListener('submit', function(e) {

                e.preventDefault();

                const submitBtn = createForm.querySelector(
                    'button[type="submit"]'
                );

                submitBtn.disabled = true;

                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm"></span>
                    <span>Menyimpan...</span>
                `;

                Swal.fire({

                    title: 'Saving Data',

                    text: 'Sedang menyimpan funding type...',

                    allowOutsideClick: false,

                    allowEscapeKey: false,

                    didOpen: () => {

                        Swal.showLoading();

                    }

                });

                setTimeout(() => {

                    createForm.submit();

                }, 800);

            });

            // =====================================================
            // EDIT SUBMIT
            // =====================================================
            editForm?.addEventListener('submit', function(e) {

                e.preventDefault();

                const submitBtn = editForm.querySelector(
                    'button[type="submit"]'
                );

                submitBtn.disabled = true;

                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm"></span>
                    <span>Updating...</span>
                `;

                Swal.fire({

                    title: 'Updating Data',

                    text: 'Sedang mengupdate funding type...',

                    allowOutsideClick: false,

                    allowEscapeKey: false,

                    didOpen: () => {

                        Swal.showLoading();

                    }

                });

                setTimeout(() => {

                    editForm.submit();

                }, 800);

            });

            // =====================================================
            // DELETE FUNDING TYPE
            // =====================================================
            document.querySelectorAll(
                '.formDeleteFundingType'
            ).forEach(form => {

                form.addEventListener('submit', async function(e) {

                    e.preventDefault();

                    // =============================================
                    // GET DATA
                    // =============================================
                    const code = this.dataset.code ?? '-';

                    const name = this.dataset.name ?? '-';

                    const description =
                        this.dataset.description ?? '-';

                    // =============================================
                    // CONFIRM DELETE
                    // =============================================
                    const result = await Swal.fire({

                        icon: 'warning',

                        title: 'Hapus Funding Type?',

                        html: `
                            <div style="
                                text-align:left;
                                font-size:13px;
                                line-height:1.7;
                            ">

                                <div style="
                                    background:#fff7ed;
                                    border:1px solid #fdba74;
                                    padding:14px;
                                    border-radius:12px;
                                    margin-bottom:16px;
                                ">

                                    <div style="
                                        font-weight:700;
                                        color:#9a3412;
                                        margin-bottom:10px;
                                    ">
                                        Detail Funding Type
                                    </div>

                                    <div>
                                        <b>Kode :</b> ${code}
                                    </div>

                                    <div>
                                        <b>Nama :</b> ${name}
                                    </div>

                                    <div>
                                        <b>Keterangan :</b>
                                        <br>
                                        ${description}
                                    </div>

                                </div>

                                <div style="
                                    background:#fef2f2;
                                    border:1px solid #fecaca;
                                    padding:14px;
                                    border-radius:12px;
                                    color:#991b1b;
                                ">

                                    <div style="
                                        font-weight:700;
                                        margin-bottom:8px;
                                    ">
                                        ⚠ Peringatan Penting
                                    </div>

                                    <ul style="
                                        padding-left:18px;
                                        margin:0;
                                    ">
                                        <li>
                                            Funding type yang masih
                                            berkaitan dengan data transaksi,
                                            jurnal, atau laporan keuangan
                                            tidak dapat dihapus.
                                        </li>

                                        <li>
                                            Penghapusan data ini dapat
                                            mempengaruhi laporan keuangan
                                            dan histori transaksi sistem.
                                        </li>

                                        <li>
                                            Pastikan data sudah benar-benar
                                            tidak digunakan sebelum
                                            melanjutkan proses penghapusan.
                                        </li>
                                    </ul>

                                </div>

                            </div>
                        `,

                        showCancelButton: true,

                        confirmButtonText: `
                            Ya, Hapus
                        `,

                        cancelButtonText: `
                            Batal
                        `,

                        confirmButtonColor: '#dc2626',

                        cancelButtonColor: '#6b7280',

                        reverseButtons: true

                    });

                    // =============================================
                    // CANCEL
                    // =============================================
                    if (!result.isConfirmed) {

                        return;

                    }

                    // =============================================
                    // LOADING DELETE
                    // =============================================
                    Swal.fire({

                        title: 'Deleting Data',

                        text: 'Sedang menghapus funding type...',

                        allowOutsideClick: false,

                        allowEscapeKey: false,

                        didOpen: () => {

                            Swal.showLoading();

                        }

                    });

                    // =============================================
                    // SUBMIT DELETE
                    // =============================================
                    setTimeout(() => {

                        form.submit();

                    }, 800);

                });

            });

            // =====================================================
            // SUCCESS ALERT
            // =====================================================
            @if (session('success'))

                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text: '{{ session('success') }}',

                    confirmButtonColor: '#4f46e5'

                });

                closeCreateModal();

                closeEditModal();
            @endif

            // =====================================================
            // ERROR ALERT
            // =====================================================
            @if ($errors->any())

                Swal.fire({

                    icon: 'error',

                    title: 'Validation Error',

                    html: `
                        <div style="text-align:left;">
                            @foreach ($errors->all() as $error)
                                <div>• {{ $error }}</div>
                            @endforeach
                        </div>
                    `,

                    confirmButtonColor: '#dc2626'

                });
            @endif

        });
    </script>
@endpush
