@push('scripts')
    <script>
        // ===========================
        // GLOBAL AJAX CSRF
        // ===========================
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // ===========================
        // SWEETALERT HELPERS
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
        // DATATABLE BLOCK
        // ===========================
        $(window).on('load', function() {

            if ($.fn.DataTable.isDataTable('#blockTable')) {
                $('#blockTable').DataTable().clear().destroy();
            }

            $('#blockTable').DataTable({
                pageLength: 4,
                lengthChange: false,
                ordering: false,
                autoWidth: false,
                responsive: true,
                destroy: true,
                paging: true,
                searching: true,
                info: false,

                language: {
                    search: "",
                    searchPlaceholder: "Cari block...",
                    emptyTable: "Belum ada data block",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },

                dom: '<"d-flex justify-content-between align-items-center mb-2"f>rt<"d-flex justify-content-end mt-2"p>'
            });
        });


        // ===========================
        // AJAX TAMBAH RT
        // ===========================
        $(document).on('submit', '#modalTambahRT form', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = form.find('button[type="submit"]');

            btn.prop('disabled', true);
            showLoading('Menyimpan RT...');

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),

                success: function(res) {
                    btn.prop('disabled', false);

                    if (res.status !== 'success') {
                        showError(res.message);
                        return;
                    }

                    showSuccess(res.message);

                    const modalEl = document.getElementById('modalTambahRT');
                    bootstrap.Modal.getInstance(modalEl)?.hide();

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
                        showError(xhr.responseJSON?.message || 'Terjadi kesalahan server');
                    }
                }
            });
        });


        // ===========================
        // AJAX TAMBAH BLOCK
        // ===========================
        $(document).on('submit', '#modalTambahBlock form', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = form.find('button[type="submit"]');

            btn.prop('disabled', true);
            showLoading('Menyimpan Block...');

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),

                success: function(res) {
                    btn.prop('disabled', false);

                    if (res.status !== 'success') {
                        showError(res.message);
                        return;
                    }

                    showSuccess(res.message);

                    const modalEl = document.getElementById('modalTambahBlock');
                    bootstrap.Modal.getInstance(modalEl)?.hide();

                    form[0].reset();

                    setTimeout(() => location.reload(), 800);
                },

                error: function(xhr) {
                    btn.prop('disabled', false);

                    const res = xhr.responseJSON;

                    if (xhr.status === 422) {
                        if (res?.errors) {
                            let firstError = Object.values(res.errors)[0][0];
                            showError(firstError);
                        } else {
                            showError(res?.message || 'Validasi gagal');
                        }
                        return;
                    }

                    showError(res?.message || 'Terjadi kesalahan server');
                }
            });
        });


        // ===========================
        // MODAL EDIT BLOCK (SAFE + ENCRYPT READY)
        // ===========================
        const modalEditBlock = document.getElementById('modalEditBlock');
        const BLOCK_UPDATE_ROUTE = "{{ route('management.block.update', ':id') }}";

        if (modalEditBlock) {
            modalEditBlock.addEventListener('show.bs.modal', function(event) {

                const button = event.relatedTarget;
                if (!button) return;

                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                const status = button.getAttribute('data-status');
                const rtId = button.getAttribute('data-rt');
                const rw = button.getAttribute('data-rw');

                // fill form safely
                const namaEl = document.getElementById('edit_nama');
                const statusEl = document.getElementById('edit_status');
                const rtEl = document.getElementById('edit_rt_id');
                const rwEl = document.getElementById('edit_rw');

                if (namaEl) namaEl.value = nama || '';
                if (statusEl) statusEl.value = status || '';
                if (rtEl) rtEl.value = rtId || '';
                if (rwEl) rwEl.value = rw || '';

                // set form action (ENCRYPT READY)
                const form = document.getElementById('formEditBlock');
                if (form) {
                    form.action = BLOCK_UPDATE_ROUTE.replace(':id', id);
                }
            });
        }

        $(document).on('submit', '#formEditBlock', function(e) {
            e.preventDefault();

            const form = $(this);
            const btn = form.find('button[type="submit"]');

            btn.prop('disabled', true);
            showLoading('Mengupdate Block...');

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),

                success: function(res) {
                    btn.prop('disabled', false);

                    if (res.status !== 'success') {
                        showError(res.message);
                        return;
                    }

                    showSuccess(res.message);

                    const modalEl = document.getElementById('modalEditBlock');
                    bootstrap.Modal.getInstance(modalEl)?.hide();

                    setTimeout(() => location.reload(), 800);
                },

                error: function(xhr) {
                    btn.prop('disabled', false);

                    const res = xhr.responseJSON;

                    if (xhr.status === 422) {
                        if (res?.errors) {
                            let firstError = Object.values(res.errors)[0][0];
                            showError(firstError);
                        } else {
                            showError(res?.message || 'Validasi gagal');
                        }
                        return;
                    }

                    showError(res?.message || 'Terjadi kesalahan server');
                }
            });
        });
    </script>
@endpush
