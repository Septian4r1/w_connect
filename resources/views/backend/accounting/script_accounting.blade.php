@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // =========================================================
            // CREATE MODAL ELEMENTS
            // =========================================================
            const createModal = document.getElementById('coaModal');
            const createForm = createModal?.querySelector('form');

            const openBtn = document.getElementById('btnAddAccount');
            const closeBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelModal');

            const modalTitle = createModal?.querySelector('h3');
            const modalSubtitle = createModal?.querySelector('small');

            const typeSelect = document.getElementById('typeSelect');
            const parentSelect = document.getElementById('parentSelect');

            const balanceInput = document.getElementById('normalBalanceInput');
            const balancePreview = document.getElementById('balancePreview');

            // =========================================================
            // EDIT MODAL ELEMENTS
            // =========================================================
            const editModal = document.getElementById('editAccountModal');
            const editForm = document.getElementById('editAccountForm');

            const editButtons = document.querySelectorAll('.btn-edit-account');

            const editType = document.getElementById('edit_type');
            const editParent = document.getElementById('edit_parent_id');

            const editCode = document.getElementById('edit_code');
            const editName = document.getElementById('edit_name');

            const editBalanceInput = document.getElementById('edit_normal_balance');
            const editBalancePreview = document.getElementById('edit_balance_preview');

            // =========================================================
            // ROUTE CONFIG
            // =========================================================
            const STORE_URL = "{{ route('management.coa.store') }}";

            /*
            |--------------------------------------------------------------------------
            | UPDATE URL TEMPLATE
            |--------------------------------------------------------------------------
            | Route:
            | Route::put('/coa/edit/{id}', ...)
            |
            | Hasil:
            | /accounting/coa/edit/ENCRYPTED_ID
            |--------------------------------------------------------------------------
            */
            const UPDATE_URL_TEMPLATE =
                "{{ route('management.coa.update', ['id' => ':id']) }}";

            // =========================================================
            // COA TREE TOGGLE
            // =========================================================
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.coa-toggle-btn');

                if (!btn) return;

                const row = btn.closest('tr');

                if (!row) return;

                const id = row.dataset.id;

                const children = document.querySelectorAll(
                    `[data-parent="${id}"]`
                );

                if (!children.length) return;

                const isOpen = btn.dataset.state === 'open';

                // =====================================================
                // SHOW / HIDE CHILDREN
                // =====================================================
                children.forEach(child => {

                    child.style.display = isOpen ?
                        'none' :
                        '';

                });

                // =====================================================
                // UPDATE BUTTON STATE
                // =====================================================
                btn.dataset.state = isOpen ?
                    'closed' :
                    'open';

                // =====================================================
                // UPDATE ICON
                // =====================================================
                const icon = btn.querySelector('i');

                if (icon) {

                    icon.className = isOpen ?
                        'bi bi-chevron-right' :
                        'bi bi-chevron-down';

                }

            });

            // =========================================================
            // OPEN CREATE MODAL
            // =========================================================
            function openCreateModal() {

                if (!createModal || !createForm) return;

                // =====================================================
                // RESET FORM
                // =====================================================
                createForm.reset();

                // =====================================================
                // SET FORM ACTION
                // =====================================================
                createForm.action = STORE_URL;

                // =====================================================
                // MODAL TITLE
                // =====================================================
                if (modalTitle) {

                    modalTitle.innerHTML = 'Tambah Account';

                }

                // =====================================================
                // MODAL SUBTITLE
                // =====================================================
                if (modalSubtitle) {

                    modalSubtitle.innerHTML =
                        'Tambah struktur chart of account';

                }

                // =====================================================
                // DEFAULT BALANCE
                // =====================================================
                if (balanceInput) {

                    balanceInput.value = 'debit';

                }

                if (balancePreview) {

                    balancePreview.innerHTML = 'Debit';

                }

                // =====================================================
                // RESET PARENT
                // =====================================================
                if (parentSelect) {

                    parentSelect.value = '';

                }

                // =====================================================
                // SHOW MODAL
                // =====================================================
                createModal.classList.add('active');

                document.body.style.overflow = 'hidden';

                // =====================================================
                // AUTO FOCUS
                // =====================================================
                setTimeout(() => {

                    createForm.querySelector(
                        'input[name="code"]'
                    )?.focus();

                }, 150);

            }

            // =========================================================
            // CLOSE CREATE MODAL
            // =========================================================
            function closeCreateModal() {

                if (!createModal || !createForm) return;

                createModal.classList.remove('active');

                document.body.style.overflow = 'auto';

                createForm.reset();

                // =====================================================
                // RESET BALANCE
                // =====================================================
                if (balanceInput) {

                    balanceInput.value = 'debit';

                }

                if (balancePreview) {

                    balancePreview.innerHTML = 'Debit';

                }

                // =====================================================
                // RESET OPTION FILTER
                // =====================================================
                parentSelect?.querySelectorAll('option')
                    .forEach(option => {

                        option.style.display = '';

                    });

            }

            // =========================================================
            // UPDATE NORMAL BALANCE (CREATE)
            // =========================================================
            function updateNormalBalance() {

                if (!typeSelect) return;

                const type = typeSelect.value?.toLowerCase();

                let balance = 'debit';

                /*
                |--------------------------------------------------------------------------
                | IFRS RULE
                |--------------------------------------------------------------------------
                | Asset + Expense     => Debit
                | Liability + Equity
                | Revenue             => Credit
                |--------------------------------------------------------------------------
                */

                switch (type) {

                    case 'liability':
                    case 'equity':
                    case 'revenue':

                        balance = 'credit';
                        break;

                    default:

                        balance = 'debit';
                        break;
                }

                // =====================================================
                // UPDATE HIDDEN INPUT
                // =====================================================
                if (balanceInput) {

                    balanceInput.value = balance;

                }

                // =====================================================
                // UPDATE UI PREVIEW
                // =====================================================
                if (balancePreview) {

                    balancePreview.innerHTML =
                        balance.charAt(0).toUpperCase() +
                        balance.slice(1);

                }

            }

            // =========================================================
            // FILTER PARENT ACCOUNT (CREATE)
            // =========================================================
            function filterParentAccounts() {

                if (!typeSelect || !parentSelect) return;

                const type = typeSelect.value?.toLowerCase();

                parentSelect.value = '';

                const options =
                    parentSelect.querySelectorAll('option');

                options.forEach(option => {

                    const optionType =
                        option.getAttribute('data-type');

                    // =================================================
                    // ROOT OPTION
                    // =================================================
                    if (!optionType) {

                        option.style.display = '';
                        return;

                    }

                    // =================================================
                    // SAME TYPE ONLY
                    // =================================================
                    if (
                        optionType.toLowerCase() === type
                    ) {

                        option.style.display = '';

                    } else {

                        option.style.display = 'none';

                    }

                });

            }

            // =========================================================
            // CREATE TYPE CHANGE
            // =========================================================
            typeSelect?.addEventListener('change', function() {

                updateNormalBalance();

                filterParentAccounts();

            });

            // =========================================================
            // OPEN CREATE MODAL EVENT
            // =========================================================
            openBtn?.addEventListener(
                'click',
                openCreateModal
            );

            // =========================================================
            // CLOSE CREATE MODAL EVENTS
            // =========================================================
            closeBtn?.addEventListener(
                'click',
                closeCreateModal
            );

            cancelBtn?.addEventListener(
                'click',
                closeCreateModal
            );

            // =========================================================
            // OPEN EDIT MODAL
            // =========================================================
            editButtons.forEach(button => {

                button.addEventListener('click', function() {

                    // =================================================
                    // GET DATA ATTRIBUTE
                    // =================================================
                    const encryptedId = this.dataset.id;

                    const accountId = this.dataset.account_id;

                    const code = this.dataset.code;
                    const name = this.dataset.name;
                    const type = this.dataset.type;

                    const parentId = this.dataset.parent_id;

                    const isHeader = parseInt(
                        this.dataset.is_header
                    );

                    // =================================================
                    // BUILD UPDATE URL
                    // =================================================
                    /*
                    |--------------------------------------------------------------------------
                    | Example Result
                    |--------------------------------------------------------------------------
                    | /accounting/coa/edit/ENCRYPTED_ID
                    |--------------------------------------------------------------------------
                    */
                    editForm.action =
                        UPDATE_URL_TEMPLATE.replace(
                            ':id',
                            encryptedId
                        );

                    // =================================================
                    // SET FORM VALUE
                    // =================================================
                    editCode.value = code;

                    editName.value = name;

                    editType.value = type;

                    editParent.value = parentId ?? '';

                    // =================================================
                    // ACCOUNT MODE
                    // =================================================
                    if (isHeader === 1) {

                        document.getElementById(
                            'edit_mode_header'
                        ).checked = true;

                    } else {

                        document.getElementById(
                            'edit_mode_postable'
                        ).checked = true;

                    }

                    // =================================================
                    // UPDATE NORMAL BALANCE
                    // =================================================
                    updateEditNormalBalance(type);

                    // =================================================
                    // FILTER PARENT
                    // =================================================
                    filterEditParentAccounts(
                        type,
                        accountId
                    );

                    // =================================================
                    // SHOW MODAL
                    // =================================================
                    editModal.classList.add('active');

                    document.body.style.overflow = 'hidden';

                });

            });

            // =========================================================
            // CLOSE EDIT MODAL
            // =========================================================
            function closeEditModal() {

                editModal?.classList.remove('active');

                document.body.style.overflow = 'auto';

                editForm?.reset();

                // =====================================================
                // RESET OPTION FILTER
                // =====================================================
                document.querySelectorAll(
                    '#edit_parent_id option'
                ).forEach(option => {

                    option.style.display = '';

                });

            }

            // =========================================================
            // UPDATE NORMAL BALANCE (EDIT)
            // =========================================================
            function updateEditNormalBalance(type = null) {

                const selectedType =
                    type ??
                    editType?.value?.toLowerCase();

                let balance = 'debit';

                switch (selectedType) {

                    case 'liability':
                    case 'equity':
                    case 'revenue':

                        balance = 'credit';
                        break;

                    default:

                        balance = 'debit';
                        break;

                }

                // =====================================================
                // UPDATE HIDDEN INPUT
                // =====================================================
                editBalanceInput.value = balance;

                // =====================================================
                // UPDATE PREVIEW TEXT
                // =====================================================
                editBalancePreview.innerHTML =
                    balance.charAt(0).toUpperCase() +
                    balance.slice(1);

            }

            // =========================================================
            // FILTER PARENT ACCOUNT (EDIT)
            // =========================================================
            function filterEditParentAccounts(
                type = null,
                currentId = null
            ) {

                const selectedType =
                    type ??
                    editType?.value?.toLowerCase();

                const options = document.querySelectorAll(
                    '#edit_parent_id option'
                );

                options.forEach(option => {

                    const optionType =
                        option.getAttribute('data-type');

                    const optionValue =
                        option.value;

                    // =================================================
                    // ROOT ACCOUNT
                    // =================================================
                    if (!optionType) {

                        option.style.display = '';
                        return;

                    }

                    // =================================================
                    // HIDE SELF ACCOUNT
                    // =================================================
                    if (
                        currentId &&
                        optionValue == currentId
                    ) {

                        option.style.display = 'none';
                        return;

                    }

                    // =================================================
                    // SAME TYPE ONLY
                    // =================================================
                    if (
                        optionType.toLowerCase() ===
                        selectedType
                    ) {

                        option.style.display = '';

                    } else {

                        option.style.display = 'none';

                    }

                });

            }

            // =========================================================
            // EDIT TYPE CHANGE
            // =========================================================
            editType?.addEventListener('change', function() {

                updateEditNormalBalance();

                filterEditParentAccounts();

            });

            // =========================================================
            // CLOSE EDIT MODAL EVENTS
            // =========================================================
            document.querySelectorAll(
                '.coa-close-modal, .close-edit-modal'
            ).forEach(btn => {

                btn.addEventListener('click', function() {

                    closeEditModal();

                });

            });

            // =========================================================
            // CLICK OUTSIDE CLOSE
            // =========================================================
            createModal?.addEventListener('click', function(e) {

                if (e.target === createModal) {

                    closeCreateModal();

                }

            });

            editModal?.addEventListener('click', function(e) {

                if (e.target === editModal) {

                    closeEditModal();

                }

            });

            // =========================================================
            // ESC CLOSE
            // =========================================================
            document.addEventListener('keydown', function(e) {

                if (e.key === 'Escape') {

                    closeCreateModal();

                    closeEditModal();

                }

            });

            // =========================================================
            // CREATE FORM SUBMIT
            // =========================================================
            createForm?.addEventListener('submit', function(e) {

                e.preventDefault();

                const submitBtn =
                    createForm.querySelector(
                        'button[type="submit"]'
                    );

                submitBtn.disabled = true;

                submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            <span>Processing...</span>
        `;

                Swal.fire({

                    title: 'Saving Data',

                    text: 'Sedang menyimpan chart of account...',

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

            // =========================================================
            // EDIT FORM SUBMIT
            // =========================================================
            editForm?.addEventListener('submit', function(e) {

                e.preventDefault();

                const submitBtn =
                    editForm.querySelector(
                        'button[type="submit"]'
                    );

                submitBtn.disabled = true;

                submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            <span>Updating...</span>
        `;

                Swal.fire({

                    title: 'Updating Data',

                    text: 'Sedang mengupdate chart of account...',

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

            // =========================================================
            // SUCCESS ALERT
            // =========================================================
            @if (session('success'))

                Swal.fire({

                    icon: 'success',

                    title: 'Berhasil',

                    text: '{{ session('success') }}',

                    confirmButtonColor: '#4f46e5'

                });
            @endif

            // =========================================================
            // ERROR ALERT
            // =========================================================
            @if ($errors->any())

                Swal.fire({

                    icon: 'error',

                    title: 'Validation Error',

                    html: `
                <div style="text-align:left">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            `,

                    confirmButtonColor: '#dc2626'

                });
            @endif

            // =========================================================
            // INITIALIZE
            // =========================================================
            updateNormalBalance();

            // =========================================================
            // TOGGLE ACCOUNT STATUS (FIXED VERSION)
            // =========================================================
            document.addEventListener('click', async function(e) {

                const button = e.target.closest('.toggle-status-btn');
                if (!button) return;

                e.preventDefault();

                console.log('CLICK OK');

                const encryptedId = button.dataset.id;
                const currentStatus = parseInt(button.dataset.status);
                const accountName = button.dataset.name;

                const url = "{{ route('management.coa.toggle-status', ':id') }}"
                    .replace(':id', encryptedId);

                const result = await Swal.fire({
                    icon: 'warning',
                    title: 'Konfirmasi Status',
                    html: `
            <div style="font-size:14px; line-height:1.6;">
                Apakah yakin ingin mengubah status akun:
                <br><br>
                <b>${accountName}</b>
                <br><br>
                <span style="color:#b91c1c; font-weight:600;">
                    ⚠ Perubahan ini akan mempengaruhi laporan keuangan
                </span>
                <br>
                <small style="color:#6b7280;">
                    Pastikan Anda memahami dampaknya sebelum melanjutkan.
                </small>
            </div>
        `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280'
                });

                if (!result.isConfirmed) return;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    confirmButtonColor: '#4f46e5'
                }).then(() => location.reload());
            });
        });


        // =========================================================
        // VIEW DETAIL COA
        // =========================================================
        document.addEventListener('DOMContentLoaded', function() {

            // =========================================================
            // ELEMENT
            // =========================================================
            const modal = document.getElementById('coaDetailModal');
            const treeContainer = document.getElementById('coaTree');

            const closeBtnFooter = document.getElementById('closeDetailModalFooter');

            // =========================================================
            // OPEN DETAIL COA
            // =========================================================
            document.addEventListener('click', async function(e) {

                const btn = e.target.closest('.coa-icon-btn.view');
                if (!btn) return;

                const id = btn.dataset.id;

                const url = "{{ route('management.coa.detail', ':id') }}"
                    .replace(':id', id);

                // RESET TREE
                treeContainer.innerHTML = '';

                // OPEN MODAL
                modal.classList.add('active');

                Swal.fire({
                    title: 'Memuat Detail COA...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {

                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    // =====================================================
                    // SAFETY CHECK
                    // =====================================================
                    const contentType = res.headers.get('content-type');

                    if (!res.ok) {

                        const text = await res.text();
                        console.error('HTTP ERROR RESPONSE:', text);

                        throw new Error('Server error: ' + res.status);
                    }

                    if (!contentType || !contentType.includes('application/json')) {

                        const text = await res.text();
                        console.error('NON JSON RESPONSE:', text);

                        throw new Error('Response bukan JSON (cek auth / route / middleware)');
                    }

                    const data = await res.json();

                    Swal.close();

                    // =====================================================
                    // BASIC INFO
                    // =====================================================
                    document.getElementById('d_code').innerText = data.code ?? '-';
                    document.getElementById('d_name').innerText = data.name ?? '-';
                    document.getElementById('d_type').innerText = data.type ?? '-';
                    document.getElementById('d_mode').innerText = data.is_header ? 'Header' :
                    'Postable';
                    document.getElementById('d_balance').innerText = data.normal_balance ?? '-';
                    document.getElementById('d_status').innerText = data.is_active ? 'Aktif' :
                        'Nonaktif';
                    document.getElementById('d_level').innerText = data.level ?? '-';
                    document.getElementById('d_path').innerText = data.parent_path ?? '-';

                    document.getElementById('detailTitle').innerText =
                        `${data.code ?? ''} - ${data.name ?? ''}`;

                    // =====================================================
                    // TREE
                    // =====================================================
                    renderTree(data.tree);

                } catch (err) {

                    console.error(err);

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Detail COA',
                        text: err.message
                    });

                    modal.classList.remove('active');
                }
            });

            // =========================================================
            // CLOSE MODAL FUNCTION (REUSABLE)
            // =========================================================
            function closeModal() {
                modal.classList.remove('active');
            }

            // =========================================================
            // CLOSE BUTTON FOOTER
            // =========================================================
            closeBtnFooter?.addEventListener('click', closeModal);

            // =========================================================
            // ESC CLOSE
            // =========================================================
            document.addEventListener('keydown', function(e) {

                if (e.key === 'Escape') {
                    closeModal();
                }
            });

            // =========================================================
            // TREE RENDER
            // =========================================================
            function renderTree(node) {

                if (!node) {
                    treeContainer.innerHTML = '<em>Tidak ada data</em>';
                    return;
                }

                treeContainer.innerHTML = buildTree(node);
            }

            // =========================================================
            // RECURSIVE TREE
            // =========================================================
            function buildTree(node) {

                if (!node) return '';

                let html = `
            <div class="tree-node">
                <div class="tree-item">
                    <span class="tree-code">${node.code}</span>
                    <span class="tree-name">${node.name}</span>
                </div>
        `;

                if (Array.isArray(node.children) && node.children.length > 0) {

                    html += `<div class="tree-children">`;

                    node.children.forEach(child => {
                        html += buildTree(child);
                    });

                    html += `</div>`;
                }

                html += `</div>`;

                return html;
            }

        });
    </script>
@endpush
