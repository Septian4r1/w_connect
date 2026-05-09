 <div class="modal fade" id="modalEditMenu" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" style="max-width:600px;">
         <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

             {{-- HEADER --}}
             <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                 <h6 class="mb-0 fw-semibold">Edit Menu</h6>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>

             {{-- FORM --}}
             <form id="formEditMenu">
                 @csrf

                 <div class="px-4 py-3">

                     <input type="hidden" name="id" id="edit_id">

                     {{-- NAMA --}}
                     <div class="mb-3">
                         <label class="form-label small text-muted mb-1">Nama Menu</label>
                         <input type="text" name="name" id="edit_name" class="form-control"
                             placeholder="contoh: Dashboard">
                     </div>

                     {{-- ROUTE --}}
                     <div class="mb-3">
                         <label class="form-label small text-muted mb-1">Route</label>
                         <input type="text" name="route" id="edit_route" class="form-control"
                             placeholder="contoh: dashboard.index">
                     </div>

                     {{-- ICON --}}
                     <div class="mb-3">
                         <label class="form-label small text-muted mb-1">Icon</label>

                         {{-- SELECT ICON --}}
                         <select name="icon" id="iconSelectEdit" class="form-select">
                             <option value="">-- Pilih Icon --</option>
                         </select>

                         {{-- PREVIEW --}}
                         <div id="iconPreviewEdit" class="mt-2 small text-primary text-center">
                             Belum ada icon
                         </div>
                     </div>

                     {{-- ORDER --}}
                     <div class="mb-3">
                         <label class="form-label small text-muted mb-1">Order</label>
                         <input type="number" name="order" id="edit_order" class="form-control"
                             placeholder="urutan menu">
                     </div>

                     {{-- STATUS --}}
                     <div class="mt-3">
                         <label class="form-label small text-muted mb-1">Status</label>
                         <select name="is_active" id="edit_status" class="form-select">
                             <option value="1">Aktif</option>
                             <option value="0">Nonaktif</option>
                         </select>
                     </div>

                     {{-- =========================
                         PERMISSION SECTION
                    ========================== --}}
                     <div class="mt-4">

                         {{-- HEADER --}}
                         <div class="d-flex justify-content-between align-items-center mb-2">
                             <label class="form-label small text-muted mb-0">
                                 Permission Terpilih
                             </label>

                             <button type="button" id="btnTambahPermission" class="btn btn-sm btn-light">
                                 + Pilih Permission
                             </button>
                         </div>

                         {{-- SELECTED PERMISSION --}}
                         <div id="selectedPermissions" class="row g-2 mb-2">
                             {{-- diisi via JS --}}
                         </div>

                         {{-- ALL PERMISSION (HIDDEN) --}}
                         <div id="allPermissions" class="mt-2"
                             style="display:none; max-height:220px; overflow-y:auto;">

                             <div class="row g-2">
                                 @foreach ($permissions as $perm)
                                     <div class="col-6">
                                         <div class="form-check">

                                             <input class="form-check-input edit-permission" type="checkbox"
                                                 name="permissions[]" value="{{ $perm->id }}"
                                                 id="perm_{{ $perm->id }}">

                                             {{-- 🔥 PENTING: pakai for --}}
                                             <label class="form-check-label small" for="perm_{{ $perm->id }}">
                                                 {{ $perm->name }}
                                             </label>

                                         </div>
                                     </div>
                                 @endforeach
                             </div>

                         </div>

                     </div>

                 </div>

                 {{-- FOOTER --}}
                 <div class="px-4 py-3 border-top d-flex justify-content-end gap-2">
                     <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                         Batal
                     </button>
                     <button type="submit" class="btn btn-sm btn-danger px-4">
                         Update
                     </button>
                 </div>

             </form>

         </div>
     </div>
 </div>


 <!-- MODAL EDIT PERMISSION -->

 <div class="modal fade" id="modalEditPermission" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
         <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">

             {{-- HEADER --}}
             <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                 <h6 class="mb-0 fw-semibold">Edit Permission</h6>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>

             {{-- FORM --}}
             <form id="formEditPermission">
                 @csrf
                 @method('PUT')

                 <div class="px-4 py-3">

                     <input type="hidden" id="edit_permission_id">

                     {{-- PERMISSION NAME --}}
                     <div class="mb-3">
                         <label class="form-label small text-muted mb-1">
                             Permission Name
                         </label>
                         <input type="text" id="edit_permission_name" class="form-control"
                             placeholder="contoh: management.create" required>
                     </div>

                 </div>

                 {{-- FOOTER --}}
                 <div class="px-4 py-3 border-top d-flex justify-content-end gap-2">
                     <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                         Batal
                     </button>
                     <button type="submit" class="btn btn-sm btn-danger px-4">
                         Update
                     </button>
                 </div>

             </form>

         </div>
     </div>
 </div>
