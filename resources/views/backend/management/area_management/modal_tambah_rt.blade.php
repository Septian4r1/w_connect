<div class="modal fade" id="modalTambahRT" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

            {{-- HEADER --}}
            <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    Tambah RT
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- FORM --}}
            <form action="{{ route('management.store_RT') }}" method="POST">
                @csrf

                <div class="px-4 py-3">

                    {{-- RW --}}
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">RW</label>
                        <select name="rw_id" class="form-select" required>
                            <option value="">-- pilih RW --</option>
                            @foreach ($rws as $rw)
                                <option value="{{ $rw->id }}">
                                    RW {{ $rw->nama_rw }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nama RT --}}
                    <div class="mb-3">
                        <label class="form-label small text-muted mb-1">Nama RT</label>
                        <input type="text" name="nama_rt" class="form-control" placeholder="contoh: 01" required>
                    </div>

                    {{-- Status --}}
                    <div class="mt-3">
                        <label class="form-label small text-muted mb-1">Status</label>

                        {{-- tampil --}}
                        <input type="text" class="form-control" value="Aktif" readonly>

                        {{-- value kirim --}}
                        <input type="hidden" name="status" value="aktif">
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-4 py-3 border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger px-4">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
