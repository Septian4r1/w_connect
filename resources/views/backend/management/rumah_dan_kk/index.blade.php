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

                    {{ $rumahs->appends(request()->query())->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

    </div>

    {{-- STYLE --}}
    @include('backend.management.warga.style')

    <style>
        .text-pink {
            color: #d63384;
        }

        .bg-blue {
            background: #0d6efd;
            color: white;
        }

        .bg-pink {
            background: #d63384;
            color: white;
        }

        .bg-orange {
            background: #fd7e14;
            color: white;
        }
    </style>
@endsection

{{-- SCRIPT --}}
@include('backend.management.warga.script_index')
