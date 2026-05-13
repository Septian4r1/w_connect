@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Data Keluarga')

@section('content')
    <div class="container py-3">

        {{-- MENU TAMBAH KK --}}
        <div class="row g-2 mb-3">
            <div class="col-12">
                <a href="{{ route('TambahBeda.kk') }}" class="text-decoration-none">
                    <div class="card shadow-sm border-1 rounded-3">
                        <div class="card-body d-flex align-items-center py-2 px-3">
                            <div class="me-2">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:38px;height:38px;">
                                    <i class="bi bi-plus-lg"></i>
                                </div>
                            </div>
                            <div class="text-start">
                                <div class="fw-semibold" style="font-size:14px;">Data Keluarga</div>
                                <small class="text-muted" style="font-size:11px;">Satu Rumah • Beda KK</small>
                            </div>
                            <div class="ms-auto text-muted">
                                <i class="bi bi-chevron-right small"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- =========================
KELUARGA UTAMA
========================= --}}
        @forelse($keluargaUtama as $keluarga)

            <div class="card border-1 shadow-sm mt-3">

                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <small class="fw-semibold">Keluarga Utama</small>

                    {{-- Tambah Anggota --}}
                    <a href="#" class="btn btn-dark btn-sm rounded-circle shadow-sm btnTambahAnggota"
                        data-kk="{{ Crypt::encryptString($keluarga->id) }}"
                        style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-plus-lg" style="font-size:12px;"></i>
                    </a>
                </div>

                <div class="card-body p-2">

                    <div class="table-responsive">

                        <table class="table table-sm table-bordered align-middle mb-0" style="font-size:12px;">

                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Hubungan</th>
                                    <th width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- =========================
                                    KEPALA KELUARGA
                                    ========================= --}}
                                @if ($keluarga->kepalaKeluarga)
                                    <tr>

                                        <td>{{ $keluarga->kepalaKeluarga->nama }}</td>

                                        <td>Kepala Keluarga</td>

                                        <td class="text-center">

                                            <div class="d-flex justify-content-center gap-1">

                                                {{-- VIEW --}}
                                                <a href="#"
                                                    class="btn btn-light btn-sm rounded-circle shadow-sm btnViewWarga"
                                                    data-id="{{ Crypt::encryptString($keluarga->kepalaKeluarga->id) }}"
                                                    data-nama="{{ $keluarga->kepalaKeluarga->nama }}"
                                                    data-nik="{{ $keluarga->kepalaKeluarga->nik }}"
                                                    data-hubungan="Kepala Keluarga"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($keluarga->kepalaKeluarga->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                    data-no_hp="{{ $keluarga->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $keluarga->kepalaKeluarga->email }}"
                                                    data-foto="{{ !empty($keluarga->kepalaKeluarga->foto_ktp) ? asset($keluarga->kepalaKeluarga->foto_ktp) : '' }}"
                                                    style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                    <i class="bi bi-eye text-primary" style="font-size:12px;"></i>

                                                </a>


                                                {{-- EDIT --}}
                                                <a href="#"
                                                    class="btn btn-light btn-sm rounded-circle shadow-sm btnEditWarga"
                                                    data-id="{{ Crypt::encryptString($keluarga->kepalaKeluarga->id) }}"
                                                    data-nama="{{ $keluarga->kepalaKeluarga->nama }}"
                                                    data-nik="{{ $keluarga->kepalaKeluarga->nik }}"
                                                    data-hubungan="{{ $keluarga->kepalaKeluarga->hubungan }}"
                                                    data-jenis_kelamin="{{ $keluarga->kepalaKeluarga->jenis_kelamin }}"
                                                    data-status_perkawinan="{{ $keluarga->kepalaKeluarga->status_perkawinan }}"
                                                    data-agama="{{ $keluarga->kepalaKeluarga->agama }}"
                                                    data-pendidikan="{{ $keluarga->kepalaKeluarga->pendidikan }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($keluarga->kepalaKeluarga->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                    data-tempat_lahir="{{ $keluarga->kepalaKeluarga->tempat_lahir }}"
                                                    data-provinsi="{{ $keluarga->kepalaKeluarga->province }}"
                                                    data-pekerjaan="{{ $keluarga->kepalaKeluarga->pekerjaan }}"
                                                    data-golongan_darah="{{ $keluarga->kepalaKeluarga->golongan_darah }}"
                                                    data-no_hp="{{ $keluarga->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $keluarga->kepalaKeluarga->email }}"
                                                    data-foto="{{ !empty($keluarga->kepalaKeluarga->foto_ktp) ? asset($keluarga->kepalaKeluarga->foto_ktp) : '' }}"
                                                    data-selfie="{{ !empty($keluarga->kepalaKeluarga->foto) ? asset($keluarga->kepalaKeluarga->foto) : '' }}"
                                                    style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                    <i class="bi bi-pencil text-warning" style="font-size:12px;"></i>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endif


                                {{-- =========================
                                ANGGOTA KELUARGA
                                ========================= --}}
                                @foreach ($keluarga->anggota as $anggota)
                                    @if ($anggota->hubungan != 'kepala_keluarga')
                                        <tr>

                                            <td>{{ $anggota->nama }}</td>

                                            <td>{{ $anggota->hubungan }}</td>

                                            <td class="text-center">

                                                <div class="d-flex justify-content-center gap-1">

                                                    {{-- VIEW --}}
                                                    <a href="#"
                                                        class="btn btn-light btn-sm rounded-circle shadow-sm btnViewWarga"
                                                        data-id="{{ Crypt::encryptString($anggota->id) }}"
                                                        data-nama="{{ $anggota->nama }}" data-nik="{{ $anggota->nik }}"
                                                        data-hubungan="{{ $anggota->hubungan }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                        data-no_hp="{{ $anggota->no_hp }}"
                                                        data-email="{{ $anggota->email }}"
                                                        data-foto="{{ !empty($anggota->foto_ktp) ? asset($anggota->foto_ktp) : '' }}"
                                                        style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                        <i class="bi bi-eye text-primary" style="font-size:12px;"></i>

                                                    </a>


                                                    {{-- EDIT --}}
                                                    <a href="#"
                                                        class="btn btn-light btn-sm rounded-circle shadow-sm btnEditWarga"
                                                        data-id="{{ Crypt::encryptString($anggota->id) }}"
                                                        data-nama="{{ $anggota->nama }}" data-nik="{{ $anggota->nik }}"
                                                        data-hubungan="{{ $anggota->hubungan }}"
                                                        data-jenis_kelamin="{{ $anggota->jenis_kelamin }}"
                                                        data-status_perkawinan="{{ $anggota->status_perkawinan }}"
                                                        data-agama="{{ $anggota->agama }}"
                                                        data-pendidikan="{{ $anggota->pendidikan }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                        data-tempat_lahir="{{ $anggota->tempat_lahir }}"
                                                        data-provinsi="{{ $anggota->province }}"
                                                        data-pekerjaan="{{ $anggota->pekerjaan }}"
                                                        data-golongan_darah="{{ $anggota->golongan_darah }}"
                                                        data-no_hp="{{ $anggota->no_hp }}"
                                                        data-email="{{ $anggota->email }}"
                                                        data-foto="{{ !empty($anggota->foto_ktp) ? asset($anggota->foto_ktp) : '' }}"
                                                        data-selfie="{{ !empty($anggota->foto) ? asset($anggota->foto) : '' }}"
                                                        style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                        <i class="bi bi-pencil text-warning" style="font-size:12px;"></i>
                                                    </a>
                                                </div>

                                            </td>

                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @empty

            <div class="card border-1 shadow-sm mt-3">
                <div class="card-body text-center text-muted">
                    Data keluarga utama belum ada
                </div>
            </div>

        @endforelse

        {{-- =========================
KELUARGA TAMBAHAN
========================= --}}
        @forelse ($keluargaTambahan as $kk)

            <div class="card border-1 shadow-sm mt-3">

                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">

                    <small class="fw-semibold">
                        {{ $kk->jenisKk->nama ?? 'Jenis KK' }}
                    </small>

                    {{-- TAMBAH ANGGOTA --}}
                    <a href="#" class="btn btn-dark btn-sm rounded-circle shadow-sm btnTambahAnggota"
                        data-kk="{{ Crypt::encryptString($kk->id) }}"
                        style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-plus-lg" style="font-size:12px;"></i>
                    </a>

                </div>


                <div class="card-body p-2">

                    <div class="table-responsive">

                        <table class="table table-sm table-bordered align-middle mb-0" style="font-size:12px;">

                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Hubungan</th>
                                    <th width="90">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- =========================KEPALA KELUARGA========================= --}}
                                @if ($kk->kepalaKeluarga)
                                    <tr>

                                        <td>{{ $kk->kepalaKeluarga->nama }}</td>

                                        <td>
                                            {{ str_replace('_', ' ', $kk->kepalaKeluarga->hubungan) }}
                                        </td>

                                        <td class="text-center">

                                            <div class="d-flex justify-content-center gap-1">

                                                {{-- VIEW --}}
                                                <a href="#"
                                                    class="btn btn-light btn-sm rounded-circle shadow-sm btnViewWarga"
                                                    data-id="{{ Crypt::encryptString($kk->kepalaKeluarga->id) }}"
                                                    data-nama="{{ $kk->kepalaKeluarga->nama }}"
                                                    data-nik="{{ $kk->kepalaKeluarga->nik }}"
                                                    data-hubungan="{{ str_replace('_', ' ', $kk->kepalaKeluarga->hubungan) }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($kk->kepalaKeluarga->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                    data-hp="{{ $kk->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $kk->kepalaKeluarga->email }}"
                                                    data-foto="{{ !empty($kk->kepalaKeluarga->foto_ktp) ? asset($kk->kepalaKeluarga->foto_ktp) : '' }}"
                                                    style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                    <i class="bi bi-eye text-primary" style="font-size:12px;"></i>

                                                </a>


                                                {{-- EDIT --}}
                                                <a href="#"
                                                    class="btn btn-light btn-sm rounded-circle shadow-sm btnEditWarga"
                                                    data-id="{{ Crypt::encryptString($kk->kepalaKeluarga->id) }}"
                                                    data-nama="{{ $kk->kepalaKeluarga->nama }}"
                                                    data-nik="{{ $kk->kepalaKeluarga->nik }}"
                                                    data-hubungan="{{ str_replace('_', ' ', $kk->kepalaKeluarga->hubungan) }}"
                                                    data-jenis_kelamin="{{ $kk->kepalaKeluarga->jenis_kelamin }}"
                                                    data-status_perkawinan="{{ $kk->kepalaKeluarga->status_perkawinan }}"
                                                    data-agama="{{ $kk->kepalaKeluarga->agama }}"
                                                    data-pendidikan="{{ $kk->kepalaKeluarga->pendidikan }}"
                                                    data-tanggal="{{ \Carbon\Carbon::parse($kk->kepalaKeluarga->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                    data-tempat_lahir="{{ $kk->kepalaKeluarga->tempat_lahir }}"
                                                    data-provinsi="{{ $kk->kepalaKeluarga->province }}"
                                                    data-pekerjaan="{{ $kk->kepalaKeluarga->pekerjaan }}"
                                                    data-golongan_darah="{{ $kk->kepalaKeluarga->golongan_darah }}"
                                                    data-hp="{{ $kk->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $kk->kepalaKeluarga->email }}"
                                                    data-foto="{{ !empty($kk->kepalaKeluarga->foto_ktp) ? asset($kk->kepalaKeluarga->foto_ktp) : '' }}"
                                                    data-selfie="{{ !empty($kk->kepalaKeluarga->foto) ? asset($kk->kepalaKeluarga->foto) : '' }}"
                                                    style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                    <i class="bi bi-pencil text-warning" style="font-size:12px;"></i>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>
                                @endif



                                {{-- =========================ANGGOTA KELUARGA========================= --}}
                                @foreach ($kk->anggota as $anggota)
                                    @if ($anggota->hubungan != 'kepala_keluarga')
                                        <tr>

                                            <td>{{ $anggota->nama }}</td>

                                            <td>{{ str_replace('_', ' ', $anggota->hubungan) }}</td>

                                            <td class="text-center">

                                                <div class="d-flex justify-content-center gap-1">

                                                    {{-- VIEW --}}
                                                    <a href="#"
                                                        class="btn btn-light btn-sm rounded-circle shadow-sm btnViewWarga"
                                                        data-id="{{ Crypt::encryptString($anggota->id) }}"
                                                        data-nama="{{ $anggota->nama }}" data-nik="{{ $anggota->nik }}"
                                                        data-hubungan="{{ str_replace('_', ' ', $anggota->hubungan) }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                        data-hp="{{ $anggota->no_hp }}"
                                                        data-email="{{ $anggota->email }}"
                                                        data-foto="{{ !empty($anggota->foto_ktp) ? asset($anggota->foto_ktp) : '' }}"
                                                        style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                        <i class="bi bi-eye text-primary" style="font-size:12px;"></i>

                                                    </a>


                                                    {{-- EDIT --}}
                                                    <a href="#"
                                                        class="btn btn-light btn-sm rounded-circle shadow-sm btnEditWarga"
                                                        data-id="{{ Crypt::encryptString($anggota->id) }}"
                                                        data-nik="{{ $anggota->nik }}" data-nama="{{ $anggota->nama }}"
                                                        data-hubungan="{{ str_replace('_', ' ', $anggota->hubungan) }}"
                                                        data-jenis_kelamin="{{ $anggota->jenis_kelamin }}"
                                                        data-status_perkawinan="{{ $anggota->status_perkawinan }}"
                                                        data-agama="{{ $anggota->agama }}"
                                                        data-pendidikan="{{ $anggota->pendidikan }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                        data-tempat_lahir="{{ $anggota->tempat_lahir }}"
                                                        data-provinsi="{{ $anggota->province }}"
                                                        data-pekerjaan="{{ $anggota->pekerjaan }}"
                                                        data-golongan_darah="{{ $anggota->golongan_darah }}"
                                                        data-no_hp="{{ $anggota->no_hp }}"
                                                        data-email="{{ $anggota->email }}"
                                                        data-foto="{{ !empty($anggota->foto_ktp) ? asset($anggota->foto_ktp) : '' }}"
                                                        data-selfie="{{ !empty($anggota->foto) ? asset($anggota->foto) : '' }}"
                                                        style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">

                                                        <i class="bi bi-pencil text-warning" style="font-size:12px;"></i>

                                                    </a>

                                                </div>

                                            </td>

                                        </tr>
                                    @endif
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @empty

            <div class="card border-1 shadow-sm mt-3">

                <div class="card-body text-center text-muted">
                    Belum ada data KK tambahan
                </div>

            </div>

        @endforelse

        {{-- MODAL TAMBAH ANGGOTA --}}
        <div class="modal fade" id="modalTambahAnggota" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content rounded-3">
                    <div class="modal-header py-2 px-3">
                        <h6 class="modal-title" style="font-size:13px;">Tambah Keluarga</h6>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-3 py-2">
                        <form id="formTambahAnggota">
                            <input type="hidden" name="kk_id" id="kk_id">
                            <label class="fw-semibold mb-2" style="font-size:12px;">Pilih Hubungan</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="hubungan" value="Istri">
                                <label class="form-check-label small">Istri</label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="hubungan" value="Anak">
                                <label class="form-check-label small">Anak</label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="hubungan" value="Orang Tua">
                                <label class="form-check-label small">Orang Tua</label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="hubungan" value="Keluarga Lainnya">
                                <label class="form-check-label small">Keluarga Lainnya</label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer py-2 px-3">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success btn-sm"
                            onclick="submitTambahAnggota()">Tambah</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================= MODAL VIEW DATA WARGA========================= -->
        <div class="modal fade" id="modalViewWarga" tabindex="-1">

            <div class="modal-dialog modal-dialog-centered modal-sm">

                <div class="modal-content border-0 shadow-sm rounded-4">

                    <div class="modal-header py-2 px-3 border-0">
                        <h6 class="modal-title fw-semibold small">Detail Warga</h6>
                        <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-0 text-center">

                        <img id="view_foto" src="{{ asset('frontend/data_warga/image/sample/ktp_sample.png') }}"
                            class="shadow-sm mb-3 border"
                            style="
                            width:260px;
                            aspect-ratio:1.586/1;
                            object-fit:contain;
                            background:#f5f5f5;
                            border-radius:8px;
                        ">
                        <div class="table-responsive">

                            <table class="table table-sm align-middle mb-0" style="font-size:12px;">

                                <tbody>

                                    <tr>
                                        <td class="text-muted" style="width:90px;">Nama</td>
                                        <td class="fw-semibold" id="view_nama">-</td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">NIK</td>
                                        <td id="view_nik">-</td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Hubungan</td>
                                        <td id="view_hubungan">-</td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Tgl Lahir</td>
                                        <td id="view_tanggal">-</td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">No HP</td>
                                        <td id="view_hp">-</td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td id="view_email">-</td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- =========================MODAL EDIT DATA WARGA========================= -->
        @include('frontend.management.modal.modaleditdatawarga')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ============================================================
            // BOOTSTRAP MODAL
            // ============================================================
            const modalViewEl = document.getElementById('modalViewWarga');
            const modalEditEl = document.getElementById('modalEditWarga');
            const modalTambahEl = document.getElementById('modalTambahAnggota');

            const modalView = modalViewEl ? new bootstrap.Modal(modalViewEl) : null;
            const modalEdit = modalEditEl ? new bootstrap.Modal(modalEditEl) : null;
            const modalTambah = modalTambahEl ? new bootstrap.Modal(modalTambahEl) : null;

            // ============================================================
            // STORAGE DATA
            // ============================================================
            let wargaData = {};

            // ============================================================
            // FALLBACK IMAGE
            // ============================================================
            const fallbackKtp =
                "{{ asset('frontend/data_warga/image/sample/ktp_sample.png') }}";

            const fallbackSelfie =
                "{{ asset('frontend/data_warga/image/sample/user.png') }}";

            // ============================================================
            // CSRF TOKEN
            // ============================================================
            const token =
                document.querySelector('meta[name="csrf-token"]')?.content || '';

            // ============================================================
            // CACHE ELEMENT
            // ============================================================
            const elems = {

                // =========================
                // EDIT INPUT
                // =========================
                edit_nama: document.getElementById('edit_nama'),
                edit_nik: document.getElementById('edit_nik'),
                edit_hubungan: document.getElementById('edit_hubungan'),
                edit_tanggal: document.getElementById('edit_tanggal'),
                edit_jenis_kelamin: document.getElementById('edit_jenis_kelamin'),
                edit_status_perkawinan: document.getElementById('edit_status_perkawinan'),
                edit_agama: document.getElementById('edit_agama'),
                edit_pendidikan: document.getElementById('edit_pendidikan'),
                edit_tempat_lahir: document.getElementById('edit_tempat_lahir'),
                edit_provinsi: document.getElementById('edit_provinsi'),
                edit_pekerjaan: document.getElementById('edit_pekerjaan'),
                edit_golongan_darah: document.getElementById('edit_golongan_darah'),
                edit_hp: document.getElementById('edit_hp'),
                edit_email: document.getElementById('edit_email'),
                edit_id_warga: document.getElementById('edit_id_warga'),
                edit_id_selfie: document.getElementById('edit_id_selfie'),
                edit_foto_ktp: document.getElementById('edit_foto_ktp'),

                // =========================
                // FORM
                // =========================
                formEditSelfie: document.getElementById('formEditSelfie'),
                formPengajuan: document.getElementById('formPengajuanedit'),

                // =========================
                // PREVIEW
                // =========================
                preview_selfie: document.getElementById('preview_selfie'),

                fotoKtpInput: document.getElementById('fotoKtpInput'),
                previewKtp: document.getElementById('previewKtp'),

                dokumenInput: document.getElementById('dokumenInput'),
                previewDokumenContainer: document.getElementById('previewDokumenContainer'),
                previewDokumenImage: document.getElementById('previewDokumenImage'),
                previewDokumenFile: document.getElementById('previewDokumenFile'),

                // =========================
                // PENGAJUAN
                // =========================
                selectPerihal: document.getElementById('pengajuan_rubah_data'),
                wrapDataBaru: document.getElementById('wrap_data_baru'),
                uploadKtp: document.getElementById('uploadKtp'),
                data_awal: document.getElementById('data_awal'),
                data_baru: document.getElementById('data_baru'),
                ktpLamaContainer: document.getElementById('ktpLamaContainer'),
                ktpLamaImage: document.getElementById('ktpLamaImage')
            };

            // ============================================================
            // UTILITIES
            // ============================================================

            /**
             * SET VALUE INPUT
             */
            const setValue = (el, value) => {

                if (!el) return;

                el.value = value || '';
            };

            /**
             * SET TEXT
             */
            const setText = (el, value) => {

                if (!el) return;

                el.textContent = value || '-';
            };

            /**
             * FORMAT TANGGAL
             */
            const formatTanggal = (tanggal) => {

                if (!tanggal) return '';

                try {

                    return new Date(tanggal).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });

                } catch {

                    return tanggal;
                }
            };

            /**
             * VALID URL IMAGE
             */
            const isValidImage = (url) => {

                return (
                    url &&
                    url !== 'null' &&
                    url !== 'undefined' &&
                    url.trim() !== ''
                );
            };

            /**
             * FORCE REFRESH IMAGE
             * SUPPORT APK BUILDER / WEBVIEW
             */
            const forceImage = (imgEl, src, fallback) => {

                if (!imgEl) return;

                imgEl.onerror = function() {

                    this.onerror = null;
                    this.src = fallback;
                };

                if (isValidImage(src)) {

                    const separator =
                        src.includes('?') ? '&' : '?';

                    imgEl.src =
                        src + separator + 'v=' + Date.now();

                } else {

                    imgEl.src = fallback;
                }
            };

            /**
             * RESET IMAGE
             */
            const resetImage = (imgEl) => {

                if (!imgEl) return;

                imgEl.removeAttribute('src');
            };

            // ============================================================
            // FILE VALIDATION
            // ============================================================

            const allowedImageTypes = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/webp'
            ];

            const allowedDocumentTypes = [
                'application/pdf'
            ];

            const maxFileSize = 5 * 1024 * 1024;

            /**
             * VALIDASI FILE
             */
            const validateFile = (file, allowPdf = true) => {

                if (!file) {

                    return {
                        status: false,
                        message: 'File tidak ditemukan'
                    };
                }

                const validMime =
                    allowedImageTypes.includes(file.type) ||
                    (allowPdf && allowedDocumentTypes.includes(file.type));

                if (!validMime) {

                    return {
                        status: false,
                        message: 'Format file tidak didukung'
                    };
                }

                if (file.size > maxFileSize) {

                    return {
                        status: false,
                        message: 'Ukuran maksimal 5MB'
                    };
                }

                return {
                    status: true
                };
            };

            // ============================================================
            // PREVIEW FILE
            // ============================================================

            const previewFile = (
                input,
                imgEl = null,
                fileEl = null,
                containerEl = null
            ) => {

                const file = input.files[0];

                if (!file) {

                    if (containerEl) {
                        containerEl.style.display = 'none';
                    }

                    return;
                }

                const validation = validateFile(file);

                if (!validation.status) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Upload Gagal',
                        text: validation.message
                    });

                    input.value = '';

                    return;
                }

                const isImage =
                    allowedImageTypes.includes(file.type);

                const isPdf =
                    allowedDocumentTypes.includes(file.type);

                if (containerEl) {
                    containerEl.style.display = 'block';
                }

                // ========================================================
                // IMAGE PREVIEW
                // ========================================================
                if (isImage && imgEl) {

                    const reader = new FileReader();

                    reader.onload = function(e) {

                        imgEl.src = e.target.result;

                        imgEl.style.display = 'block';

                        imgEl.onerror = function() {

                            this.onerror = null;
                            this.src = fallbackKtp;
                        };

                        if (fileEl) {
                            fileEl.style.display = 'none';
                        }
                    };

                    reader.readAsDataURL(file);
                }

                // ========================================================
                // PDF PREVIEW
                // ========================================================
                else if (isPdf) {

                    if (imgEl) {
                        imgEl.style.display = 'none';
                    }

                    if (fileEl) {

                        fileEl.innerHTML = `
                    <div class="border rounded p-2 bg-light small">
                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                        ${file.name}
                    </div>
                `;

                        fileEl.style.display = 'block';
                    }
                }
            };

            // ============================================================
            // CLICK EVENTS
            // ============================================================

            document.addEventListener('click', (e) => {

                const btnView =
                    e.target.closest('.btnViewWarga');

                const btnEdit =
                    e.target.closest('.btnEditWarga');

                const btnTambah =
                    e.target.closest('.btnTambahAnggota');

                // ========================================================
                // VIEW DATA
                // ========================================================
                if (btnView) {

                    e.preventDefault();

                    const d = btnView.dataset;

                    setText(
                        document.getElementById('view_nama'),
                        d.nama
                    );

                    setText(
                        document.getElementById('view_nik'),
                        d.nik
                    );

                    setText(
                        document.getElementById('view_hubungan'),
                        d.hubungan
                    );

                    setText(
                        document.getElementById('view_tanggal'),
                        d.tanggal
                    );

                    setText(
                        document.getElementById('view_hp'),
                        d.no_hp || d.hp
                    );

                    setText(
                        document.getElementById('view_email'),
                        d.email
                    );

                    forceImage(
                        document.getElementById('view_foto'),
                        d.foto,
                        fallbackKtp
                    );

                    modalView?.show();
                }

                // ========================================================
                // EDIT DATA
                // ========================================================
                if (btnEdit) {

                    e.preventDefault();

                    const d = btnEdit.dataset;

                    wargaData = {
                        ...d,
                        no_hp: d.no_hp || d.hp || ''
                    };

                    Object.keys(elems).forEach(key => {

                        if (!key.startsWith('edit_')) return;

                        const field =
                            key.replace('edit_', '');

                        if (d[field] !== undefined) {

                            setValue(
                                elems[key],
                                d[field]
                            );
                        }
                    });

                    // =========================
                    // FORCE IMAGE KTP
                    // =========================
                    forceImage(
                        elems.edit_foto_ktp,
                        d.foto,
                        fallbackKtp
                    );

                    // =========================
                    // FORCE SELFIE
                    // =========================
                    forceImage(
                        elems.preview_selfie,
                        d.selfie,
                        fallbackSelfie
                    );

                    // =========================
                    // SET ID
                    // =========================
                    if (elems.edit_id_warga) {
                        elems.edit_id_warga.value = d.id;
                    }

                    if (elems.edit_id_selfie) {
                        elems.edit_id_selfie.value = d.id;
                    }

                    // =========================
                    // FORM ACTION
                    // =========================
                    if (elems.formEditSelfie) {

                        elems.formEditSelfie.action =
                            "{{ route('warga.updateSelfie', ':id') }}"
                            .replace(':id', d.id);
                    }

                    modalEdit?.show();
                }

                // ========================================================
                // TAMBAH ANGGOTA
                // ========================================================
                if (btnTambah) {

                    e.preventDefault();

                    const kkId =
                        btnTambah.dataset.kk;

                    const kkInput =
                        document.getElementById('kk_id');

                    if (kkInput) {
                        kkInput.value = kkId;
                    }

                    modalTambah?.show();
                }
            });

            // ============================================================
            // CHANGE PERIHAL
            // ============================================================

            elems.selectPerihal?.addEventListener('change', function() {

                const field = this.value;

                elems.wrapDataBaru?.classList.remove('d-none');

                if (elems.uploadKtp) {
                    elems.uploadKtp.style.display = 'none';
                }

                if (elems.ktpLamaContainer) {
                    elems.ktpLamaContainer.style.display = 'none';
                }

                if (field === 'foto_ktp') {

                    // =========================================
                    // HIDE INPUT DATA BARU
                    // =========================================
                    elems.wrapDataBaru?.classList.add('d-none');

                    // =========================================
                    // REMOVE REQUIRED
                    // WAJIB AGAR TIDAK ERROR
                    // =========================================
                    if (elems.data_baru) {

                        elems.data_baru.removeAttribute('required');
                        elems.data_baru.value = '';
                    }

                    // =========================================
                    // SHOW UPLOAD KTP
                    // =========================================
                    if (elems.uploadKtp) {
                        elems.uploadKtp.style.display = 'block';
                    }

                    // =========================================
                    // HIDE DATA AWAL
                    // =========================================
                    if (elems.data_awal) {

                        const parent =
                            elems.data_awal.closest('.mb-2');

                        if (parent) {
                            parent.style.display = 'none';
                        }
                    }

                    // =========================================
                    // LOAD FOTO LAMA
                    // =========================================
                    forceImage(
                        elems.ktpLamaImage,
                        wargaData.foto,
                        fallbackKtp
                    );

                    // =========================================
                    // SHOW CONTAINER FOTO LAMA
                    // =========================================
                    if (elems.ktpLamaContainer) {
                        elems.ktpLamaContainer.style.display = 'block';
                    }

                    return;
                } else {

                    if (elems.data_baru) {
                        elems.data_baru.setAttribute('required', 'required');
                    }
                }
                let valueAwal =
                    wargaData[field] || '';

                if (field === 'tanggal_lahir') {
                    valueAwal = wargaData.tanggal || '';
                }

                setValue(
                    elems.data_awal,
                    valueAwal
                );
            });

            // ============================================================
            // FILE EVENT
            // ============================================================

            elems.fotoKtpInput?.addEventListener('change', (e) => {

                previewFile(
                    e.target,
                    elems.previewKtp
                );
            });

            elems.dokumenInput?.addEventListener('change', (e) => {

                previewFile(
                    e.target,
                    elems.previewDokumenImage,
                    elems.previewDokumenFile,
                    elems.previewDokumenContainer
                );
            });

            // ============================================================
            // FORM SELFIE
            // ============================================================

            elems.formEditSelfie?.addEventListener('submit', () => {

                Swal.fire({
                    title: 'Mohon Tunggu',
                    text: 'Sedang upload foto...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });
            });

            // ============================================================
            // AJAX SUBMIT
            // ============================================================

            elems.formPengajuan?.addEventListener('submit', async (e) => {

                e.preventDefault();

                const formData =
                    new FormData(elems.formPengajuan);

                Swal.fire({
                    title: 'Mengirim Pengajuan',
                    text: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                try {

                    const controller =
                        new AbortController();

                    const timeout =
                        setTimeout(() => {
                            controller.abort();
                        }, 60000);

                    const response = await fetch(
                        elems.formPengajuan.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: formData,
                            signal: controller.signal
                        }
                    );

                    clearTimeout(timeout);

                    const res =
                        await response.json();

                    Swal.close();

                    if (!response.ok) {

                        throw new Error(
                            res.message ||
                            'Server Error'
                        );
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message ||
                            'Data berhasil dikirim'
                    });

                    elems.formPengajuan.reset();

                    modalEdit?.hide();

                    setTimeout(() => {
                        location.reload();
                    }, 1000);

                } catch (err) {

                    Swal.close();

                    console.log(err);

                    let pesan =
                        'Terjadi kesalahan';

                    if (err.name === 'AbortError') {

                        pesan =
                            'Upload timeout. Coba ulang lagi.';
                    } else {

                        pesan =
                            err.message ||
                            'Koneksi bermasalah';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Gagal',
                        text: pesan
                    });
                }
            });

            // ============================================================
            // RESET MODAL
            // ============================================================

            modalEditEl?.addEventListener('hidden.bs.modal', () => {

                elems.formPengajuan?.reset();

                resetImage(elems.previewKtp);

                if (elems.preview_selfie) {
                    elems.preview_selfie.src = fallbackSelfie;
                }

                if (elems.previewDokumenContainer) {
                    elems.previewDokumenContainer.style.display = 'none';
                }

                document.querySelectorAll(
                    'input[type="file"]'
                ).forEach(input => {
                    input.value = '';
                });
            });

        });

        // ============================================================
        // SUBMIT TAMBAH ANGGOTA
        // ============================================================

        function submitTambahAnggota() {

            const kkId =
                document.getElementById('kk_id')?.value;

            const hubungan =
                document.querySelector(
                    'input[name="hubungan"]:checked'
                );

            if (!hubungan) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Hubungan',
                    text: 'Silakan pilih hubungan keluarga'
                });

                return;
            }

            const url =
                hubungan.value === 'Anak'

                ?
                "{{ route('tamabhDataAnak') }}?kk_id=" +
                encodeURIComponent(kkId)

                :
                "{{ route('warga.create', ':kkId') }}"
                .replace(':kkId', encodeURIComponent(kkId)) +
                "?hubungan=" +
                encodeURIComponent(hubungan.value);

            window.location.href = url;
        }

        // ============================================================
        // PREVIEW SELFIE
        // ============================================================

        function previewSelfie(event) {

            const file =
                event.target.files[0];

            const img =
                document.getElementById('preview_selfie');

            const fallback =
                "{{ asset('frontend/data_warga/image/sample/user.png') }}";

            if (!file) {

                img.src = fallback;

                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Format Tidak Didukung',
                    text: 'Gunakan JPG, PNG atau WEBP'
                });

                event.target.value = '';

                return;
            }

            if (file.size > 5 * 1024 * 1024) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Ukuran Terlalu Besar',
                    text: 'Ukuran maksimal 5MB'
                });

                event.target.value = '';

                return;
            }

            const reader =
                new FileReader();

            reader.onload = function(e) {

                img.src = e.target.result;

                img.onerror = function() {

                    this.onerror = null;
                    this.src = fallback;
                };
            };

            reader.readAsDataURL(file);
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                width: '260px',
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                width: '260px',
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33'
            });
        </script>
    @endif

    <style>
        .swal2-title {
            font-size: 16px !important;
        }

        .swal2-html-container {
            font-size: 13px !important;
        }

        /* Container per pengajuan */
        .pengajuan-item {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        /* Timeline utama */
        .timeline {
            position: relative;
            padding-left: 2rem;
            list-style: none;
            margin-bottom: 0;
        }

        /* Garis timeline */
        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 1rem;
            /* sejajar dengan icon */
            width: 2px;
            height: 100%;
            background-color: #dee2e6;
        }

        /* Setiap item timeline */
        .timeline-item {
            position: relative;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
        }

        /* Icon status */
        .timeline-icon {
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        /* Konten timeline */
        .timeline-content {
            flex-grow: 1;
        }

        /* Optional: ubah ukuran icon agar lebih kecil */
        .timeline-icon i {
            font-size: 1rem;
        }
    </style>
@endsection
