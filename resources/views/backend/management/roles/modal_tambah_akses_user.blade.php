  <div class="modal fade" id="modalTambahPengurus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:650px;">
            <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

                {{-- HEADER --}}
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        Tambah Akses User
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- FORM --}}
               <form id="formTambahPengurus" action="{{ route('management.roles_akses.store') }}" method="POST">
                    @csrf

                    <div class="px-4 py-3">

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">User</label>
                            <select name="user_id" id="selectUser" class="form-select">
                                <option value="">-- pilih user --</option>

                                @foreach ($wargas as $warga)
                                    <option value="{{ $warga->id }}">
                                        {{ $warga->nama }}
                                        - {{ optional($warga->keluarga->rumah)->nomor_rumah ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Email</label>
                            <input type="email" name="email" class="form-control"
                                placeholder="contoh: user@mail.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Role</label>
                            <select name="role_id" class="form-select">
                                <option value="">-- pilih role --</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }} ({{ $role->users_count }} user)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            {{-- RW --}}
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1">RW</label>
                                <select name="rw_id" id="selectRw" class="form-select">
                                    <option value="">-- pilih RW --</option>
                                    @foreach ($rws as $rw)
                                        <option value="{{ $rw->id }}">
                                            RW {{ $rw->nama_rw }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- RT --}}
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1">RT</label>
                                <select name="rt_id" id="selectRt" class="form-select">
                                    <option value="">-- pilih RT --</option>

                                    @foreach ($rts as $rt)
                                        <option value="{{ $rt->id }}" data-rw="{{ $rt->rw_id }}">
                                            RT {{ $rt->nama_rt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label small text-muted mb-1">Status</label>

                            {{-- tampil ke user (readonly) --}}
                            <input type="text" class="form-control" value="Aktif" readonly>

                            {{-- nilai yang dikirim ke backend --}}
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
