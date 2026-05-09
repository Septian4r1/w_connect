@push('scripts')
    <script>
        $(document).ready(function() {

            // ========================
            // CSRF SETUP
            // ========================
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            // ========================
            // SUBMIT MODAL RW
            // ========================
            $(document).on('submit', '#modalTambahRW form', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let data = form.serialize();

                let errors = [];
                if (!form.find('[name="user_id"]').val()) errors.push('Pengurus wajib dipilih');
                if (!form.find('[name="role_id"]').val()) errors.push('Jabatan wajib dipilih');
                if (!form.find('[name="rw_id"]').val()) errors.push('RW wajib dipilih');

                if (errors.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Validasi Gagal',
                        html: errors.join('<br>'),
                        width: '280px',
                        customClass: {
                            title: 'swal-title-custom',
                            content: 'swal-content-custom'
                        }
                    });
                    return;
                }

                Swal.fire({
                    title: 'Sedang menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                    width: '280px',
                    customClass: {
                        title: 'swal-title-custom',
                        content: 'swal-content-custom'
                    }
                });

                setTimeout(function() {
                    $.ajax({
                        url: url,
                        method: form.attr('method'),
                        data: data,
                        success: function(res) {
                            Swal.close();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message || 'Data berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false,
                                width: '280px',
                                customClass: {
                                    title: 'swal-title-custom',
                                    content: 'swal-content-custom'
                                }
                            });
                            form[0].reset();
                            $('#modalTambahRW').modal('hide');
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan',
                                width: '280px',
                                customClass: {
                                    title: 'swal-title-custom',
                                    content: 'swal-content-custom'
                                }
                            });
                        }
                    });
                }, 800); // delay biar smooth
            });

        });
    </script>

    <style>
        .swal-title-custom {
            font-size: 14px !important;
            line-height: 1.2 !important;
            text-align: center;
        }

        .swal-content-custom {
            font-size: 13px !important;
            line-height: 1.2 !important;
            text-align: center;
        }
    </style>
@endpush
