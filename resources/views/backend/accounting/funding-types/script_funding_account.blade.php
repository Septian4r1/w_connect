@push('scripts')
    <script>

        document.addEventListener('DOMContentLoaded', () => {

            /*
            |--------------------------------------------------------------------------
            | DELETE MODULE (PAKAI VERSION KAMU - TIDAK DIUBAH)
            |--------------------------------------------------------------------------
            */
            class FundMappingDelete {

                constructor() {
                    this.bindDeleteConfirmation();
                }

                bindDeleteConfirmation() {

                    document.addEventListener('submit', function(e) {

                        const form = e.target.closest('.deleteFundTypeForm');
                        if (!form) return;

                        e.preventDefault();

                        const button = e.submitter;
                        if (!button) return;

                        const fund = button.dataset.fund || 'Fund Mapping';
                        const orgName = button.dataset.orgName || '';
                        const orgCode = button.dataset.orgCode || '';

                        const label = orgCode ?
                            `${orgName} (${orgCode})` :
                            orgName;

                        Swal.fire({
                            title: 'Konfirmasi Hapus Data',
                            html: `
                        <div class="text-start">
                            <div class="mb-2">
                                Apakah anda yakin ingin menghapus mapping ini?
                            </div>

                            <div class="alert alert-warning">
                                <div class="fw-bold">${fund}</div>
                                <div class="mt-2">
                                    <span class="badge bg-dark">${label}</span>
                                </div>
                            </div>

                            <div class="text-danger small">
                                Data akan dihapus permanen.
                            </div>
                        </div>
                    `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Hapus',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                        }).then((result) => {

                            if (result.isConfirmed) {
                                form.submit();
                            }

                        });

                    });

                }
            }

            new FundMappingDelete();
        });
    </script>
@endpush
