@push('scripts')
    <script>
        window.routes = {
            permissionUpdate: "{{ route('management.permissions.update', ':id') }}"
        };
    </script>

    <script>
        $(function() {

            // ===========================
            // INIT DATATABLE MENU
            // ===========================
            let tableEl = $('#menuTable');

            if (tableEl.length) {

                if ($.fn.DataTable.isDataTable('#menuTable')) {
                    tableEl.DataTable().clear().destroy();
                }

                let table = tableEl.DataTable({
                    pageLength: 17,
                    lengthChange: false,
                    ordering: false,
                    info: false,
                    autoWidth: false,
                    scrollX: true,
                    scrollY: "65vh",
                    scrollCollapse: true,
                    responsive: false,
                    language: {
                        search: "",
                        searchPlaceholder: "Cari menu...",
                        emptyTable: "Menu belum tersedia",
                        paginate: {
                            previous: "‹",
                            next: "›"
                        }
                    },
                    dom: '<"d-flex justify-content-between mb-2"f>rt<"d-flex justify-content-end mt-2"p>'
                });

                $('#menuTable_filter input').off().on('keyup', function() {
                    table.search(this.value).draw();
                });
            }

            // ===========================
            // DATATABLE PERMISSION
            // ===========================
            $('#permissionsTable').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: false,
                info: false,
                responsive: false,
                dom: '<"d-flex justify-content-between mb-2"f>rt<"d-flex justify-content-end mt-2"p>',
            });

        });


        // ======================================================
        // GLOBAL STATE
        // ======================================================
        let editMenuId = null;
        let iconList = [];


        // ======================================================
        // HELPER
        // ======================================================
        function parsePermissions(data) {
            if (!data) return [];
            if (typeof data === 'string') {
                try {
                    return JSON.parse(data);
                } catch {
                    return [];
                }
            }
            return data;
        }

        function renderBadge(id, label) {
            return `
    <div class="col-6" id="badge_perm_${id}">
        <div class="badge-soft badge-info-soft w-100 text-start">
            ${label}
        </div>
    </div>`;
        }

        function formatIcon(icon) {
            if (!icon.id) return icon.text;

            return $(`
        <span>
            <i class="${icon.id}" style="margin-right:8px;"></i>
            ${icon.text}
        </span>
    `);
        }


        // ======================================================
        // ICON HANDLER (CREATE & EDIT)
        // ======================================================
        function loadIcons(callback) {

            if (iconList.length > 0) {
                callback();
                return;
            }

            $.getJSON('/icons.json', function(data) {
                iconList = data;
                callback();
            });
        }


        // ================= CREATE =================
        function initIconCreate() {

            let select = $('#iconSelectCreate');
            select.empty().append(`<option value="">-- Pilih Icon --</option>`);

            iconList.forEach(icon => {
                select.append(`<option value="${icon}">${icon}</option>`);
            });

            select.select2({
                dropdownParent: $('#modalCreateMenu'),
                placeholder: 'Pilih icon...',
                width: '100%',
                templateResult: formatIcon,
                templateSelection: formatIcon
            });
        }

        // ================= EDIT =================
        function initIconEdit(selectedIcon = null) {

            let select = $('#iconSelectEdit');

            select.empty().append(`<option value="">-- Pilih Icon --</option>`);

            iconList.forEach(icon => {
                select.append(`<option value="${icon}">${icon}</option>`);
            });

            select.select2({
                dropdownParent: $('#modalEditMenu'),
                placeholder: 'Pilih icon...',
                width: '100%',
                templateResult: formatIcon,
                templateSelection: formatIcon
            });

            if (selectedIcon) {
                select.val(selectedIcon).trigger('change');
            }
        }


        // ======================================================
        // PREVIEW ICON
        // ======================================================
        function renderIconPreview(target, icon) {

            if (!icon) {
                $(target).html('Belum ada icon');
                return;
            }

            $(target).html(`
        <div class="text-center mt-2">
            <div class="mb-2">
                <i class="${icon}" style="font-size:40px;"></i>
            </div>
            <div class="small text-muted">${icon}</div>
        </div>
    `);
        }


        // ======================================================
        // CREATE MENU
        // ======================================================
        $('#modalCreateMenu').on('shown.bs.modal', function() {
            loadIcons(initIconCreate);
        });

        $('#modalCreateMenu').on('show.bs.modal', function() {
            $('#iconSelectCreate').val(null).trigger('change');
            renderIconPreview('#iconPreviewCreate', null);
        });

        $('#iconSelectCreate').on('change', function() {
            renderIconPreview('#iconPreviewCreate', $(this).val());
        });

        $(document).on('submit', '#formCreateMenu', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = form.find('button[type="submit"]');

            btn.prop('disabled', true).text('Menyimpan...');

            let formData = form.serializeArray();
            let permissions = $('#permissionSelectCreate').val() || [];

            permissions.forEach(id => {
                formData.push({
                    name: 'permissions[]',
                    value: id
                });
            });

            $.ajax({
                url: '/management/menu/store',
                type: 'POST',
                data: $.param(formData),
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },

                success: function(res) {
                    btn.prop('disabled', false).text('Simpan');

                    if (!res.success) {
                        Swal.fire('Gagal', res.message, 'error');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 800);
                },

                error: function(xhr) {
                    btn.prop('disabled', false).text('Simpan');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Gagal simpan menu'
                    });
                }
            });
        });


        // ======================================================
        // EDIT MENU (FIX ICON BUG HERE)
        // ======================================================
        $(document).on('click', '.btn-edit-menu', function() {

            let btn = $(this);

            editMenuId = btn.data('id');

            $('#edit_id').val(editMenuId);
            $('#edit_name').val(btn.data('name'));
            $('#edit_route').val(btn.data('route'));
            $('#edit_order').val(btn.data('order'));
            $('#edit_status').val(btn.data('status'));

            let icon = btn.data('icon');

            // 🔥 LOAD ICON + SET VALUE
            loadIcons(function() {
                initIconEdit(icon);
                renderIconPreview('#iconPreviewEdit', icon);
            });

            // ================= PERMISSION =================
            let permissions = parsePermissions(btn.data('permissions'));

            $('.edit-permission').prop('checked', false);
            $('#selectedPermissions').empty();

            if (permissions.length > 0) {
                permissions.forEach(function(id) {

                    let checkbox = $('#perm_' + id);
                    checkbox.prop('checked', true);

                    let label = $('label[for="perm_' + id + '"]').text();

                    $('#selectedPermissions').append(renderBadge(id, label));
                });
            }

            $('#allPermissions').hide();
            $('#btnTambahPermission').text('+ Pilih Permission');

            new bootstrap.Modal(document.getElementById('modalEditMenu')).show();
        });


        // preview edit
        $('#iconSelectEdit').on('change', function() {
            renderIconPreview('#iconPreviewEdit', $(this).val());
        });


        // ======================================================
        // PERMISSION TOGGLE
        // ======================================================
        $('#btnTambahPermission').on('click', function() {

            let container = $('#allPermissions');

            if (container.is(':visible')) {
                container.slideUp(150);
                $(this).text('+ Pilih Permission');
            } else {
                container.slideDown(150);
                $(this).text('Tutup');
            }
        });

        $(document).on('change', '.edit-permission', function() {

            let id = $(this).val();
            let label = $('label[for="perm_' + id + '"]').text();

            if ($(this).is(':checked')) {

                if ($('#badge_perm_' + id).length === 0) {
                    $('#selectedPermissions').append(renderBadge(id, label));
                }

            } else {
                $('#badge_perm_' + id).remove();
            }
        });


        // ======================================================
        // SUBMIT EDIT MENU
        // ======================================================
        $('#formEditMenu').on('submit', function(e) {
            e.preventDefault();

            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            let formData = $(this).serialize();

            $.ajax({
                url: `/management/menu/update/${editMenuId}`,
                type: 'POST',
                data: formData + '&_method=PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function(res) {

                    btn.prop('disabled', false).text('Update');

                    if (!res.success) {
                        Swal.fire('Gagal', res.message, 'error');
                        return;
                    }

                    $('#modalEditMenu').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?t=' + Date.now();
                    }, 800);
                },

                error: function(xhr) {

                    btn.prop('disabled', false).text('Update');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        });


        // ======================================================
        // EDIT PERMISSION
        // ======================================================
        $(document).on('click', '.btn-edit-permissions', function() {

            $('#edit_permission_id').val($(this).data('id'));
            $('#edit_permission_name').val($(this).data('name'));

            $('#modalEditPermission').modal('show');
        });

        $('#formEditPermission').on('submit', function(e) {
            e.preventDefault();

            let id = $('#edit_permission_id').val();
            let name = $('#edit_permission_name').val();

            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            let url = window.routes.permissionUpdate.replace(':id', id);

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: "PUT",
                    name: name
                },

                success: function(res) {

                    btn.prop('disabled', false).text('Update');

                    if (!res.success) {
                        Swal.fire('Gagal', res.message, 'error');
                        return;
                    }

                    $('#modalEditPermission').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 1200);
                },

                error: function(xhr) {

                    btn.prop('disabled', false).text('Update');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        });


        // ======================================================
        // CREATE PERMISSION
        // ======================================================
        $('#formCreatePermission').on('submit', function(e) {
            e.preventDefault();

            let name = $('#permission_name').val().trim();

            if (!name) {
                Swal.fire('Oops', 'Nama permission wajib diisi', 'warning');
                return;
            }

            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: "{{ route('management.permissions.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name
                },

                success: function(res) {

                    btn.prop('disabled', false).text('Simpan');

                    if (!res.success) {
                        Swal.fire('Gagal', res.message, 'error');
                        return;
                    }

                    $('#modalCreatePermissions').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 1200,
                        showConfirmButton: false
                    });

                    setTimeout(() => location.reload(), 1200);
                },

                error: function(err) {

                    btn.prop('disabled', false).text('Simpan');

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        });


        // ======================================================
        // AUTO FORMAT PERMISSION
        // ======================================================
        $('#permission_name').on('input', function() {
            let val = $(this).val()
                .toLowerCase()
                .replace(/\s+/g, '.')
                .replace(/[^a-z0-9.]/g, '');

            $(this).val(val);
        });


        // ======================================================
        // DELETE PERMISSION
        // ======================================================
        $(document).on('click', '.btn-delete-permission', function() {

            let id = $(this).data('id');
            let name = $(this).data('name');
            let row = $(this).closest('tr');

            Swal.fire({
                title: 'Yakin hapus?',
                html: `<div style="margin-top:10px;">
            <div style="font-size:14px;color:#666;margin-bottom:10px;">
                Permission berikut akan dihapus permanen:
            </div>
            <span style="padding:6px 12px;border-radius:999px;background:#ffe2e2;color:#d33;">
                ${name}
            </span>
        </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.ajax({
                    url: `/management/permissions/${id}`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },

                    success: function(res) {

                        if (!res.success) {
                            Swal.fire('Gagal', res.message, 'error');
                            return;
                        }

                        row.fadeOut(300, function() {
                            $(this).remove();
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });
                    },

                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan',
                            'error');
                    }
                });
            });
        });
    </script>
@endpush
