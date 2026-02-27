@extends('frontend.layouts.app')

@section('title', 'Beranda')
@section('header-title', 'Beranda')

@section('content')

    {{-- BANNER SLIDER --}}
    <div class="banner-wrapper">
        <div class="banner-slider">
            <div class="banner-item banner-1">
                <h5>Selamat Datang di Aplikasi Warga</h5>
                <small>Kelola data keluarga dan layanan RT dengan mudah</small>
            </div>
            <div class="banner-item banner-2">
                <h5>Layanan Surat Online</h5>
                <small>Ajukan surat pengantar tanpa datang ke rumah RT</small>
            </div>
            <div class="banner-item banner-3">
                <h5>Pengaduan Lingkungan</h5>
                <small>Laporkan masalah lingkungan langsung dari HP</small>
            </div>
        </div>
    </div>

    <h6 class="fw-bold mb-3">Layanan Lainnya</h6>

    <div class="row text-center g-3">
        @php
            $menus = [
                ['icon' => 'people', 'title' => 'Keluarga'],
                ['icon' => 'cash-stack', 'title' => 'IPL'],
                ['icon' => 'tag', 'title' => 'Birokrasi'],
                ['icon' => 'megaphone', 'title' => 'Pengaduan'],
                ['icon' => 'shield-check', 'title' => 'Keamanan'],
                ['icon' => 'cash-stack', 'title' => 'LK RT'],
                ['icon' => 'bar-chart-line', 'title' => 'LK RW'],
                ['icon' => 'wallet2', 'title' => 'LK Rukem'],
            ];
        @endphp

        @foreach ($menus as $m)
            <div class="col-3">
                <div class="service-icon mb-1">
                    <i class="bi bi-{{ $m['icon'] }}"></i>
                </div>
                <small>{{ $m['title'] }}</small>
            </div>
        @endforeach
    </div>

    <h6 class="fw-bold mt-3 mb-3">Program Layanan </h6>

    <div class="program-wrapper">

        {{-- Rukem --}}
        <div class="program-item">
            <div class="program-text">
                <div class="program-title">Rukem</div>
                <small class="text-muted">
                    Program Rukun Kematian
                </small>
            </div>

            <div class="program-check">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

        {{-- Kebersihan --}}
        <div class="program-item">
            <div class="program-text">
                <div class="program-title">Kebersihan Lingkungan</div>
                <small class="text-muted">
                    Program kebersihan lingkungan rutin setiap minggu
                </small>
            </div>

            <div class="program-check">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>

    </div>

@endsection
