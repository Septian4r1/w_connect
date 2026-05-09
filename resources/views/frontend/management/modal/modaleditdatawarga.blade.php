    <div class="modal fade" id="modalEditWarga" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm modal-fullscreen-sm-down">
            <div class="modal-content border-0 shadow-sm rounded-4">

                <div class="modal-header py-2 px-3 border-0">
                    <h6 class="modal-title fw-semibold small">Pengajuan Perubahan Data</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-1">
                    <!-- TAB FOLDER -->
                    <ul class="nav nav-tabs small mb-3 nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab_pengajuan">
                                Pengajuan
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_status">
                                Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_hasil">
                                Hasil
                            </a>
                        </li> --}}
                    </ul>


                    <div class="tab-content">
                        <!-- =========================
                                TAB 1 : PENGAJUAN PERUBAHAN
                                ========================= -->

                        <div class="tab-pane fade show active" id="tab_pengajuan">
                            <div class="accordion mobile-accordion" id="accordionPengajuan">
                                <!-- =========================
                                            DATA WARGA
                                            ========================= -->

                                <div class="accordion-item ">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button text-dark" style="background:#91f1b6;"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#dataWarga">

                                            Data Warga <br>
                                            {{-- <span class="text-danger">(Data Dapat Dirubah Melalui Form Pengjuan)</span> --}}

                                        </button>

                                    </h2>

                                    <div id="dataWarga" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionPengajuan">

                                        <div class="accordion-body">


                                            <div class="text-center mb-3">

                                                <img id="edit_foto_ktp"
                                                    src="{{ asset('frontend/data_warga/image/sample/ktp_sample.png') }}"
                                                    class="border shadow-sm mx-auto d-block img-fluid"
                                                    style="
                                                    max-width:280px;
                                                    width:100%;
                                                    height:auto;
                                                    aspect-ratio:1.586/1;
                                                    object-fit:cover;
                                                    border-radius:10px;
                                                ">

                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Nama</label>
                                                <input type="text" id="edit_nama"
                                                    class="form-control form-control-sm" disabled>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small text-muted">NIK</label>
                                                <input type="text" id="edit_nik"
                                                    class="form-control form-control-sm" disabled>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Hubungan</label>
                                                <input type="text" id="edit_hubungan"
                                                    class="form-control form-control-sm" disabled>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Tanggal Lahir</label>
                                                <input type="text" id="edit_tanggal"
                                                    class="form-control form-control-sm" disabled>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Jenis Kelamin</label>
                                                <select id="edit_jenis_kelamin" class="form-select form-select-sm"
                                                    disabled>

                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>

                                                </select>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Status Perkawinan</label>
                                                <select id="edit_status_perkawinan" class="form-select form-select-sm"
                                                    disabled>

                                                    <option value="">-- Pilih --</option>
                                                    <option value="kawin">Kawin</option>
                                                    <option value="belum_kawin">Belum Kawin</option>

                                                </select>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Agama</label>
                                                <select id="edit_agama" class="form-select form-select-sm" disabled>

                                                    <option value="">-- Pilih --</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Katolik">Katolik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Buddha">Buddha</option>
                                                    <option value="Konghucu">Konghucu</option>

                                                </select>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Pendidikan</label>
                                                <select id="edit_pendidikan" class="form-select form-select-sm"
                                                    disabled>

                                                    <option value="">Pilih Pendidikan</option>
                                                    <option value="Belum/Tidak Sekolah">Belum/Tidak Sekolah</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA/SMK">SMA/SMK</option>
                                                    <option value="Diploma">Diploma</option>
                                                    <option value="Sarjana">Sarjana</option>
                                                    <option value="Pasca Sarjana">Pasca Sarjana</option>

                                                </select>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Tempat Lahir</label>
                                                <input type="text" id="edit_tempat_lahir"
                                                    class="form-control form-control-sm" disabled>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Provinsi</label>
                                                <input type="text" id="edit_provinsi"
                                                    class="form-control form-control-sm" disabled>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Pekerjaan</label>
                                                <input type="text" id="edit_pekerjaan"
                                                    class="form-control form-control-sm" disabled>
                                            </div>


                                            <div class="mb-2">
                                                <label class="form-label small text-muted">Golongan Darah</label>
                                                <select id="edit_golongan_darah" class="form-select form-select-sm"
                                                    disabled>

                                                    <option value="">-- Pilih --</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="AB">AB</option>
                                                    <option value="O">O</option>

                                                </select>
                                            </div>


                                        </div>
                                    </div>

                                </div>



                                <!-- =========================
                                    FOTO SELFIE
                                ========================= -->

                                <div class="accordion-item">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button text-dark" style="background:#91f1b6;"
                                            type="button" data-bs-toggle="collapse"data-bs-target="#fotoSelfie">

                                            Update Foto Selfie

                                        </button>

                                    </h2>

                                    <div id="fotoSelfie" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionPengajuan">

                                        <div class="accordion-body">

                                            <form method="POST" id="formEditSelfie" enctype="multipart/form-data">

                                                @csrf
                                                @method('PUT')

                                                <input type="hidden" name="id_selfie" id="edit_id_selfie">

                                                <div class="text-center mb-2">

                                                    <img id="preview_selfie"
                                                        src="{{ asset('frontend/data_warga/image/sample/user.png') }}"
                                                        class="rounded-circle border shadow-sm"
                                                        style="width:120px;height:120px;object-fit:cover">

                                                </div>

                                                <input type="file" name="foto_selfie"
                                                    class="form-control form-control-sm" accept="image/*"
                                                    onchange="previewSelfie(event)">

                                                <div class="small text-muted mt-1">
                                                    Upload foto selfie terbaru
                                                </div>

                                                <div class="modal-footer py-2 border-0">

                                                    <button type="button" class="btn btn-light btn-sm"
                                                        data-bs-dismiss="modal">
                                                        Batal
                                                    </button>

                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        Update
                                                    </button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>



                                <!-- =========================
                                    FORM PERUBAHAN
                                ========================= -->

                                <div class="accordion-item">

                                    <h2 class="accordion-header">

                                        <button class="accordion-button text-dark" style="background:#91f1b6;"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#formPerubahan">

                                            Form Pengajuan Perubahan

                                        </button>

                                    </h2>

                                    <div id="formPerubahan" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionPengajuan">

                                        <div class="accordion-body">

                                            <form id="formPengajuanedit"
                                                action="{{ route('pengajuan.perubahan.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf


                                                <input type="hidden" name="id_warga" id="edit_id_warga">

                                                <!-- PERIHAL PERUBAHAN -->
                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Perihal
                                                        Perubahan</label>
                                                    <select name="perihal" id="pengajuan_rubah_data"
                                                        class="form-select form-select-sm">
                                                        <option value="">-- Pilih --</option>
                                                        <option value="nama">Nama</option>
                                                        <option value="jenis_kelamin">Jenis Kelamin</option>
                                                        <option value="hubungan">Hubungan</option>
                                                        <option value="status_perkawinan">Status Perkawinan</option>
                                                        <option value="agama">Agama</option>
                                                        <option value="pendidikan">Pendidikan</option>
                                                        <option value="tanggal_lahir">Tanggal Lahir</option>
                                                        <option value="tempat_lahir">Tempat Lahir</option>
                                                        <option value="pekerjaan">Pekerjaan</option>
                                                        <option value="no_hp">No Hp</option>
                                                        <option value="golongan_darah">Golongan Darah</option>
                                                        <option value="foto_ktp">Foto_ktp</option>
                                                    </select>
                                                </div>

                                                <!-- DATA AWAL -->
                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Data Awal</label>
                                                    <input type="text" name="data_awal" id="data_awal"
                                                        class="form-control form-control-sm" readonly>
                                                </div>

                                                <div class="mb-2" id="uploadKtp" style="display:none;">

                                                    <!-- FOTO KTP LAMA -->
                                                    <div class="mb-2" id="ktpLamaContainer" style="display:none;">
                                                        <label class="form-label small text-muted">
                                                            Foto KTP Lama
                                                        </label>

                                                        <img id="ktpLamaImage"
                                                            style="
                                                            width:100%;
                                                            max-height:220px;
                                                            object-fit:cover;
                                                            border-radius:10px;
                                                            border:1px solid #ddd;
                                                            padding:4px;
                                                        ">
                                                    </div>

                                                    <!-- UPLOAD KTP BARU -->
                                                    <label class="form-label small text-muted">
                                                        Upload Foto KTP Baru
                                                    </label>

                                                    <input type="file" name="foto_ktp" id="fotoKtpInput"
                                                        class="form-control form-control-sm" accept="image/*">

                                                    <small class="text-muted">
                                                        Upload foto KTP terbaru
                                                    </small>

                                                    <!-- Preview Image -->
                                                    <div class="mt-2">
                                                        <img id="previewKtp"
                                                            style="
                                                        display:none;
                                                        width:100%;
                                                        max-height:220px;
                                                        object-fit:cover;
                                                        border-radius:10px;
                                                        border:1px solid #ddd;
                                                        padding:4px;
                                                    ">
                                                    </div>

                                                </div>

                                                <!-- DATA PERUBAHAN -->
                                                <div class="mb-2" id="wrap_data_baru">
                                                    <label class="form-label small text-muted">Data Perubahan</label>
                                                    <input type="text" name="data_baru" id="data_baru"
                                                        class="form-control form-control-sm" required>
                                                </div>



                                                <!-- ALASAN -->
                                                <div class="mb-2">
                                                    <label class="form-label small text-muted">Alasan Perubahan</label>
                                                    <textarea name="alasan" class="form-control form-control-sm"
                                                        placeholder="Contoh: Ada kesalahan penulisan pada data sebelumnya" required></textarea>
                                                </div>

                                                <!-- DOKUMEN PENDUKUNG -->
                                                <!-- DOKUMEN PENDUKUNG -->
                                                <div class="mb-2">

                                                    <label class="form-label small text-muted">
                                                        Dokumen Pendukung
                                                    </label>

                                                    <input type="file" name="dokumen" id="dokumenInput"
                                                        class="form-control form-control-sm"
                                                        accept=".jpg,.jpeg,.png,.pdf">

                                                    <small class="text-muted">
                                                        Upload dokumen seperti KTP / KK / Ijazah
                                                    </small>

                                                    <!-- Preview -->
                                                    <div class="mt-2" id="previewDokumenContainer"
                                                        style="display:none;">

                                                        <img id="previewDokumenImage"
                                                            style="
                                                                display:none;
                                                                width:100%;
                                                                max-height:200px;
                                                                object-fit:cover;
                                                                border-radius:10px;
                                                                border:1px solid #ddd;
                                                                padding:4px;
                                                            ">

                                                        <div id="previewDokumenFile" class="small text-primary"></div>

                                                    </div>

                                                </div>

                                                <div class="modal-footer py-2 border-0">

                                                    <button type="button" class="btn btn-light btn-sm"
                                                        data-bs-dismiss="modal">
                                                        Batal
                                                    </button>

                                                    <button type="submit" class="btn btn-warning btn-sm text-dark">
                                                        Kirim Pengajuan
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>



                        <!-- =========================
                                    TAB 2 STATUS PROSES
                                    ========================= -->

                        {{-- <div class="tab-pane fade" id="tab_status">



                            @foreach ($pengajuanList as $pengajuan)
                                <!-- CONTAINER PER PENGAJUAN -->
                                <div class="pengajuan-item p-3 mb-4 shadow-sm rounded">

                                    <!-- INFO PENGAJUAN -->
                                    <div class="mb-3 border-bottom pb-2">
                                        <div class="fw-bold small text-primary">
                                            No Pengajuan : {{ $pengajuan->no_pengajuan }}
                                        </div>
                                        <div class="small text-muted">
                                            Nama : {{ $pengajuan->nama_pengaju }}
                                        </div>
                                        <div class="small text-muted">
                                            Perihal : {{ $pengajuan->field_perubahan }}
                                        </div>
                                    </div>

                                    <!-- TIMELINE -->
                                    <ul class="timeline timeline-sm mb-0">
                                        @foreach ($pengajuan->approvals as $step)
                                            <li class="timeline-item d-flex align-items-start">

                                                <!-- ICON STATUS -->
                                                <div class="timeline-icon me-2 mb-2">
                                                    @if ($step->status == 'approved')
                                                        <i class="bi bi-check-circle-fill text-success fs-6"></i>
                                                    @elseif ($step->status == 'rejected')
                                                        <i class="bi bi-x-circle-fill text-danger fs-6"></i>
                                                    @elseif ($step->status == 'pending')
                                                        <i class="bi bi-hourglass-split text-primary fs-6"></i>
                                                    @endif
                                                </div>

                                                <!-- CONTENT -->
                                                <div class="timeline-content flex-grow-1">
                                                    <div class="fw-bold text-capitalize mb-1">
                                                        @switch($step->level)
                                                            @case('admin')
                                                                <i class="bi bi-shield-lock"></i> Verifikasi Admin
                                                            @break

                                                            @case('rt')
                                                                <i class="bi bi-house"></i> Verifikasi RT
                                                            @break

                                                            @case('rw')
                                                                <i class="bi bi-clipboard-data"></i> Verifikasi RW
                                                            @break

                                                            @default
                                                                <i class="bi bi-file-text"></i> Proses
                                                        @endswitch
                                                    </div>
                                                    <div class="small text-muted">
                                                        {{ $step->created_at->format('d M Y H:i') }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                </div>
                            @endforeach



                        </div> --}}



                        <!-- =========================
                                    TAB 3 HASIL PENGAJUAN
                                    ========================= -->

                        {{-- <div class="tab-pane fade" id="tab_hasil">

                            <div class="card shadow-sm">

                                <div class="card-body text-center small">

                                    <div class="text-success fw-semibold">
                                        ✔ Pengajuan disetujui
                                    </div>

                                    <div class="text-danger">
                                        ✖ Pengajuan ditolak
                                    </div>

                                    <div class="small text-muted">
                                        Alasan akan ditampilkan di sini
                                    </div>

                                </div>

                            </div>

                        </div> --}}


                    </div>
                </div>

            </div>
        </div>
    </div>
