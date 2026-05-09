    <div class="modal fade" id="modalCreateMenu" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
            <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

                {{-- HEADER --}}
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Tambah Menu</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- FORM --}}
                <form id="formCreateMenu">
                    @csrf

                    <div class="px-4 py-3">

                        {{-- NAMA --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Nama Menu</label>
                            <input type="text" name="name" class="form-control" placeholder="contoh: Dashboard">
                        </div>

                        {{-- ROUTE --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Route</label>
                            <input type="text" name="route" class="form-control"
                                placeholder="contoh: dashboard.index">
                        </div>

                        {{-- ICON --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Icon</label>

                            {{-- SELECT ICON --}}
                            <select name="icon" id="iconSelectCreate" class="form-select">
                                <option value="">-- Pilih Icon --</option>
                            </select>

                            {{-- PREVIEW --}}
                            <div id="iconPreviewCreate" class="mt-2 small text-primary text-center">
                                Belum ada icon
                            </div>
                        </div>

                        {{-- =========================
                        PARENT MENU
                    ========================== --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Parent Menu</label>

                            <select name="parent_id" class="form-select">
                                <option value="">-- Tanpa Parent (Menu Utama) --</option>

                                @foreach ($menus as $parent)
                                    <option value="{{ $parent->id }}">
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <select name="order" class="form-select">
                            <option value="">-- Pilih Urutan --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>

                        {{-- STATUS --}}
                        <div class="mt-3">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>

                        {{-- =========================
                                PERMISSION SECTION
                            ========================== --}}

                        <div class="mt-4">

                            <label class="form-label small text-muted mb-2">
                                Permission Menu
                            </label>

                            <select name="permissions[]" id="permissionSelectCreate" class="form-select" multiple>
                                @foreach ($permissions as $perm)
                                    <option value="{{ $perm->id }}">
                                        {{ $perm->name }}
                                    </option>
                                @endforeach
                            </select>

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




    {{-- ================= MODAL CREATE PERMISSION ================= --}}
    <div class="modal fade" id="modalCreatePermissions" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
            <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

                {{-- HEADER --}}
                <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">Tambah Permission</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- FORM --}}
                <form id="formCreatePermission">
                    @csrf

                    <div class="px-4 py-3">

                        {{-- NAME --}}
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Nama Permission</label>
                            <input type="text" name="name" id="permission_name" class="form-control"
                                placeholder="contoh: management.create" required>
                        </div>

                        {{-- HELPER --}}
                        <div class="bg-light border rounded-3 p-3 small text-muted">
                            Gunakan format:
                            <br>
                            <code>module.action</code>
                            <br><br>
                            Contoh:
                            <br>
                            <code>management.view</code>,
                            <code>management.create</code>,
                            <code>management.update</code>,
                            <code>management.delete</code>
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
