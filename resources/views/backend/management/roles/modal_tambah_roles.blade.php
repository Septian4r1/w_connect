 <div class="modal fade" id="modalCreateRole" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0 rounded-4 shadow-lg">

             {{-- HEADER --}}
             <div class="modal-header border-0">
                 <h6 class="modal-title fw-semibold">Tambah Role</h6>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>

             {{-- BODY --}}
             <div class="modal-body pt-0">

                 <form id="formCreateRole" method="POST">
                     @csrf

                     <div class="mb-3">
                         <label class="form-label text-muted small">Nama Role</label>
                         <input type="text" name="name" class="form-control rounded-3"
                             placeholder="contoh: ketua_rt">
                     </div>

                     <div class="text-end">
                         <button type="button" class="btn btn-sm btn-light rounded-3" data-bs-dismiss="modal">
                             Batal
                         </button>

                         <button type="submit" class="btn btn-sm btn-danger rounded-3">
                             Simpan
                         </button>
                     </div>

                 </form>

             </div>

         </div>
     </div>
 </div>
