@push('scripts')
    <script>
        // ===========================
        // GLOBAL SWEETALERT HELPERS
        // ===========================
        function showLoading(text = 'Memproses...') {
            Swal.fire({
                title: text,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
                customClass: {
                    popup: 'swal-popup-mini'
                },
                buttonsStyling: false
            });
        }

        function showSuccess(msg = 'Berhasil') {
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: msg,
                timer: 1200,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal-popup-mini'
                }
            });
        }

        function showError(msg = 'Terjadi kesalahan') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg,
                customClass: {
                    popup: 'swal-popup-mini'
                }
            });
        }


        // ===========================
        // MAIN INIT
        // ===========================
        $(window).on('load', function() {

            // ===========================
            // PREVENT DOUBLE INIT
            // ===========================
            if ($.fn.DataTable.isDataTable('#rolesTable')) {
                $('#rolesTable').DataTable().clear().destroy();
            }

            if ($.fn.DataTable.isDataTable('#permissionTable')) {
                $('#permissionTable').DataTable().clear().destroy();
            }

            // ===========================
            // ROLES TABLE
            // ===========================
            $('#rolesTable').DataTable({
                pageLength: 5,
                lengthChange: false,
                ordering: false,
                info: false,
                destroy: true,
                autoWidth: true,
                language: {
                    search: "",
                    searchPlaceholder: "Cari role...",
                    emptyTable: "Data role belum tersedia",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center mb-2"f>rt<"d-flex justify-content-end mt-2"p>'
            });

            // ===========================
            // PERMISSION TABLE
            // ===========================
            $('#permissionTable').DataTable({
                pageLength: 15,
                lengthChange: false,
                ordering: true,
                autoWidth: false,
                scrollX: true,
                destroy: true,
                columnDefs: [{
                    orderable: false,
                    targets: [1, 9]
                }],
                language: {
                    search: "",
                    searchPlaceholder: "Cari data...",
                    emptyTable: "Data pengurus belum tersedia",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },
                dom: '<"d-flex justify-content-between align-items-center mb-2"f>rt<"d-flex justify-content-end mt-2"p>'
            });

        });


        // ===========================
        // CREATE ROLE
        // ===========================
        $(document).on('submit', '#formCreateRole', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = form.find('button[type="submit"]');

            btn.prop('disabled', true);
            showLoading('Menyimpan role...');

            $.ajax({
                url: "{{ route('management.roles.store') }}",
                type: "POST",
                data: form.serialize(),

                success: function(res) {
                    btn.prop('disabled', false);

                    if (res.status !== 'success') {
                        showError(res.message || 'Gagal menyimpan');
                        return;
                    }

                    showSuccess(res.message);

                    const modalEl = document.getElementById('modalCreateRole');
                    bootstrap.Modal.getInstance(modalEl).hide();

                    form[0].reset();

                    setTimeout(() => location.reload(), 800);
                },

                error: function(xhr) {
                    btn.prop('disabled', false);

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        showError(firstError);
                    } else {
                        showError('Terjadi kesalahan server');
                    }
                }
            });
        });


        // ===========================
        // EDIT ROLE
        // ===========================
        $(document).on('click', '.btn-edit-role', function() {

            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Edit Role',
                width: '400px',
                html: `
                <label class="swal-label">Nama Role</label>
                <input id="editRoleName"
                    class="swal2-input swal-input-mini"
                    value="${name}">
            `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal-popup-mini'
                },
                buttonsStyling: false,
                preConfirm: () => document.getElementById('editRoleName').value

            }).then((result) => {

                if (!result.isConfirmed) return;

                showLoading('Mengupdate role...');

                $.ajax({
                    url: "/management/roles/update/" + id,
                    type: "PUT",
                    data: {
                        name: result.value
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    success: function(res) {
                        if (res.status !== 'success') {
                            showError(res.message);
                            return;
                        }

                        showSuccess(res.message);
                        setTimeout(() => location.reload(), 800);
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let firstError = Object.values(errors)[0][0];
                            showError(firstError);
                        } else {
                            showError('Gagal update role');
                        }
                    }
                });

            });

        });


        // ===========================
        // SELECT2 MODAL FIX
        // ===========================
        $(document).ready(function() {

            $('#modalTambahPengurus').on('shown.bs.modal', function() {
                $('#selectUser').select2({
                    dropdownParent: $('#modalTambahPengurus'),
                    placeholder: "Cari nama / NIK...",
                    allowClear: true,
                    width: '100%'
                });
            });

            $('#modalTambahPengurus').on('hidden.bs.modal', function() {
                $('#selectUser').val(null).trigger('change');
            });

        });


        // ===========================
        // SUBMIT TAMBAH PENGURUS
        // ===========================
        $(document).on('submit', '#formTambahPengurus', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = form.find('button[type="submit"]');

            btn.prop('disabled', true);
            showLoading('Menyimpan akses user...');

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),

                success: function(res) {
                    btn.prop('disabled', false);

                    if (!res || res.status !== 'success') {
                        showError(res?.message || 'Gagal menyimpan');
                        return;
                    }

                    showSuccess(res.message);

                    const modalEl = document.getElementById('modalTambahPengurus');
                    bootstrap.Modal.getInstance(modalEl).hide();

                    form[0].reset();
                    $('#selectUser').val(null).trigger('change');

                    setTimeout(() => location.reload(), 800);
                },

                error: function(xhr) {
                    btn.prop('disabled', false);

                    let response = xhr.responseJSON || {};

                    if (xhr.status === 422 && response.errors) {
                        let firstError = Object.values(response.errors)[0]?.[0];
                        if (firstError) {
                            showError(firstError);
                            return;
                        }
                    }

                    if (response.message) {
                        showError(response.message);
                        return;
                    }

                    showError('Terjadi kesalahan server');
                }
            });
        });


        $(document).on('click', '.btn-delete-pengurus', function() {

            const btn = $(this);

            const id = btn.data('id');
            const user = btn.data('user') || '-';
            const role = btn.data('role') || '-';
            const org = btn.data('org') || '-';
            const rw = btn.data('rw') || '-';
            const rt = btn.data('rt') || '-';

            // 🔥 BUILD ROUTE MANUAL (INI KUNCI FIX)
            const url = `/management/pengurus-wilayah/delete/${id}`;

            Swal.fire({
                title: 'Konfirmasi Hapus',
                icon: 'warning',
                width: 520,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                focusCancel: true,

                html: `
            <div style="text-align:left;font-size:13px;line-height:1.6">

                <div style="text-align:center;font-weight:600;margin-bottom:10px">
                    Anda akan menghapus data berikut:
                </div>

                <div style="background:#f8fafc;border:1px solid #e5e7eb;padding:12px;border-radius:10px">

                    <div>👤 <b>${user}</b></div>
                    <div>🛡 Role: <span style="color:#6366f1">${role}</span></div>
                    <div>🏢 Organisasi: ${org}</div>
                    <div>🏘 RW: ${rw} | 🏠 RT: ${rt}</div>

                </div>

                <div style="margin-top:10px;text-align:center;color:#ef4444;font-weight:600">
                    ⚠ Data tidak dapat dikembalikan
                </div>

            </div>
        `,

                preConfirm: async () => {
                    try {
                        const res = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();

                        if (!res.ok || data.status !== 'success') {
                            throw new Error(data.message || 'Gagal menghapus data');
                        }

                        return data;

                    } catch (err) {
                        Swal.showValidationMessage(
                            `<div style="color:#ef4444">${err.message}</div>`
                        );
                    }
                }

            }).then((result) => {

                if (!result.isConfirmed) return;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Dihapus',
                    text: 'Data pengurus berhasil dihapus',
                    timer: 1200,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    btn.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 500);

            });
        });

        $(document).on('click', '.btn-toggle-status', function() {

            const btn = $(this);

            // ===========================
            // LOCK ACCESS
            // ===========================
            if (btn.hasClass('disabled')) {
                Swal.fire(
                    'Akses Ditolak',
                    'Hanya Super Admin yang bisa mengubah status',
                    'error'
                );
                return;
            }

            const id = btn.data('id');
            const current = btn.data('status');

            const user = btn.data('user') || '-';
            const role = btn.data('role') || '-';
            const org = btn.data('org') || '-';
            const rw = btn.data('rw') || '-';
            const rt = btn.data('rt') || '-';

            // ===========================
            // CONFIRMATION MODAL
            // ===========================
            Swal.fire({
                title: 'Konfirmasi Ubah Status',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#6366f1',

                html: `
            <div style="text-align:left;font-size:13px;line-height:1.6">

                <div style="text-align:center;font-weight:600;margin-bottom:10px">
                    Anda akan mengubah status berikut:
                </div>

                <div style="background:#f8fafc;border:1px solid #e5e7eb;padding:12px;border-radius:10px">

                    <div>👤 <b>${user}</b></div>
                    <div>🛡 Role: ${role}</div>
                    <div>🏢 Organisasi: ${org}</div>
                    <div>🏘 RW: ${rw} | 🏠 RT: ${rt}</div>

                    <div style="margin-top:8px;">
                        📌 Status Saat Ini: <b>${current}</b>
                    </div>

                </div>

            </div>
        `,

                showLoaderOnConfirm: true,

                preConfirm: async () => {
                    try {
                        const res = await fetch(`/management/pengurus-wilayah/toggle-status/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json().catch(() => ({}));

                        if (!res.ok || data.status !== 'success') {
                            throw new Error(data.message || 'Gagal update status');
                        }

                        return data;

                    } catch (err) {
                        Swal.showValidationMessage(
                            `<div style="color:#ef4444">${err.message}</div>`
                        );
                    }
                }

            }).then((result) => {

                if (!result.isConfirmed) return;

                // ===========================
                // SUCCESS ALERT
                // ===========================
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Status berhasil diubah',
                    timer: 1200,
                    showConfirmButton: false
                });

                // ===========================
                // RELOAD FULL PAGE
                // ===========================
                setTimeout(() => {
                    location.reload();
                }, 900);
            });
        });


        $(document).on('click', '.btn-edit-pengurus', function() {

            let $btn = $(this);

            let id = $btn.data('id');
            let status = $btn.data('status');
            let role = $btn.data('role_name');

            let isReadOnly = (status === 'nonaktif' && role !== 'superadmin');

            // ======================
            // SET BASIC VALUES
            // ======================
            $('#edit_id').val(id);
            $('#edit_user_name').val($btn.data('name'));
            $('#edit_email').val($btn.data('email'));
            $('#edit_status').val(status);
            $('#edit_status_text').val(status === 'aktif' ? 'Aktif 🟢' : 'Nonaktif 🔴');

            $('#edit_start_date').val(($btn.data('start_date') || '').substring(0, 10));
            $('#edit_end_date').val(($btn.data('end_date') || '').substring(0, 10));

            // ======================
            // SET SELECT (WAIT DOM READY)
            // ======================
            setTimeout(() => {

                $('#edit_user_id').val($btn.data('user_id')).trigger('change');
                $('#edit_role_id').val($btn.data('role_id')).trigger('change');
                $('#edit_org_id').val($btn.data('org_id')).trigger('change');
                $('#edit_rw_id').val($btn.data('rw_id')).trigger('change');
                $('#edit_rt_id').val($btn.data('rt_id')).trigger('change');

            }, 50);

            // ======================
            // FORM ACTION
            // ======================
            $('#formEditPengurus').attr(
                'action',
                "{{ route('management.roles_akses.update', ':id') }}".replace(':id', id)
            );

            // ======================
            // TITLE MODAL
            // ======================
            if (isReadOnly) {
                $('#modalEditPengurus .modal-title').text('Detail Pengurus (Read Only)');
            } else {
                $('#modalEditPengurus .modal-title').text('Edit Pengurus');
            }

            // ======================
            // APPLY READONLY MODE
            // ======================
            toggleEditReadonly(isReadOnly);

            // ======================
            // SHOW MODAL
            // ======================
            $('#modalEditPengurus').modal('show');
        });


        function toggleEditReadonly(state) {

            let $modal = $('#modalEditPengurus');

            if (state) {

                // disable semua input form
                $modal.find('input, select, textarea').prop('disabled', true);

                // refresh select2 kalau ada
                $modal.find('select').trigger('change.select2');

                // hide tombol submit
                $modal.find('button[type="submit"]').hide();

            } else {

                // enable kembali
                $modal.find('input, select, textarea').prop('disabled', false);

                // refresh select2
                $modal.find('select').trigger('change.select2');

                // tampilkan tombol submit
                $modal.find('button[type="submit"]').show();
            }
        }

        // ===========================
        // UPDATE PENGURUS (AJAX)
        // ===========================
        $(document).on('submit', '#formEditPengurus', function(e) {

            e.preventDefault();

            const form = $(this);

            const btn = form.find('button[type="submit"]');

            btn.prop('disabled', true);

            showLoading('Mengupdate data pengurus...');

            $.ajax({

                url: form.attr('action'),

                type: 'POST',

                data: form.serialize(),

                success: function(res) {

                    btn.prop('disabled', false);

                    if (!res || res.status !== 'success') {

                        showError(res?.message || 'Gagal update data');
                        return;
                    }

                    // tutup modal
                    $('#modalEditPengurus').modal('hide');

                    // success swal
                    showSuccess(res.message);

                    // reload table
                    setTimeout(() => {
                        location.reload();
                    }, 1200);
                },

                error: function(xhr) {

                    btn.prop('disabled', false);

                    let response = xhr.responseJSON || {};

                    // validation
                    if (xhr.status === 422 && response.errors) {

                        let firstError = Object.values(response.errors)[0]?.[0];

                        showError(firstError || 'Validasi gagal');

                        return;
                    }

                    // server error
                    showError(response.message || 'Terjadi kesalahan server');
                }
            });
        });
    </script>
@endpush
