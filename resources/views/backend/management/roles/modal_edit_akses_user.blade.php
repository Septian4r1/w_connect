    <div class="modal fade" id="modalEditPengurus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:650px;">
            <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

                {{-- HEADER --}}
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        Edit Akses User
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- FORM --}}
                <form id="formEditPengurus" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="px-4 py-3">

                        <input type="hidden" name="id" id="edit_id">

                        {{-- USER --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">User</label>

                            {{-- tampil nama --}}
                            <input type="text" id="edit_user_name" class="form-control" readonly>

                            {{-- tetap kirim user_id --}}
                            <input type="hidden" name="user_id" id="edit_user_id">
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>

                        <div class="row g-3">
                            {{-- RW --}}
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1">RW</label>
                                <select name="rw_id" id="edit_rw_id" class="form-select">
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
                                <select name="rt_id" id="edit_rt_id" class="form-select">
                                    <option value="">-- pilih RT --</option>
                                    @foreach ($rts as $rt)
                                        <option value="{{ $rt->id }}" data-rw="{{ $rt->rw_id }}">
                                            RT {{ $rt->nama_rt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            {{-- ORGANIZATION --}}
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1">Organizations</label>
                                <select name="organization_id" id="edit_org_id" class="form-select">
                                    <option value="">-- pilih Organizations --</option>
                                    @foreach ($organizations ?? [] as $org)
                                        <option value="{{ $org->id }}">
                                            {{ strtoupper($org->type) }} - {{ $org->code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ROLE --}}
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-1">Role</label>
                                <select name="role_id" id="edit_role_id" class="form-select">
                                    <option value="">-- pilih role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- START DATE --}}
                        <div class="mt-3">
                            <label class="form-label mb-1 text-secondary small fw-semibold">
                                Tanggal Mulai Menjabat
                            </label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control">
                        </div>

                        {{-- END DATE --}}
                        <div class="mt-3">
                            <label class="form-label mb-1 text-secondary small fw-semibold">
                                Tanggal Selesai Menjabat
                            </label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control"readonly>
                        </div>

                        {{-- STATUS --}}
                        <div class="mt-3">
                            <label class="form-label small text-muted mb-1">Status</label>

                            <input type="text" id="edit_status_text" class="form-control" readonly>

                            <input type="hidden" name="status" id="edit_status">
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="px-4 py-3 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary px-4">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
