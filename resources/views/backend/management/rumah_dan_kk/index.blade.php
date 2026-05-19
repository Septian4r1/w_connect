@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">

        {{-- LOADING --}}
        <div id="loadingSearch" class="loading-overlay">
            <div class="spinner"></div>
            <div>Mencari data...</div>
        </div>

        {{-- TITLE --}}
        <h5 class="mb-4 text-danger fw-bold">
            Data Rumah Dan KK {{ $wilayahLabel }}
        </h5>

        {{-- SUMMARY CARD --}}
        <div class="row g-3 mb-4">

            {{-- TOTAL RUMAH --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-primary">
                    <div class="icon-box">
                        <i class="bi bi-house-door-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Total Rumah</small>
                        <h4>{{ number_format($totalRumah) }}</h4>
                    </div>
                </div>
            </div>

            {{-- TOTAL KK --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-success">
                    <div class="icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Total KK</small>
                        <h4>{{ number_format($totalKK) }}</h4>
                    </div>
                </div>
            </div>

            {{-- TOTAL WARGA --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-dark">
                    <div class="icon-box">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Total Warga</small>
                        <h4>{{ number_format($totalWarga) }}</h4>
                    </div>
                </div>
            </div>

            {{-- RUMAH SEWA --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-warning">
                    <div class="icon-box">
                        <i class="bi bi-key-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Rumah Sewa</small>
                        <h4>{{ number_format($totalSewa) }}</h4>
                    </div>
                </div>
            </div>

            {{-- MILIK SENDIRI --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-info">
                    <div class="icon-box">
                        <i class="bi bi-house-check-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Milik Sendiri</small>
                        <h4>{{ number_format($totalMilikSendiri) }}</h4>
                    </div>
                </div>
            </div>

            {{-- RUMAH KOSONG --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-danger">
                    <div class="icon-box">
                        <i class="bi bi-house-dash-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Rumah Tanggung Jawab BTN</small>
                        <h4>{{ number_format($totalKosong) }}</h4>
                    </div>
                </div>
            </div>

            {{-- BELUM DIHUNI --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-secondary">
                    <div class="icon-box">
                        <i class="bi bi-house-lock-fill"></i>
                    </div>

                    <div class="card-info">
                        <small>Belum Dihuni</small>
                        <h4>{{ number_format($totalBelumDihuni) }}</h4>
                    </div>
                </div>
            </div>

            {{-- LAKI --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-blue">
                    <div class="icon-box">
                        <i class="bi bi-gender-male"></i>
                    </div>

                    <div class="card-info">
                        <small>Laki-laki</small>
                        <h4>{{ number_format($totalLaki) }}</h4>
                    </div>
                </div>
            </div>

            {{-- PEREMPUAN --}}
            <div class="col-6 col-md-3 col-xl-2-custom">
                <div class="modern-card bg-pink">
                    <div class="icon-box">
                        <i class="bi bi-gender-female"></i>
                    </div>

                    <div class="card-info">
                        <small>Perempuan</small>
                        <h4>{{ number_format($totalPerempuan) }}</h4>
                    </div>
                </div>
            </div>

            {{-- KATEGORI UMUR DINAMIS --}}
            @foreach ($statistikUmur as $umur)
                <div class="col-6 col-md-3 col-xl-2-custom">

                    <div class="modern-card bg-orange">

                        <div class="icon-box">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>

                        <div class="card-info">
                            <small>{{ $umur['nama'] }}</small>

                            <h4>
                                {{ number_format($umur['jumlah']) }}
                            </h4>
                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        {{-- TABLE --}}
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <form method="GET" id="formSearch" class="search-wrapper">

                    <input type="text" name="search" id="searchInput" autocomplete="off"
                        value="{{ request('search') }}" class="search-input" placeholder="Cari rumah / blok / RT...">

                </form>

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
                            return '<i class="bi bi-arrow-down-up text-muted ms-1"></i>';
                        }

                        if (request('sort_dir') === 'asc') {
                            return '<i class="bi bi-arrow-up ms-1"></i>';
                        }

                        return '<i class="bi bi-arrow-down ms-1"></i>';
                    }

                @endphp

                <div class="table-responsive">

                    <table class="table table-hover align-middle text-nowrap">

                        <thead class="table-light">

                            <tr class="text-center align-middle">

                                <th>No</th>

                                <th>
                                    <a href="{{ sort_link('rumahs.nomor_rumah') }}" class="text-dark text-decoration-none">

                                        No Rumah
                                        {!! sort_icon('rumahs.nomor_rumah') !!}

                                    </a>
                                </th>


                                <th>Nama Kepala Keluarga</th>


                                <th>
                                    <a href="{{ sort_link('rts.nama_rt') }}" class="text-dark text-decoration-none">

                                        RT
                                        {!! sort_icon('rts.nama_rt') !!}

                                    </a>
                                </th>

                                <th>Status Hunian</th>
                                <th>Total KK</th>
                                <th>Total Warga</th>
                                <th>Keterangan </th>
                                <th width="140">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($rumahs as $item)
                                <tr class="text-center align-middle">

                                    <td>
                                        {{ $loop->iteration + ($rumahs->firstItem() - 1) }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $item->nomor_rumah }}
                                    </td>

                                    <td class="text-start">

                                        @if ($item->kepala_keluarga)
                                            <span class="fw-semibold">
                                                {{ $item->kepala_keluarga }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Belum Ada KK
                                            </span>
                                        @endif

                                    </td>
                                    <td>
                                        RT {{ $item->nama_rt ?? '-' }}
                                    </td>

                                    <td>

                                        @if ($item->status_hunian == 'sewa')
                                            <span class="badge bg-warning">
                                                Sewa
                                            </span>
                                        @elseif($item->status_hunian == 'huni milik sendiri')
                                            <span class="badge bg-info">
                                                Milik Sendiri
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ $item->status_hunian }}
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        @php
                                            $isKosong = $item->status_hunian == 'kosong';
                                            $kkKosong = $item->total_kk == 0;
                                        @endphp

                                        <span
                                            class="badge
        {{ $isKosong ? 'bg-warning text-dark' : ($kkKosong ? 'bg-danger' : 'bg-primary') }}">

                                            {{ $item->total_kk }}

                                        </span>

                                    </td>

                                    <td>

                                        @php
                                            $wargaKosong = $item->total_warga == 0;
                                        @endphp

                                        <span
                                            class="badge
        {{ $isKosong ? 'bg-warning text-dark' : ($wargaKosong ? 'bg-danger' : 'bg-success') }}">

                                            {{ $item->total_warga }}

                                        </span>

                                    </td>

                                    <td>

                                        @if ($item->status_hunian == 'kosong')
                                            <span class="badge bg-warning text-dark">
                                                Rumah Tanggung Jawab BTN
                                            </span>
                                        @elseif(in_array($item->status_hunian, ['huni milik sendiri', 'sewa', 'belum huni']) &&
                                                ($item->total_kk == 0 || $item->total_warga == 0))
                                            <span class="badge bg-danger">
                                                Wajib Update Data KK dan Warga
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                Data Lengkap
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="action-group">

                                            {{-- DETAIL --}}
                                            {{-- <a href="{{ route('management.rumah.show', $item->id) }}"
                                                class="action-btn action-detail">

                                                <i class="bi bi-eye-fill"></i>
                                            </a> --}}

                                            {{-- EDIT --}}
                                            <button type="button" class="action-btn action-edit btn-edit-rumah"
                                                data-id="{{ $item->id }}" data-nomor="{{ $item->nomor_rumah }}"
                                                data-status="{{ $item->status_hunian }}">

                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- DELETE --}}
                                            <form action="{{ route('management.rumah.destroy', $item->id) }}"
                                                method="POST" class="form-delete-rumah m-0 p-0"
                                                data-nama="{{ $item->kepala_keluarga ?? 'Belum Ada Kepala Keluarga' }}"
                                                data-rumah="{{ $item->nomor_rumah }}">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="action-btn action-delete">

                                                    <i class="bi bi-trash-fill"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>



                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center text-muted py-4">

                                        Data tidak ditemukan

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- PAGINATION --}}
                <div class="pagination-wrapper mt-3 d-flex justify-content-between align-items-center flex-wrap">

                    <small class="text-muted">

                        Menampilkan
                        {{ $rumahs->firstItem() ?? 0 }}
                        -
                        {{ $rumahs->lastItem() ?? 0 }}

                        dari

                        {{ $rumahs->total() }}
                        data

                    </small>

                    {{ $rumahs->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

    </div>

    {{-- STYLE --}}
    @include('backend.management.warga.style')


    {{-- MODAL EDIT RUMAH --}}
    <div class="modal fade" id="modalEditRumah" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg">

                <form id="formEditRumah" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-danger text-white">

                        <h5 class="modal-title">
                            Edit Data Rumah
                        </h5>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        {{-- NOMOR RUMAH --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Nomor Rumah
                            </label>

                            <input type="text" name="nomor_rumah" id="edit_nomor_rumah" class="form-control"
                                required>

                        </div>

                        {{-- STATUS HUNIAN --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Status Hunian
                            </label>

                            <select name="status_hunian" id="edit_status_hunian" class="form-select" required>

                                <option value="huni milik sendiri">
                                    Huni Milik Sendiri
                                </option>

                                <option value="sewa">
                                    Sewa
                                </option>

                                <option value="belum huni">
                                    Belum Huni
                                </option>

                                <option value="kosong">
                                    Kosong
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                            Batal
                        </button>

                        <button type="submit" class="btn btn-danger">

                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    {{-- ===========================
    SWEET ALERT SUCCESS
    ============================ --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                timer: 1800,
                showConfirmButton: false
            });
        </script>
    @endif

    {{-- ===========================
    SWEET ALERT ERROR
    ============================ --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
            });
        </script>
    @endif

    <script>
        // ===========================
        // OPEN MODAL EDIT RUMAH
        // ===========================
        $(document).on('click', '.btn-edit-rumah', function() {

            const id = $(this).data('id');
            const nomor = $(this).data('nomor');
            const status = $(this).data('status');

            // SET VALUE
            $('#edit_nomor_rumah').val(nomor);
            $('#edit_status_hunian').val(status);

            // SET ACTION FORM
            const updateUrl =
                "{{ route('management.rumah.update', ':id') }}"
                .replace(':id', id);

            $('#formEditRumah').attr('action', updateUrl);

            // SHOW MODAL
            $('#modalEditRumah').modal('show');
        });

        // ===========================
        // SUBMIT EDIT RUMAH AJAX
        // ===========================
        $(document).on('submit', '#formEditRumah', function(e) {

            e.preventDefault();

            const form = $(this);

            const action = form.attr('action');

            const formData = form.serialize();

            // CLOSE MODAL
            $('#modalEditRumah').modal('hide');

            // LOADING SWEET ALERT
            Swal.fire({
                title: 'Menyimpan Perubahan...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({

                url: action,
                type: 'POST',
                data: formData,

                success: function(res) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message ??
                            'Data rumah berhasil diperbarui',
                        timer: 1800,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1200);
                },

                error: function(xhr) {

                    let message =
                        'Terjadi kesalahan saat update data';

                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message
                    });
                }

            });

        });

        // ===========================
        // DELETE RUMAH SWEET ALERT
        // ===========================
        $(document).on('submit', '.form-delete-rumah', function(e) {

            e.preventDefault();

            const form = $(this);

            const nama = form.data('nama');
            const rumah = form.data('rumah');

            Swal.fire({

                title: 'Hapus Data Rumah?',

                html: `
                <div style="font-size:13px; line-height:1.6">

                    Apakah anda yakin ingin menghapus data keluarga:

                    <br><br>

                    <div style="
                        background:#f8fafc;
                        border-radius:10px;
                        padding:10px;
                        text-align:left;
                    ">

                        <div>
                            <b>Kepala Keluarga:</b><br>
                            ${nama}
                        </div>

                        <div class="mt-2">
                            <b>No Rumah:</b><br>
                            ${rumah}
                        </div>

                    </div>

                    <br>

                    <span style="color:#dc2626;font-size:12px">
                        <b>
                        Seluruh Data Rumah, KK, dan Warga
                        yang dihapus tidak dapat dikembalikan
                        </b>
                    </span>

                </div>
                `,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',

                reverseButtons: true,

                width: window.innerWidth < 576 ? '90%' : '430px',

                customClass: {
                    popup: 'swal-popup-mini',
                    title: 'swal-title-mini'
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    // LOADING
                    Swal.fire({
                        title: 'Menghapus Data...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // SUBMIT KE CONTROLLER
                    form[0].submit();
                }

            });

        });
    </script>
@endpush
