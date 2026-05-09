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
                pageLength: 5,
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

                    if (res.status !== 'success') {
                        showError(res.message || 'Gagal menyimpan');
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
    </script>
@endpush
