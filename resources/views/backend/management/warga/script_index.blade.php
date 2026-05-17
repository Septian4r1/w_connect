@push('scripts')
    <script>
        $(document).ready(function() {

            // ===========================
            // ELEMENT
            // ===========================
            const input = $('#searchInput');
            const form = $('#formSearch');
            const loading = $('#loadingSearch');


            const showLoading = () => loading.show();
            const hideLoading = () => loading.hide();

            const setImage = (selector, path, fallback) => {
                $(selector)
                    .attr('src', path || fallback)
                    .off('error')
                    .on('error', function() {
                        $(this).attr('src', fallback);
                    });
            };

            // ===========================
            // SEARCH DEBOUNCE
            // ===========================
            if (input.length && form.length) {

                let debounceTimer;
                let lastValue = input.val().trim();

                input.on('input', function() {

                    clearTimeout(debounceTimer);

                    debounceTimer = setTimeout(() => {

                        const currentValue = input.val().trim();

                        if (currentValue === lastValue) return;

                        lastValue = currentValue;

                        showLoading();

                        form.submit();

                    }, 500);

                });

                input.on('search', function() {
                    if (input.val() === lastValue) return;
                    lastValue = input.val();

                    showLoading();
                    form.submit();
                });
            }

            // ===========================
            // VIEW WARGA (AJAX)
            // ===========================
            $(document).on('click', '.btn-view-warga', function() {

                let wargaId = $(this).data('id');
                if (!wargaId) return;

                let url = "{{ route('management.warga.view', ':id') }}".replace(':id', wargaId);

                showLoading();

                $.get(url)
                    .done(function(res) {

                        hideLoading();

                        setImage('#viewFoto', res.foto, '/frontend/image/sample/user.png');

                        $('#viewNama').text(res.nama ?? '-');
                        $('#viewHubungan').text(res.hubungan ?? '-');
                        $('#viewJenisKelamin').text(res.jenis_kelamin ?? '-');
                        $('#viewStatus').text(res.status ?? '-');

                        $('#v_nik').text(res.nik ?? '-');
                        $('#v_nama').text(res.nama ?? '-');
                        $('#v_jk').text(res.jenis_kelamin ?? '-');
                        $('#v_hubungan').text(res.hubungan ?? '-');
                        $('#v_kawin').text(res.status_perkawinan ?? '-');
                        $('#v_agama').text(res.agama ?? '-');
                        $('#v_pendidikan').text(res.pendidikan ?? '-');
                        $('#v_tgl').text(res.tanggal_lahir ?? '-');
                        $('#v_tempat').text(res.tempat_lahir ?? '-');
                        $('#v_pekerjaan').text(res.pekerjaan ?? '-');
                        $('#v_hp').text(res.no_hp ?? '-');
                        $('#v_email').text(res.email ?? '-');
                        $('#v_goldar').text(res.golongan_darah ?? '-');
                        $('#v_status').text(res.status ?? '-');

                        let keluarga = res.keluarga ?? {};
                        $('#v_nokk').text(keluarga.no_kk ?? '-');
                        $('#v_jenis_kk').text(keluarga.jenis_kk ?? '-');
                        $('#v_alamat').text(keluarga.alamat ?? '-');
                        $('#v_desa').text(keluarga.desa ?? '-');
                        $('#v_kecamatan').text(keluarga.kecamatan ?? '-');
                        $('#v_kota').text(keluarga.kota ?? '-');
                        $('#v_provinsi').text(keluarga.provinsi ?? '-');
                        $('#v_kependudukan').text(keluarga.kependudukan ?? '-');

                        let rumah = keluarga.rumah ?? {};
                        $('#v_rumah').text(rumah.nomor ?? '-');
                        $('#v_blok').text(rumah.blok ?? '-');
                        $('#v_rtrw').text((rumah.rt ?? '-') + ' / ' + (rumah.rw ?? '-'));
                        $('#v_hunian').text(rumah.hunian ?? '-');
                        $('#v_login').text(rumah.login ?? '-');

                        setImage('#v_foto_ktp', res.foto_ktp,
                            '/frontend/data_warga/image/sample/no_image.png');
                        setImage('#v_foto_selfie', res.foto_selfie,
                            '/frontend/data_warga/image/sample/no_image.png');
                        setImage('#v_foto_kk', keluarga.foto_kk,
                            '/frontend/data_warga/image/sample/no_image.png');

                        $('#viewWargaModal').modal('show');
                    })
                    .fail(function() {
                        hideLoading();
                        Swal.fire('Error', 'Gagal memuat data warga', 'error');
                    });
            });

            $(document).on('click', '.btn-toggle-status', function() {

                const btn = $(this);
                const id = btn.data('id');
                const currentStatus = btn.data('status');

                const toggleStatusRoute = "{{ route('management.warga.toggle-status', ':id') }}";

                Swal.fire({
                    title: 'Ubah Status Warga',
                    text: 'Pilih status terbaru',
                    icon: 'question',

                    input: 'radio',
                    inputOptions: {
                        aktif: 'Aktif',
                        pindah: 'Pindah',
                        meninggal: 'Meninggal'
                    },

                    inputValue: currentStatus,

                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    showCancelButton: true,

                    // 🔥 RESPONSIVE MOBILE STYLE
                    width: window.innerWidth < 576 ? '90%' : '500px',
                    padding: window.innerWidth < 576 ? '1rem' : '2rem',

                    customClass: {
                        popup: 'swal-mobile-popup',
                        title: 'swal-mobile-title',
                        confirmButton: 'swal-btn-confirm',
                        cancelButton: 'swal-btn-cancel'
                    },

                    inputValidator: (value) => {
                        if (!value) {
                            return 'Silakan pilih status!';
                        }
                    }

                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: toggleStatusRoute.replace(':id', id),
                        type: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            status: result.value
                        },
                        success: function(res) {

                            if (!res.success) {
                                Swal.fire('Error', res.message, 'error');
                                return;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 800);
                        },

                        error: function() {
                            Swal.fire('Error', 'Gagal mengubah status', 'error');
                        }
                    });

                });
            });

        });

        // ===========================
        // TAMBAH DATA KELUARGA (SWEET ALERT PILIHAN)
        // ===========================
        $(document).on('click', '.btn-tambah-keluarga', function() {

            const wargaId = $(this).data('id'); // 🔥 ini sudah encrypted
            if (!wargaId) return;

            Swal.fire({
                title: 'Tambah Data Keluarga',
                html: `
        <div class="text-start">
            <div class="p-2 border rounded mb-2 hover-option" data-type="satu_kk">
                <b>🏠 Satu Rumah - Satu KK</b><br>
                <small>Tambah anggota dalam 1 KK yang sama</small>
            </div>

            <div class="p-2 border rounded hover-option" data-type="beda_kk">
                <b>👨‍👩‍👧 Satu Rumah - Beda KK</b><br>
                <small>Tambah KK baru dalam rumah yang sama</small>
            </div>
        </div>
        `,
                showConfirmButton: false,
                showCloseButton: true,

                width: window.innerWidth < 576 ? '90%' : '420px',
                padding: window.innerWidth < 576 ? '1rem' : '1.5rem',

                customClass: {
                    popup: 'swal-mobile-popup',
                    title: 'swal-title-sm'
                },

                didOpen: () => {

                    $('.hover-option').on('click', function() {

                        const type = $(this).data('type');
                        if (!type) return;

                        let url = '';

                        if (type === 'satu_kk') {
                            url = "{{ route('management.warga.tambahSatuKK', ':id') }}".replace(
                                ':id', wargaId);
                        } else if (type === 'beda_kk') {
                            url = "{{ route('management.warga.tambahBedaKK', ':id') }}".replace(
                                ':id',
                                wargaId);
                        }

                        window.location.href = url;
                    });

                }
            });

        });
    </script>
@endpush
