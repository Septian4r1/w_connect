@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">
        <div id="loadingSearch" class="loading-overlay">
            <div class="spinner"></div>
            <div>Mencari data...</div>
        </div>

        <h6 class="mb-4 text-danger fw-bold">
            Data Warga {{ $wilayahLabel }}
        </h6>

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                {{-- SEARCH --}}
                <form method="GET" id="formSearch" class="search-wrapper">
                    <input type="text" name="search" id="searchInput" autocomplete="off" value="{{ request('search') }}"
                        class="search-input" placeholder="..Cari warga...">
                </form>

                {{-- BUTTON --}}
                <a href="{{ route('management.warga.tambah') }}" class="btn btn-sm btn-secondary">
                    <i class="bx bx-plus"></i> Warga
                </a>
            </div>
            <div class="card-body">
                @php
                    function sort_link($column)
                    {
                        $dir = request('sort_dir') === 'asc' ? 'desc' : 'asc';
                        return request()->fullUrlWithQuery([
                            'sort_by' => $column,
                            'sort_dir' => $dir,
                        ]);
                    }

                    function sort_icon($column)
                    {
                        if (request('sort_by') !== $column) {
                            return '<i class="bi bi-arrow-down-up text-muted ms-1"></i>'; // default (belum sort)
                        }

                        if (request('sort_dir') === 'asc') {
                            return '<i class="bi bi-arrow-up ms-1"></i>'; // naik
                        }

                        return '<i class="bi bi-arrow-down ms-1"></i>'; // turun
                    }
                @endphp

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">

                        <thead class="table-light">
                            <tr class="text-center align-middle">
                                <th>No</th>
                                <th>Foto</th>

                                <th>
                                    <a href="{{ sort_link('wargas.nama') }}" class="text-dark text-decoration-none">
                                        Nama {!! sort_icon('wargas.nama') !!}
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ sort_link('rumahs.nomor_rumah') }}" class="text-dark text-decoration-none">
                                        No Rumah {!! sort_icon('rumahs.nomor_rumah') !!}
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ sort_link('rts.nama_rt') }}" class="text-dark text-decoration-none">
                                        RT {!! sort_icon('rts.nama_rt') !!}
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ sort_link('wargas.jenis_kelamin') }}"
                                        class="text-dark text-decoration-none">
                                        JK {!! sort_icon('wargas.jenis_kelamin') !!}
                                    </a>
                                </th>

                                <th>
                                    <a href="{{ sort_link('usia') }}" class="text-dark text-decoration-none">
                                        Usia {!! sort_icon('usia') !!}
                                    </a>
                                </th>

                                <th>Kategori Usia</th>
                                <th>Hubungan</th>
                                <th>Kependudukan</th>
                                <th>Hunian</th>
                                <th>Log</th>

                                <th>
                                    <a href="{{ sort_link('wargas.status') }}" class="text-dark text-decoration-none">
                                        Status {!! sort_icon('wargas.status') !!}
                                    </a>
                                </th>

                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($wargas as $i => $warga)
                                <tr>
                                    <td class="text text-center">{{ $wargas->firstItem() + $i }}</td>

                                    @php
                                        $fotoPath =
                                            !empty($warga->foto) && file_exists(public_path($warga->foto))
                                                ? asset($warga->foto)
                                                : asset('frontend/data_warga/image/sample/User.png');
                                    @endphp

                                    <td class="text text-center">
                                        <img src="{{ $fotoPath }}"
                                            style="width:40px;height:40px;object-fit:cover;border-radius:50%;">
                                    </td>

                                    <td class="fw-semibold">{{ $warga->nama }}</td>

                                    <td class="text text-center">
                                        {{ $warga->nomor_rumah ?? '-' }}
                                    </td>

                                    <td class="text text-center">
                                        {{ $warga->nama_rt ?? '-' }}
                                    </td>

                                    <td class="text text-center">
                                        <span
                                            class="badge-soft {{ $warga->jenis_kelamin == 'Laki-laki' ? 'badge-blue' : 'badge-orange' }}">
                                            {{ $warga->jenis_kelamin }}
                                        </span>
                                    </td>

                                    <td class="text text-center">
                                        {{ $warga->usia ? $warga->usia . ' th' : '-' }}
                                    </td>

                                    <td class="text text-center">
                                        @if ($warga->status == 'aktif')
                                            <span class="badge-soft badge-info">
                                                {{ $warga->kategori_umur_nama }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text text-center">
                                        @php
                                            $hubungan = $warga->hubungan;
                                            $label = ucfirst(str_replace('_', ' ', $hubungan));

                                            $ayah = optional(
                                                $warga->keluarga->wargas->firstWhere('hubungan', 'kepala_keluarga'),
                                            )->nama;
                                            $ibu = optional($warga->keluarga->wargas->firstWhere('hubungan', 'istri'))
                                                ->nama;
                                        @endphp

                                        @if ($hubungan == 'anak')
                                            {{ $label }}
                                            <small class="text-muted d-block">
                                                (dari {{ $ayah ?? '-' }} & {{ $ibu ?? '-' }})
                                            </small>
                                        @elseif($hubungan == 'istri')
                                            {{ $label }}
                                            <small class="text-muted d-block">
                                                (istri dari {{ $ayah ?? '-' }})
                                            </small>
                                        @elseif($hubungan == 'kepala_keluarga')
                                            {{ $label }}
                                        @else
                                            {{ $label }}
                                        @endif
                                    </td>

                                    <td class="text text-center">
                                        <span
                                            class="badge-soft {{ optional($warga->keluarga)->kependudukan == 'ktp_setempat' ? 'badge-green' : 'badge-gray' }}">
                                            {{ ucfirst($warga->kependudukan ?? '-') }}
                                        </span>
                                    </td>

                                    <td class="text text-center">
                                        <span class="badge-soft badge-blue">
                                            {{ ucfirst($warga->status_hunian ?? '-') }}
                                        </span>
                                    </td>

                                    <td class="text text-center">
                                        <span
                                            class="badge-soft {{ $warga->status_login_filtered == 'online' ? 'badge-green' : 'badge-gray' }}">
                                            {{ ucfirst($warga->status_login_filtered) }}
                                        </span>
                                    </td>
                                    <td class="text text-center">
                                        <span class="badge-soft badge-green">
                                            {{ ucfirst($warga->status) }}
                                        </span>
                                    </td>

                                    <td class="text text-center">
                                        <div class="d-flex gap-1 align-items-center">

                                            {{-- VIEW --}}
                                            <button class="btn btn-icon btn-xs btn-info btn-view-warga"
                                                data-id="{{ $warga->id }}" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            {{-- EDIT --}}
                                            <button class="btn btn-icon btn-xs btn-warning btn-edit-warga"
                                                data-id="{{ $warga->id }}" title="Edit Data">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- SWITCH STATUS (AKTIF / NONAKTIF) --}}
                                            @php
                                                $status = $warga->status;
                                            @endphp

                                            <button
                                                class="btn btn-icon btn-xs btn-toggle-status
                                                    {{ $status == 'aktif' ? 'btn-success' : '' }}
                                                    {{ $status == 'pindah' ? 'btn-warning' : '' }}
                                                    {{ $status == 'meninggal' ? 'btn-danger' : '' }}"
                                                data-id="{{ $warga->id }}" data-status="{{ $status }}"
                                                title="Ubah Status">

                                                @if ($status == 'aktif')
                                                    <i class="bi bi-arrow-up-circle"></i>
                                                @elseif($status == 'pindah')
                                                    <i class="bi bi-arrow-right-circle"></i>
                                                @else
                                                    <i class="bi bi-x-circle"></i>
                                                @endif

                                            </button>
                                            {{-- TAMBAH DATA KELUARGA --}}
                                            @if ($warga->hubungan == 'kepala_keluarga')
                                                <button class="btn btn-icon btn-xs btn-primary btn-tambah-keluarga"
                                                    data-id="{{ encrypt($warga->id) }}" title="Tambah Data Keluarga">
                                                    <i class="bi bi-person-plus"></i>
                                                </button>
                                            @endif



                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center text-muted">
                                        Data tidak ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>


                </div>

                {{-- PAGINATION --}}
                <div class="pagination-wrapper mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $wargas->firstItem() }} - {{ $wargas->lastItem() }}
                        dari {{ $wargas->total() }} data
                    </small>

                    {{ $wargas->appends(request()->except('auth_user_id'))->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

    </div>

    @include('backend.management.warga.style')
    @include('backend.management.warga.modal_view ')
@endsection

@include('backend.management.warga.script_index')
