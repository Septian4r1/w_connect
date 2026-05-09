@extends('backend.layouts.app')

@section('content')
    <style>
        .rotate-icon {
            transition: transform .3s ease;
        }

        .rotate-icon.rotate {
            transform: rotate(180deg);
        }

        .card-stat {
            border: 0;
            border-radius: 14px;
            transition: .25s ease;
        }

        .card-stat:hover {
            transform: translateY(-3px);
        }

        .icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .3px;
        }
    </style>

    <div class="row">

        {{-- =========================================================
            KIRI : DATA WARGA
        ========================================================== --}}
        <div class="col-lg-6">

            {{-- ===============================
                DATA WARGA WILAYAH
            =============================== --}}
            <div class="card radius-10 shadow-sm mb-4">

                <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#collapseRW" style="cursor:pointer">

                    <span class="section-title">
                        Data Warga Wilayah
                    </span>

                    <i class="bi bi-chevron-down rotate-icon"></i>

                </div>

                <div id="collapseRW" class="collapse show">

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Total Warga --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-primary text-white">
                                            <i class="bi bi-people-fill"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $totalWarga }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Total Warga
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Laki --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-info text-white">
                                            <i class="bi bi-gender-male"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $jumlahLaki }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Laki-laki
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Perempuan --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-danger text-white">
                                            <i class="bi bi-gender-female"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $jumlahPerempuan }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Perempuan
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Statistik Umur --}}
                            @foreach ($statistikUmur as $umur)
                                <div class="col-6 col-md-4">

                                    <div class="card card-stat text-center h-100">

                                        <div class="card-body">

                                            <div class="icon-circle bg-{{ $umur['color'] }} text-white">
                                                <i class="bi {{ $umur['icon'] }}"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $umur['jumlah'] }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                {{ $umur['nama'] }}
                                            </p>

                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            {{-- ===============================
                DATA WARGA PER RT
            =============================== --}}
            @foreach ($listRT as $kodeRT => $rt)
                <div class="card radius-10 shadow-sm mb-4">

                    <div class="card-header bg-dark text-white fw-semibold d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#collapseRT{{ $kodeRT }}" style="cursor:pointer">

                        <span class="section-title">
                            Data Warga RT {{ $kodeRT }}
                        </span>

                        <i class="bi bi-chevron-down rotate-icon"></i>

                    </div>

                    <div id="collapseRT{{ $kodeRT }}" class="collapse show">

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Total Warga --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">

                                        <div class="card-body">

                                            <div class="icon-circle bg-primary text-white">
                                                <i class="bi bi-people-fill"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rt['totalWarga'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Total Warga
                                            </p>

                                        </div>

                                    </div>
                                </div>

                                {{-- Laki --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">

                                        <div class="card-body">

                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-gender-male"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rt['jumlahLaki'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Laki-laki
                                            </p>

                                        </div>

                                    </div>
                                </div>

                                {{-- Perempuan --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">

                                        <div class="card-body">

                                            <div class="icon-circle bg-danger text-white">
                                                <i class="bi bi-gender-female"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rt['jumlahPerempuan'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Perempuan
                                            </p>

                                        </div>

                                    </div>
                                </div>

                                {{-- Statistik Umur --}}
                                @if (isset($rt['statistikUmur']))
                                    @foreach ($rt['statistikUmur'] as $umur)
                                        <div class="col-6 col-md-4">

                                            <div class="card card-stat text-center h-100">

                                                <div class="card-body">

                                                    <div class="icon-circle bg-{{ $umur['color'] }} text-white">
                                                        <i class="bi {{ $umur['icon'] }}"></i>
                                                    </div>

                                                    <h5 class="mt-2 mb-1 fw-bold">
                                                        {{ $umur['jumlah'] }}
                                                    </h5>

                                                    <p class="text-muted small mb-0">
                                                        {{ $umur['nama'] }}
                                                    </p>

                                                </div>

                                            </div>

                                        </div>
                                    @endforeach
                                @endif

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        {{-- =========================================================
            KANAN : DATA RUMAH
        ========================================================== --}}
        <div class="col-lg-6">

            {{-- ===============================
                DATA RUMAH WILAYAH
            =============================== --}}
            <div class="card radius-10 shadow-sm mb-4">

                <div class="card-header bg-danger text-white fw-semibold d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#collapseCSR" style="cursor:pointer">

                    <span class="section-title">
                        Data Rumah Wilayah
                    </span>

                    <i class="bi bi-chevron-down rotate-icon"></i>

                </div>

                <div id="collapseCSR" class="collapse show">

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Total Rumah --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-primary text-white">
                                            <i class="bi bi-house-fill"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $totalRumah }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Total Rumah
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Total KK --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-success text-white">
                                            <i class="bi bi-people-fill"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $totalKK }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Total KK
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Rasio --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-warning text-white">
                                            <i class="bi bi-pie-chart-fill"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $rasioHunian }}%
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Rasio Hunian
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Milik --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-success text-white">
                                            <i class="bi bi-house-door"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $rumahMilik }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Milik Sendiri
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Sewa --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-info text-white">
                                            <i class="bi bi-key-fill"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $rumahSewa }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Rumah Sewa
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Belum --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-secondary text-white">
                                            <i class="bi bi-house-slash"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $rumahBelum }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Belum Dihuni
                                        </p>

                                    </div>
                                </div>
                            </div>

                            {{-- Kosong --}}
                            <div class="col-6 col-md-4">
                                <div class="card card-stat text-center h-100">
                                    <div class="card-body">

                                        <div class="icon-circle bg-dark text-white">
                                            <i class="bi bi-house-x-fill"></i>
                                        </div>

                                        <h5 class="mt-2 mb-1 fw-bold">
                                            {{ $rumahKosong }}
                                        </h5>

                                        <p class="text-muted small mb-0">
                                            Rumah Kosong
                                        </p>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- ===============================
                DATA RUMAH PER RT
            =============================== --}}
            @foreach ($listRumahRT as $kodeRT => $rumah)
                <div class="card radius-10 shadow-sm mb-4">

                    <div class="card-header bg-secondary text-white fw-semibold d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" data-bs-target="#collapseRumahRT{{ $kodeRT }}"
                        style="cursor:pointer">

                        <span class="section-title">
                            Data Rumah RT {{ $kodeRT }}
                        </span>

                        <i class="bi bi-chevron-down rotate-icon"></i>

                    </div>

                    <div id="collapseRumahRT{{ $kodeRT }}" class="collapse show">

                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Total Rumah --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-primary text-white">
                                                <i class="bi bi-house-fill"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['totalRumah'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Total Rumah
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- Total KK --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-people-fill"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['totalKK'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Total KK
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- Rasio --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-warning text-white">
                                                <i class="bi bi-pie-chart-fill"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['rasioHunian'] ?? 0 }}%
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Rasio Hunian
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- Milik --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-success text-white">
                                                <i class="bi bi-house-door"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['rumahMilik'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Milik Sendiri
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- Sewa --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-info text-white">
                                                <i class="bi bi-key-fill"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['rumahSewa'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Rumah Sewa
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- Belum --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-secondary text-white">
                                                <i class="bi bi-house-slash"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['rumahBelum'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Belum Dihuni
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                {{-- Kosong --}}
                                <div class="col-6 col-md-4">
                                    <div class="card card-stat text-center h-100">
                                        <div class="card-body">

                                            <div class="icon-circle bg-dark text-white">
                                                <i class="bi bi-house-x-fill"></i>
                                            </div>

                                            <h5 class="mt-2 mb-1 fw-bold">
                                                {{ $rumah['rumahKosong'] ?? 0 }}
                                            </h5>

                                            <p class="text-muted small mb-0">
                                                Rumah Kosong
                                            </p>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

    <script>
        document.querySelectorAll('.collapse').forEach(function(collapseEl) {

            collapseEl.addEventListener('show.bs.collapse', function() {

                this.previousElementSibling
                    .querySelector('.rotate-icon')
                    .classList.add('rotate');

            });

            collapseEl.addEventListener('hide.bs.collapse', function() {

                this.previousElementSibling
                    .querySelector('.rotate-icon')
                    .classList.remove('rotate');

            });

        });
    </script>
@endsection
