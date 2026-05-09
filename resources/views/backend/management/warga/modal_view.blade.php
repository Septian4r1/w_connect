{{-- MODAL VIEW WARGA --}}
<div class="modal fade" id="viewWargaModal" tabindex="-1" aria-labelledby="viewWargaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewWargaLabel">Detail Warga</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="profile-box d-flex gap-3 mb-3">
                    <img id="viewFoto" alt="Foto Warga" class="rounded-circle"
                        style="width:80px;height:80px;object-fit:cover;">
                    <div class="profile-info">
                        <h5 id="viewNama" class="fw-bold"></h5>
                        <p id="viewHubungan" class="mb-0"></p>
                        <p id="viewJenisKelamin" class="mb-0"></p>
                        <p id="viewStatus" class="mb-0"></p>
                    </div>
                </div>

                <hr>

                <ul class="nav nav-tabs mb-3" id="wargaTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-warga">
                            Data Warga
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keluarga">
                            Keluarga
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rumah">
                            Rumah
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dokumen">
                            Dokumen
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- ================= DATA WARGA ================= --}}
                    <div class="tab-pane fade show active" id="tab-warga">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>NIK</th>
                                <td id="v_nik"></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td id="v_nama"></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td id="v_jk"></td>
                            </tr>
                            <tr>
                                <th>Hubungan</th>
                                <td id="v_hubungan"></td>
                            </tr>
                            <tr>
                                <th>Status Kawin</th>
                                <td id="v_kawin"></td>
                            </tr>
                            <tr>
                                <th>Agama</th>
                                <td id="v_agama"></td>
                            </tr>
                            <tr>
                                <th>Pendidikan</th>
                                <td id="v_pendidikan"></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td id="v_tgl"></td>
                            </tr>
                            <tr>
                                <th>Tempat Lahir</th>
                                <td id="v_tempat"></td>
                            </tr>
                            <tr>
                                <th>Pekerjaan</th>
                                <td id="v_pekerjaan"></td>
                            </tr>
                            <tr>
                                <th>No HP</th>
                                <td id="v_hp"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td id="v_email"></td>
                            </tr>
                            <tr>
                                <th>Gol Darah</th>
                                <td id="v_goldar"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="v_status"></td>
                            </tr>
                        </table>
                    </div>

                    {{-- ================= KELUARGA ================= --}}
                    <div class="tab-pane fade" id="tab-keluarga">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>No KK</th>
                                <td id="v_nokk"></td>
                            </tr>

                            <tr>
                                <th>Jenis KK</th>
                                <td id="v_jenis_kk"></td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td id="v_alamat"></td>
                            </tr>
                            <tr>
                                <th>Desa</th>
                                <td id="v_desa"></td>
                            </tr>
                            <tr>
                                <th>Kecamatan</th>
                                <td id="v_kecamatan"></td>
                            </tr>
                            <tr>
                                <th>Kota</th>
                                <td id="v_kota"></td>
                            </tr>
                            <tr>
                                <th>Provinsi</th>
                                <td id="v_provinsi"></td>
                            </tr>
                            <tr>
                                <th>Kependudukan</th>
                                <td id="v_kependudukan"></td>
                            </tr>
                        </table>
                    </div>

                    {{-- ================= RUMAH ================= --}}
                    <div class="tab-pane fade" id="tab-rumah">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th>No Rumah</th>
                                <td id="v_rumah"></td>
                            </tr>
                            <tr>
                                <th>Blok</th>
                                <td id="v_blok"></td>
                            </tr>
                            <tr>
                                <th>RT / RW</th>
                                <td id="v_rtrw"></td>
                            </tr>
                            <tr>
                                <th>Status Hunian</th>
                                <td id="v_hunian"></td>
                            </tr>
                            <tr>
                                <th>Status Login</th>
                                <td id="v_login"></td>
                            </tr>
                        </table>
                    </div>

                    {{-- ================= DOKUMEN ================= --}}
                    <div class="tab-pane fade" id="tab-dokumen">
                        <div class="row">

                            <div class="col-md-4 text-center">
                                <label class="fw-semibold mb-2">Foto KTP</label>
                                <img id="v_foto_ktp" src="" class="img-fluid rounded border"
                                    style="max-height:200px; object-fit:cover;">
                            </div>

                            <div class="col-md-4 text-center">
                                <label class="fw-semibold mb-2">Selfie</label>
                                <img id="v_foto_selfie" src="" class="img-fluid rounded border"
                                    style="max-height:200px; object-fit:cover;">
                            </div>

                            <div class="col-md-4 text-center">
                                <label class="fw-semibold mb-2">Foto KK</label>
                                <img id="v_foto_kk" src="" class="img-fluid rounded border"
                                    style="max-height:200px; object-fit:cover;">
                            </div>


                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <img id="previewImage" src="" class="img-fluid rounded">
        </div>
    </div>
</div>
