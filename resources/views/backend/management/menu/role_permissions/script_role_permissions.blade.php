@push('scripts')
    <script>
        console.log('JS READY');

        $(function() {

            // =====================================
            // STATE (MENYIMPAN PERUBAHAN SEMENTARA)
            // =====================================
            let isDirty = false; // apakah ada perubahan

            // ===============================
            // LOAD TREE
            // ===============================
            $('#selectRole').on('change', function() {

                let roleId = $(this).val();

                if (!roleId) {
                    $('#permissionTreeBody').html('');
                    return;
                }

                // 🔥 loading sederhana
                $('#permissionTreeBody').html(
                    '<tr><td colspan="5" class="text-center">Loading...</td></tr>');

                $.get(`/management/role-permissions/tree/${roleId}`)
                    .done(function(res) {

                        let html = renderMenuTree(res.menus || [], res.rolePermissions || []);
                        $('#permissionTreeBody').html(html);

                        // reset state
                        isDirty = false;
                        $('#unsavedBadge').addClass('d-none');

                    })
                    .fail(function() {
                        $('#permissionTreeBody').html(
                            '<tr><td colspan="5" class="text-danger text-center">Gagal load data</td></tr>'
                        );
                    });

            });


            // ===============================
            // RENDER TREE
            // ===============================
            function renderMenuTree(menus, rolePermissions, level = 0, parentId = null) {

                let html = '';

                menus.forEach(menu => {

                    let children = menu.children_recursive || [];
                    let permissions = menu.permissions || [];
                    let indent = level * 25;
                    let hasChild = children.length > 0;

                    html += `
                <tr
                    data-id="${menu.id}"
                    data-parent="${parentId ?? ''}"
                    class="${parentId ? 'child-row d-none' : ''}"
                >
                    <td style="padding-left:${indent}px">

                        ${hasChild
                            ? `<span class="toggle-menu me-1" style="cursor:pointer">▶</span>`
                            : '<span style="display:inline-block;width:14px"></span>'
                        }

                        ${hasChild ? '📁' : '📄'}

                        <span class="${hasChild ? 'fw-bold' : ''}">
                            ${menu.name}
                        </span>
                    </td>

                    <td>${menu.route ?? '-'}</td>
                    <td>${menu.icon ?? '-'}</td>

                    <td colspan="2">
                        ${renderPermissions(permissions, rolePermissions)}
                    </td>
                </tr>
                `;

                    if (hasChild) {
                        html += renderMenuTree(children, rolePermissions, level + 1, menu.id);
                    }

                });

                return html;
            }


            // ===============================
            // COLLAPSE / EXPAND
            // ===============================
            $(document).on('click', '.toggle-menu', function() {

                let tr = $(this).closest('tr');
                let id = tr.data('id');

                let isOpen = $(this).hasClass('open');

                if (isOpen) {
                    $(this).removeClass('open').text('▶');
                    hideChildren(id);
                } else {
                    $(this).addClass('open').text('▼');
                    showChildren(id);
                }
            });

            function showChildren(parentId) {
                $(`tr[data-parent="${parentId}"]`).removeClass('d-none');
            }

            function hideChildren(parentId) {
                $(`tr[data-parent="${parentId}"]`).each(function() {

                    $(this).addClass('d-none');

                    let childId = $(this).data('id');
                    hideChildren(childId);

                    $(this).find('.toggle-menu')
                        .removeClass('open')
                        .text('▶');
                });
            }


            // ===============================
            // SEARCH (CUSTOM)
            // ===============================
            $('#menuSearch').on('keyup', function() {

                let keyword = $(this).val().toLowerCase();

                if (!keyword) {
                    $('tbody tr').show();
                    $('.child-row').addClass('d-none');
                    $('.toggle-menu').removeClass('open').text('▶');
                    return;
                }

                $('tbody tr').each(function() {

                    let text = $(this).text().toLowerCase();

                    if (text.includes(keyword)) {
                        $(this).show();
                        showParents($(this));
                    } else {
                        $(this).hide();
                    }

                });

            });

            function showParents(row) {

                let parentId = row.data('parent');

                if (parentId) {
                    let parentRow = $(`tr[data-id="${parentId}"]`);

                    parentRow.show();

                    parentRow.find('.toggle-menu')
                        .addClass('open')
                        .text('▼');

                    showParents(parentRow);
                }
            }


            // ===============================
            // RENDER PERMISSION (UPDATED 🔥)
            // ===============================
            function renderPermissions(perms, rolePermissions) {

                if (!perms.length) {
                    return '<span class="text-muted small">No Permission</span>';
                }

                let grouped = {};

                perms.forEach(p => {
                    let key = p.name.split('.')[0];
                    if (!grouped[key]) grouped[key] = [];
                    grouped[key].push(p);
                });

                let html = '';

                Object.keys(grouped).forEach(group => {

                    html += `<div class="mb-1"><strong class="text-dark small">${group}</strong></div>`;
                    html += `<div class="d-flex flex-wrap gap-2 mb-2">`;

                    grouped[group].forEach(p => {

                        let checked = rolePermissions.includes(p.id) ? 'checked' : '';

                        html += `
                    <label class="form-check form-check-inline small">
                        <input type="checkbox"
                            class="form-check-input permission-checkbox"
                            data-permission-id="${p.id}"
                            data-permission-name="${p.name}"
                            ${checked}>
                        <span class="form-check-label">
                            ${p.name.split('.').pop()}
                        </span>
                    </label>
                    `;
                    });

                    html += `</div>`;
                });

                return html;
            }


            // ===============================
            // TRACK PERUBAHAN
            // ===============================
            $(document).on('change', '.permission-checkbox', function() {

                isDirty = true;

                $('#unsavedBadge').removeClass('d-none');
            });


            // ===============================
            // SAVE BULK (UPDATED 🔥)
            // ===============================
            $('#btnSavePermissions').on('click', function() {

                let roleId = $('#selectRole').val();

                if (!roleId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Pilih role terlebih dahulu'
                    });
                    return;
                }

                let permissions = [];

                // ambil semua permission yg dicentang
                $('.permission-checkbox:checked').each(function() {

                    let permissionId = $(this).data('permission-id');

                    if (permissionId) {
                        permissions.push(parseInt(permissionId));
                    }
                });

                // 🔥 hapus duplicate
                permissions = [...new Set(permissions)];

                console.log('ROLE ID:', roleId);
                console.log('PERMISSIONS:', permissions);

                let btn = $(this);

                btn.prop('disabled', true).text('Menyimpan...');

                $.post(`/management/role-permissions/sync`, {
                        _token: '{{ csrf_token() }}',
                        role_id: roleId,
                        permissions: permissions
                    })
                    .done(function(res) {

                        if (res.status) {

                            isDirty = false;
                            $('#unsavedBadge').addClass('d-none');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message || 'Permissions berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message || 'Terjadi kesalahan'
                            });
                        }

                    })
                    .fail(function(xhr) {

                        let msg = 'Gagal menyimpan';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }

                        console.error(xhr);

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });

                    })
                    .always(function() {
                        btn.prop('disabled', false).text('💾 Simpan');
                    });

            });


            // ===============================
            // CHECK ALL PER ROW
            // ===============================
            $(document).on('change', '.check-all', function() {

                let isChecked = $(this).is(':checked');

                $(this).closest('tr')
                    .find('.permission-checkbox')
                    .prop('checked', isChecked)
                    .trigger('change');

            });

        });
    </script>
@endpush
