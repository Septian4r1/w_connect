@push('scripts')
    <script>
        // =====================================================
        // GLOBAL CSRF
        // =====================================================
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // =====================================================
        // MODAL CONTROL
        // =====================================================
        function openFiscalModal() {
            document.getElementById('fiscalModal').classList.add('active');
        }

        function closeFiscalModal() {
            document.getElementById('fiscalModal').classList.remove('active');
        }

        function openModal() {
            document.getElementById('periodModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('periodModal').classList.remove('active');
        }

        // =====================================================
        // EDIT PERIOD MODAL (BOOTSTRAP 5 CLEAN VERSION)
        // =====================================================
        // =====================================================
        // EDIT PERIOD MODAL (BOOTSTRAP 5 CLEAN VERSION)
        // =====================================================
        function openEditPeriodModal(data) {

            // =========================
            // FILL FORM
            // =========================
            $('#edit_id').val(data.id ?? '');
            $('#edit_code').val(data.code ?? '');
            $('#edit_name').val(data.name ?? '');

            $('#edit_status').val(data.status ?? 'OPEN');

            const isCurrent = String(data.is_current) === '1';
            const allowTransaction = String(data.allow_transaction) === '1';
            const allowEdit = String(data.allow_edit) === '1';

            $('#edit_current').val(isCurrent ? 1 : 0);
            $('#edit_transaction').val(allowTransaction ? 1 : 0);
            $('#edit_allow_edit').val(allowEdit ? 1 : 0);

            $('#edit_notes').val(data.notes ?? '');

            // =========================
            // BOOTSTRAP MODAL SAFE INIT
            // =========================
            const modalEl = document.getElementById('editPeriodModal');

            let modalInstance = bootstrap.Modal.getInstance(modalEl);

            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: true
                });
            }

            modalInstance.show();
        }

        // =====================================================
        // 🔥 EDIT BUTTON CLICK BINDING (INI YANG KAMU LUPA)
        // =====================================================
        document.addEventListener('DOMContentLoaded', function() {

            $(document).on('click', '.edit-btn', function() {

                openEditPeriodModal({
                    id: $(this).data('id'),
                    code: $(this).data('code'),
                    name: $(this).data('name'),
                    status: $(this).data('status'),
                    is_current: $(this).data('current'),
                    allow_transaction: $(this).data('transaction'),
                    allow_edit: $(this).data('edit'),
                    notes: $(this).data('notes'),
                });

            });

        });

        // =====================================================
        // SUBMIT EDIT FORM (TETAP PUNYA KAMU - TIDAK DIUBAH)
        // =====================================================
        $('#editPeriodForm').on('submit', function(e) {
            e.preventDefault();

            let id = $('#edit_id').val();

            $.ajax({
                url: "{{ route('management.accounting_periode.update_setting', ':id') }}".replace(':id',
                    id),
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    _method: 'PUT',
                    status: $('#edit_status').val(),
                    is_current: $('#edit_current').val(),
                    allow_transaction: $('#edit_transaction').val(),
                    allow_edit: $('#edit_allow_edit').val(),
                    notes: $('#edit_notes').val(),
                },
                success: function(res) {
                    $('#editPeriodModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message || 'Updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 800);
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Gagal update accounting period'
                    });
                }
            });
        });
        // =====================================================
        // CHANGE ACCOUNTING STATUS UI
        // =====================================================
        function changePeriodStatus(id, currentStatus, periodName) {

            currentStatus = currentStatus.toUpperCase();

            const statuses = {
                OPEN: {
                    icon: 'bx-up-arrow-alt',
                    color: '#16a34a',
                    description: `<div class="status-desc"><b>OPEN</b><br>Periode aktif.</div>`
                },
                CLOSED: {
                    icon: 'bx-down-arrow-alt',
                    color: '#d97706',
                    description: `<div class="status-desc"><b>CLOSED</b><br>Menutup buku.</div>`
                },
                LOCKED: {
                    icon: 'bx-lock-alt',
                    color: '#dc2626',
                    description: `<div class="status-desc"><b>LOCKED</b><br>Data dikunci.</div>`
                },
                ARCHIVED: {
                    icon: 'bx-file',
                    color: '#2563eb',
                    description: `<div class="status-desc"><b>ARCHIVED</b><br>${periodName} ke arsip.</div>`
                }
            };

            let html = `<div class="coa-status-list">`;

            Object.keys(statuses).forEach(status => {
                const item = statuses[status];
                const disabled = status === currentStatus;

                html += `
                <button type="button"
                    class="swal-status-btn ${disabled ? 'disabled' : ''}"
                    ${disabled ? 'disabled' : ''}
                    onclick="submitStatus(${id}, '${status}')">

                    <div class="swal-status-header">
                        <i class='bx ${item.icon}' style="color:${item.color}"></i>
                        <span>${status}</span>
                    </div>

                    ${item.description}
                </button>
            `;
            });

            html += `</div>`;

            Swal.fire({
                title: 'Change Accounting Period Status',
                html: html,
                width: 850,
                showConfirmButton: false,
                showCloseButton: true,
            });
        }

        // =====================================================
        // SUBMIT STATUS (FETCH STANDARD)
        // =====================================================
        async function submitStatus(id, status) {

            const confirm = await Swal.fire({
                title: 'Confirm Status Change',
                text: `Change status to ${status}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes Change',
            });

            if (!confirm.isConfirmed) return;

            try {

                let url = "{{ route('accounting.period.change-status', ':id') }}";
                url = url.replace(':id', id);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed update status');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => location.reload(), 800);

            } catch (err) {

                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: err.message
                });

            }
        }

        // =====================================================
        // MAIN DOM READY
        // =====================================================
        document.addEventListener('DOMContentLoaded', function() {

            // =====================================================
            // PERIOD FORM
            // =====================================================
            const periodForm = document.getElementById('periodForm');

            if (periodForm) {
                periodForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(periodForm);

                    const btn = document.getElementById('saveBtn');
                    const btnText = document.getElementById('btnText');
                    const btnLoader = document.getElementById('btnLoader');

                    btn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoader.style.display = 'inline-block';

                    try {
                        const response = await fetch(periodForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Request failed');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        closeModal();
                        periodForm.reset();

                        setTimeout(() => location.reload(), 800);

                    } catch (err) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: err.message
                        });

                    } finally {
                        btn.disabled = false;
                        btnText.style.display = 'inline-block';
                        btnLoader.style.display = 'none';
                    }
                });
            }

            // =====================================================
            // FISCAL FORM
            // =====================================================
            const fiscalForm = document.getElementById('fiscalForm');

            if (fiscalForm) {

                const btn = document.getElementById('saveFiscalBtn');
                const btnText = document.getElementById('fiscalBtnText');
                const btnLoader = document.getElementById('fiscalBtnLoader');

                fiscalForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(fiscalForm);

                    btn.disabled = true;
                    btnText.style.display = 'none';
                    btnLoader.style.display = 'inline-block';

                    try {

                        const response = await fetch(fiscalForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Failed fiscal');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        closeFiscalModal();
                        fiscalForm.reset();

                        setTimeout(() => location.reload(), 800);

                    } catch (err) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: err.message
                        });

                    } finally {
                        btn.disabled = false;
                        btnText.style.display = 'inline-block';
                        btnLoader.style.display = 'none';
                    }
                });
            }

            // =====================================================
            // VIEW PERIOD MODAL
            // =====================================================
            // =====================================================
            // VIEW PERIOD MODAL
            // =====================================================
            $(document).on('click', '.view-btn', function() {

                let data = {
                    fiscal_code: $(this).data('fiscal-code'),
                    fiscal_name: $(this).data('fiscal-name'),

                    code: $(this).data('code'),
                    name: $(this).data('name'),
                    year: $(this).data('year'),
                    month: $(this).data('month'),

                    // ⛔ sudah dibersihkan dari jam
                    start_date: $(this).data('start_date'),
                    end_date: $(this).data('end_date'),

                    // 🔥 sudah nama, bukan ID lagi
                    organization_name: $(this).data('organization_name'),

                    status: $(this).data('status'),

                    is_current: $(this).data('current'),
                    is_closed: $(this).data('closed'),

                    closed_at: $(this).data('closed_at'),
                    closed_by_name: $(this).data('closed_by_name'),

                    locked_at: $(this).data('locked_at'),
                    locked_by_name: $(this).data('locked_by_name'),

                    allow_transaction: $(this).data('transaction'),
                    allow_edit: $(this).data('edit'),

                    notes: $(this).data('notes'),
                    created_at: $(this).data('created_at'),
                    updated_at: $(this).data('updated_at'),
                };

                // =========================
                // FISCAL
                // =========================
                $('#view_fiscal_year_code').text(data.fiscal_code ?? '-');
                $('#view_fiscal_year_name').text(data.fiscal_name ?? '-');

                // =========================
                // PERIOD
                // =========================
                $('#view_code').text(data.code ?? '-');
                $('#view_name').text(data.name ?? '-');
                $('#view_year').text(data.year ?? '-');
                $('#view_month').text(data.month ?? '-');

                // tanggal sudah clean YYYY-MM-DD
                $('#view_start_date').text(data.start_date ?? '-');
                $('#view_end_date').text(data.end_date ?? '-');

                // 🔥 FIX INI
                $('#view_organization_id').text(data.organization_name ?? '-');

                $('#view_status').text(data.status ?? '-');

                $('#view_is_current').text(data.is_current == 1 ? 'YES' : 'NO');
                $('#view_is_closed').text(data.is_closed == 1 ? 'YES' : 'NO');

                $('#view_closed_at').text(data.closed_at ?? '-');
                $('#view_closed_by').text(data.closed_by_name ?? '-');

                $('#view_locked_at').text(data.locked_at ?? '-');
                $('#view_locked_by').text(data.locked_by_name ?? '-');

                $('#view_allow_transaction').text(data.allow_transaction == 1 ? 'YES' : 'NO');
                $('#view_allow_edit').text(data.allow_edit == 1 ? 'YES' : 'NO');

                $('#view_notes').text(data.notes ?? '-');
                $('#view_created_at').text(data.created_at ?? '-');
                $('#view_updated_at').text(data.updated_at ?? '-');

                const modal = new bootstrap.Modal(document.getElementById('viewPeriodModal'));
                modal.show();
            });

        });
    </script>
@endpush
