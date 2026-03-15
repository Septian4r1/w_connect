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
                                                    data-hp="{{ $keluarga->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $keluarga->kepalaKeluarga->email }}"
                                                    data-foto="{{ $keluarga->kepalaKeluarga->foto_ktp ? asset($keluarga->kepalaKeluarga->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
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
                                                    data-hp="{{ $keluarga->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $keluarga->kepalaKeluarga->email }}"
                                                    data-foto="{{ $keluarga->kepalaKeluarga->foto_ktp ? asset($keluarga->kepalaKeluarga->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
                                                    data-selfie="{{ $keluarga->kepalaKeluarga->foto ? asset($keluarga->kepalaKeluarga->foto) : asset('frontend/image/sample/user.png') }}"
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
                                                        data-hp="{{ $anggota->no_hp }}" data-email="{{ $anggota->email }}"
                                                        data-foto="{{ $anggota->foto_ktp ? asset($anggota->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
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
                                                        data-tanggal="{{ $anggota->tanggal_lahir }}"
                                                        data-tempat_lahir="{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}"
                                                        data-provinsi="{{ $anggota->province }}"
                                                        data-pekerjaan="{{ $anggota->pekerjaan }}"
                                                        data-golongan_darah="{{ $anggota->golongan_darah }}"
                                                        data-hp="{{ $anggota->no_hp }}"
                                                        data-email="{{ $anggota->email }}"
                                                        data-foto="{{ $anggota->foto_ktp ? asset($anggota->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
                                                        data-selfie="{{ $anggota->foto ? asset($anggota->foto) : asset('frontend/image/sample/user.png') }}"
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
                                                    data-foto="{{ $kk->kepalaKeluarga->foto_ktp ? asset($kk->kepalaKeluarga->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
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
                                                    data-tempat_lahir="{{ $anggota->tempat_lahir }}"
                                                    data-provinsi="{{ $anggota->province }}"
                                                    data-pekerjaan="{{ $anggota->pekerjaan }}"
                                                    data-golongan_darah="{{ $anggota->golongan_darah }}"
                                                    data-hp="{{ $kk->kepalaKeluarga->no_hp }}"
                                                    data-email="{{ $kk->kepalaKeluarga->email }}"
                                                    data-foto="{{ $kk->kepalaKeluarga->foto_ktp ? asset($kk->kepalaKeluarga->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
                                                    data-selfie="{{ $kk->kepalaKeluarga->foto ? asset($kk->kepalaKeluarga->foto) : asset('frontend/image/sample/user.png') }}"
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
                                                        data-foto="{{ $anggota->foto_ktp ? asset($anggota->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
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
                                                        data-hp="{{ $anggota->no_hp }}"
                                                        data-email="{{ $anggota->email }}"
                                                        data-foto="{{ $anggota->foto_ktp ? asset($anggota->foto_ktp) : asset('frontend/image/sample/ktp_sample.png') }}"
                                                        data-selfie="{{ $anggota->foto ? asset($anggota->foto) : asset('frontend/image/sample/user.png') }}"
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

                        <img id="view_foto" src="{{ asset($keluarga->kepalaKeluarga->foto_ktp) }}"
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

            /* ===============================
               INIT MODAL
            =============================== */

            const modalView = new bootstrap.Modal(document.getElementById('modalViewWarga') ?? document
                .createElement('div'));
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEditWarga') ?? document
                .createElement('div'));
            const modalTambah = new bootstrap.Modal(document.getElementById('modalTambahAnggota') ?? document
                .createElement('div'));

            /* ===============================
               PENYIMPAN DATA WARGA
            =============================== */

            let wargaData = {};

            /* ===============================
               HELPER FUNCTIONS
            =============================== */

            const setValue = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = val || '';
            };

            const setText = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.innerText = val || '-';
            };

            const setImage = (id, src, fallback) => {
                const el = document.getElementById(id);
                if (el) el.src = src && src !== "" ? src : fallback;
            };

            /* ===============================
               EVENT DELEGATION BUTTON
            =============================== */

            document.addEventListener('click', function(e) {

                const btnView = e.target.closest('.btnViewWarga');
                const btnEdit = e.target.closest('.btnEditWarga');
                const btnTambah = e.target.closest('.btnTambahAnggota');

                /* ===============================
                   VIEW DATA
                =============================== */

                if (btnView) {

                    e.preventDefault();

                    const d = btnView.dataset;

                    setText('view_nama', d.nama);
                    setText('view_nik', d.nik);
                    setText('view_hubungan', d.hubungan);
                    setText('view_tanggal', d.tanggal);
                    setText('view_hp', d.hp);
                    setText('view_email', d.email);

                    setImage(
                        'view_foto',
                        d.foto,
                        "{{ asset('frontend/data_warga/ktp/default.png') }}"
                    );

                    modalView.show();
                }

                /* ===============================
                   EDIT DATA
                =============================== */
                function formatTanggalIndonesia(tgl) {
                    if (!tgl) return '';

                    return new Date(tgl).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                }

                if (btnEdit) {

                    e.preventDefault();

                    const d = btnEdit.dataset;
                    const id = d.id;

                    wargaData = {
                        nama: d.nama,
                        jenis_kelamin: d.jenis_kelamin,
                        hubungan: d.hubungan,
                        status_perkawinan: d.status_perkawinan,
                        agama: d.agama,
                        pendidikan: d.pendidikan,
                        tanggal_lahir: formatTanggalIndonesia(d.tanggal),
                        tempat_lahir: d.tempat_lahir,
                        pekerjaan: d.pekerjaan,
                        no_hp: d.hp,
                        golongan_darah: d.golongan_darah
                    };

                    setValue('edit_nama', d.nama);
                    setValue('edit_nik', d.nik);
                    setValue('edit_hubungan', d.hubungan);
                    setValue('edit_tanggal', d.tanggal);
                    setValue('edit_jenis_kelamin', d.jenis_kelamin);
                    setValue('edit_status_perkawinan', d.status_perkawinan);
                    setValue('edit_agama', d.agama);
                    setValue('edit_pendidikan', d.pendidikan);
                    setValue('edit_tempat_lahir', d.tempat_lahir);
                    setValue('edit_provinsi', d.provinsi);
                    setValue('edit_pekerjaan', d.pekerjaan);
                    setValue('edit_golongan_darah', d.golongan_darah);
                    setValue('edit_hp', d.hp);
                    setValue('edit_email', d.email);

                    setImage(
                        'edit_foto_ktp',
                        d.foto,
                        "{{ asset('frontend/image/sample/ktp_sample.png') }}"
                    );

                    setImage(
                        'preview_selfie',
                        d.selfie,
                        "{{ asset('frontend/image/sample/user.png') }}"
                    );

                    const idSelfie = document.getElementById('edit_id_selfie');
                    const idWarga = document.getElementById('edit_id_warga');

                    if (idSelfie) idSelfie.value = id;
                    if (idWarga) idWarga.value = id;

                    const formSelfie = document.getElementById('formEditSelfie');
                    const formUpdate = document.getElementById('formPengajuanedit');

                    if (formSelfie)
                        formSelfie.action = "{{ route('warga.updateSelfie', ':id') }}".replace(':id', id);


                    modalEdit.show();
                }

                /* ===============================
                   TAMBAH ANGGOTA
                =============================== */

                if (btnTambah) {

                    e.preventDefault();

                    const kkId = btnTambah.dataset.kk;
                    const input = document.getElementById('kk_id');

                    if (input) input.value = kkId;

                    modalTambah.show();
                }

            });

            /* ===============================
               AUTO ISI DATA AWAL
            =============================== */

            const selectPerihal = document.getElementById('pengajuan_rubah_data');

            if (selectPerihal) {

                selectPerihal.addEventListener('change', function() {

                    const field = this.value;

                    const inputAwal = document.getElementById('data_awal');
                    const inputBaru = document.getElementById('data_baru');

                    const wrapDataBaru = document.getElementById('wrap_data_baru');
                    const uploadKtp = document.getElementById('uploadKtp');

                    if (!field) {

                        if (inputAwal) inputAwal.value = '';
                        if (inputBaru) inputBaru.value = '';

                        if (wrapDataBaru) wrapDataBaru.style.display = "block";
                        if (uploadKtp) uploadKtp.style.display = "none";

                        return;
                    }

                    if (field === "foto_ktp") {

                        if (inputAwal) inputAwal.value = "Foto KTP Lama";

                        if (wrapDataBaru) wrapDataBaru.style.display = "none";
                        if (uploadKtp) uploadKtp.style.display = "block";

                    } else {

                        if (wrapDataBaru) wrapDataBaru.style.display = "block";
                        if (uploadKtp) uploadKtp.style.display = "none";

                        if (wargaData[field]) {

                            if (inputAwal) inputAwal.value = wargaData[field];
                            if (inputBaru) inputBaru.value = '';
                        }
                    }

                });

            }

            /* ===============================
               RESET FORM
            =============================== */

            const modalEditElement = document.getElementById('modalEditWarga');

            if (modalEditElement) {

                modalEditElement.addEventListener('hidden.bs.modal', function() {

                    const selectPerihal = document.getElementById('pengajuan_rubah_data');
                    const dataAwal = document.getElementById('data_awal');
                    const dataBaru = document.getElementById('data_baru');

                    if (selectPerihal) selectPerihal.value = '';
                    if (dataAwal) dataAwal.value = '';
                    if (dataBaru) dataBaru.value = '';

                });

            }

            /* ===============================
               LOADING UPDATE SELFIE
            =============================== */

            const formEditSelfie = document.getElementById('formEditSelfie');

            if (formEditSelfie) {

                formEditSelfie.addEventListener('submit', function() {

                    Swal.fire({
                        title: 'Mohon Tunggu',
                        text: 'Foto sedang diupdate',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                });

            }

            /* ===============================
               SUBMIT PENGAJUAN PERUBAHAN (AJAX)
            =============================== */

            const formPengajuan = document.getElementById('formPengajuanedit');

            if (formPengajuan) {

                formPengajuan.addEventListener('submit', function(e) {

                    e.preventDefault();

                    let formData = new FormData(this);
                    let url = this.action; // ambil langsung dari form

                    Swal.fire({
                        title: 'Mengirim Pengajuan...',
                        text: 'Mohon tunggu',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(url, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(res => {

                            Swal.close();

                            if (res.status === 'success') {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: res.message
                                });

                                formPengajuan.reset();

                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan'
                                });

                            }

                        })
                        .catch(() => {

                            Swal.close();

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Server error'
                            });

                        });

                });

            }

        });


        /* ===============================
           SUBMIT TAMBAH ANGGOTA
        =============================== */

        function submitTambahAnggota() {

            const kkId = document.getElementById('kk_id').value;
            const hubungan = document.querySelector('input[name="hubungan"]:checked');

            if (!hubungan) {
                alert('Pilih hubungan keluarga terlebih dahulu');
                return;
            }

            let url = "";

            if (hubungan.value === "Anak") {

                url = "{{ route('tamabhDataAnak') }}?kk_id=" + encodeURIComponent(kkId);

            } else {

                url = "{{ route('warga.create', ':kkId') }}"
                    .replace(':kkId', encodeURIComponent(kkId)) +
                    "?hubungan=" + encodeURIComponent(hubungan.value);

            }

            window.location.href = url;
        }


        /* ===============================
           PREVIEW FOTO SELFIE
        =============================== */

        function previewSelfie(event) {

            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = e => {
                const img = document.getElementById('preview_selfie');
                if (img) img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }

        // =======================
        // PREVIEW FOTO KTP
        // =======================

        const fotoInput = document.getElementById('fotoKtpInput');
        const previewKtp = document.getElementById('previewKtp');

        if (fotoInput) {

            fotoInput.addEventListener('change', function() {

                const file = this.files[0];

                if (!file) {
                    previewKtp.style.display = 'none';
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    alert("Ukuran foto maksimal 5MB");
                    this.value = "";
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {

                    previewKtp.src = e.target.result;
                    previewKtp.style.display = "block";

                }

                reader.readAsDataURL(file);

            });

        }


        // =======================
        // PREVIEW DOKUMEN
        // =======================

        const dokumenInput = document.getElementById('dokumenInput');
        const previewDokumenImage = document.getElementById('previewDokumenImage');
        const previewDokumenFile = document.getElementById('previewDokumenFile');
        const previewDokumenContainer = document.getElementById('previewDokumenContainer');

        if (dokumenInput) {

            dokumenInput.addEventListener('change', function() {

                const file = this.files[0];

                if (!file) {
                    previewDokumenContainer.style.display = "none";
                    return;
                }

                previewDokumenContainer.style.display = "block";

                const fileType = file.type;

                // Jika gambar
                if (fileType.startsWith('image')) {

                    const reader = new FileReader();

                    reader.onload = function(e) {

                        previewDokumenImage.src = e.target.result;
                        previewDokumenImage.style.display = "block";
                        previewDokumenFile.innerHTML = "";

                    }

                    reader.readAsDataURL(file);

                } else {

                    previewDokumenImage.style.display = "none";

                    previewDokumenFile.innerHTML =
                        "📄 " + file.name;

                }

            });

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
    </style>
@endsection
