 <!-- Modal RW -->
<div class="modal fade" id="modalTambahRW" tabindex="-1">
     <div class="modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content card-bordered">

             <div class="modal-header">
                 <h5 class="mb-0">Tambah Pengurus RW</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
             </div>

             <form action="#" method="POST">
                 @csrf

                 <div class="modal-body">
                     <div class="row g-3">

                         <!-- WARGA -->
                         <div class="col-md-6">
                             <label class="form-label">Pilih Pengurus</label>
                             <select name="user_id" class="form-select select2 " required>
                                 <option value="">-- Pilih Pengurus --</option>
                                 @foreach ($users as $user)
                                     <option value="{{ $user->id }}">
                                         {{ $user->name }} ({{ $user->nomor_rumah ?? '-' }})
                                     </option>
                                 @endforeach
                             </select>
                         </div>

                         <!-- ROLE -->
                         <div class="col-md-6">
                             <label class="form-label">Jabatan</label>
                             <select name="role_id" class="form-select" required>
                                 <option value="">-- Pilih Jabatan --</option>
                                 @foreach ($roles as $role)
                                     <option value="{{ $role->id }}">
                                         {{ strtoupper(str_replace('_', ' ', $role->name)) }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>

                         <!-- RW -->
                         <div class="col-md-6">
                             <label class="form-label">RW</label>
                             <select name="rw_id" id="rwSelect" class="form-select" required>
                                 <option value="">-- Pilih RW --</option>
                                 @foreach ($rws as $rw)
                                     <option value="{{ $rw->id }}">
                                         RW {{ $rw->nama_rw }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>

                         <!-- RT -->
                         {{-- <div class="col-md-6">
                             <label class="form-label">RT (Opsional)</label>
                             <select name="rt_id" id="rtSelect" class="form-select">
                                 <option value="">-- Pilih RT --</option>
                                 @foreach ($rts_all as $rt)
                                     <option value="{{ $rt->id }}" data-rw="{{ $rt->rw_id }}">
                                         RT {{ $rt->nama_rt }}
                                     </option>
                                 @endforeach
                             </select>
                         </div> --}}

                         <!-- STATUS -->
                         <div class="col-md-6">
                             <label class="form-label">Status</label>

                             <!-- Tampilan (disabled) -->
                             <select class="form-select" disabled>
                                 <option value="aktif" selected>Aktif</option>
                             </select>

                             <!-- Nilai yang dikirim ke server -->
                             <input type="hidden" name="status" value="aktif">
                         </div>

                     </div>
                 </div>

                 <div class="modal-footer">
                     <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                     <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                 </div>

             </form>
         </div>
     </div>
</div>





 <!-- Modal RT1 -->
<div class="modal fade" id="modalTambahRT1" tabindex="-1">
     <div class="modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5>Tambah Pengurus RT 001</h5>
             </div>
             <div class="modal-body">
                 Form tambah RT001
             </div>
         </div>
     </div>
</div>

 <!-- Modal RT2 -->
<div class="modal fade" id="modalTambahRT2" tabindex="-1">
     <div class="modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5>Tambah Pengurus RT 002</h5>
             </div>
             <div class="modal-body">
                 Form tambah RT002
             </div>
         </div>
     </div>
</div>

 <!-- Modal RT1 -->
<div class="modal fade" id="modalTambahRT3" tabindex="-1">
     <div class="modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5>Tambah Pengurus RT 003</h5>
             </div>
             <div class="modal-body">
                 Form tambah RT003
             </div>
         </div>
     </div>
</div>

 <!-- Modal RT2 -->
<div class="modal fade" id="modalTambahRT4" tabindex="-1">
     <div class="modal-xl modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5>Tambah Pengurus RT 004</h5>
             </div>
             <div class="modal-body">
                 Form tambah RT004
             </div>
         </div>
     </div>
</div>
