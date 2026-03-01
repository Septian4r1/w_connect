@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Data Diri')

@section('content')
    {{-- @dd($rumah) --}}

    <!-- ================= INFORMASI RUMAH ================= -->
    <div class="card border-0 shadow-sm p-3 mb-3" style="font-size: 0.75rem;">
        <h5 class="mb-3 text-center" style="font-size: 0.8rem;">Informasi Rumah</h5>
        <table class="table table-bordered table-striped mb-0 table-sm" style="font-size: 0.75rem;">
            <tbody>
                <tr>
                    <th>Nama Kepala Keluarga</th>
                    <td>{{ $rumah->kepalaKeluarga->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIK Kepala Keluarga</th>
                    <td>{{ $rumah->kepalaKeluarga->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Blok / Nomor Rumah</th>
                    <td>{{ $rumah->nomor_rumah ?? '-' }}</td>
                </tr>
                <tr>
                    <th>RT / RW</th>
                    <td>{{ $rumah->block->rt->nama_rt ?? '-' }} / {{ $rumah->block->rt->rw->nama_rw ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Jalan / Alamat Lengkap</th>
                    <td>{{ $rumah->alamat_lengkap ?? ($rumah->keluarga->alamat_kk ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Desa / Kelurahan</th>
                    <td>{{ $rumah->desa ?? '-' }} / {{ $rumah->kelurahan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status Hunian</th>
                    <td>{{ ucfirst($rumah->status_hunian ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Status Login</th>
                    <td>{{ ucfirst($rumah->status_login ?? '-') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ================= INFORMASI KELUARGA ================= -->
    <div class="card border-0 shadow-sm p-3 mb-3" style="font-size: 0.75rem;">
        <h6 class="mb-3 text-center" style="font-size: 0.8rem;">Informasi Keluarga</h6>
        <table class="table table-striped table-bordered mb-0 table-sm" style="font-size: 0.75rem;">
            <tbody>
                <tr>
                    <th>No. KK</th>
                    <td>{{ $rumah->keluarga->no_kk ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ ucfirst($rumah->keluarga->status ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Kependudukan</th>
                    <td>{{ ucfirst($rumah->keluarga->kependudukan ?? '-') }}</td>
                </tr>
                <tr>
                    <th>Alamat KK</th>
                    <td>{{ $rumah->keluarga->alamat_kk ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Desa / Kelurahan</th>
                    <td>{{ $rumah->keluarga->desa_kelurahan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kecamatan</th>
                    <td>{{ $rumah->keluarga->kecamatan ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kota / Kabupaten</th>
                    <td>{{ $rumah->keluarga->kota_kabupaten ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Provinsi</th>
                    <td>{{ $rumah->keluarga->provinsi ?? '-' }}</td>
                </tr>
                <tr>
                    <th>KTP Setempat</th>
                    <td>{{ $rumah->keluarga->ktp_setempat == 'ya' ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th>Foto KK</th>
                    <td>
                        @if ($rumah->keluarga->foto_kk)
                            <a href="{{ asset($rumah->keluarga->foto_kk) }}" target="_blank">Lihat Foto</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ================= ANGGOTA KELUARGA ================= -->
    @foreach ($rumah->keluarga->wargas as $index => $warga)
        <div class="card border-0 shadow-sm p-3 mb-3" style="font-size: 0.75rem;">
            <h6 class="mb-3 text-center" style="font-size: 0.8rem;">
                Anggota Keluarga : {{ $index + 1 }} - {{ $warga->nama }}
            </h6>
            <table class="table table-striped table-bordered mb-0 table-sm" style="font-size: 0.75rem;">
                <tbody>
                    {{-- ID dan Keluarga ID disembunyikan --}}
                    {{-- <tr>
                    <th>ID</th>
                    <td>{{ $warga->id }}</td>
                </tr>
                <tr>
                    <th>Keluarga ID</th>
                    <td>{{ $warga->keluarga_id }}</td>
                </tr> --}}

                    <tr>
                        <th>NIK</th>
                        <td>{{ $warga->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $warga->nama }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $warga->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <th>Hubungan</th>
                        <td>{{ $warga->hubungan }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $warga->status_perkawinan }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>{{ $warga->agama }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan</th>
                        <td>{{ $warga->pendidikan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Lahir </th>
                        <td>{{ $warga->tempat_lahir ?? '-' }} </td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $warga->tanggal_lahir_formatted }}</td>
                    </tr>
                    <tr>
                        <th>Usia</th>
                        <td>{{ $warga->umur }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Usia</th>
                        <td>{{ $warga->kategori_umur['nama'] }} - {{ $warga->kategori_umur['keterangan'] }}</td>
                    </tr>
                    <tr>
                        <th>Province</th>
                        <td>{{ $warga->province ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td>{{ $warga->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $warga->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $warga->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Golongan Darah</th>
                        <td>{{ $warga->golongan_darah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Foto KTP</th>
                        <td>
                            @if ($warga->foto_ktp)
                                <a href="{{ asset($warga->foto_ktp) }}" target="_blank">Lihat Foto KTP</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Foto Selfie</th>
                        <td>
                            @if ($warga->foto)
                                <a href="{{ asset($warga->foto) }}" target="_blank">Lihat Foto</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status Warga</th>
                        <td>{{ $warga->status }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $warga->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Pada</th>
                        <td>{{ $warga->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach

    <!-- ================= Kembali ================= -->
    <div class="card border-0 shadow-sm">
        <a href="javascript:history.back()" class="list-group-item list-group-item-action text-danger text-center fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

@endsection
