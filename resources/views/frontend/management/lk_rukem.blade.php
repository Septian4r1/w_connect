@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Laporan Keuangan Rukem')

@section('content')
    {{-- @dd($rumah) --}}
     <div class="container text-center py-5">
        <div class="mb-4">
            <i class="bi bi-tools" style="font-size: 80px; color: #ffc107;"></i>
        </div>

        <h4 class="fw-bold">Halaman Sedang Dalam Pengembangan</h4>
        <p class="text-muted">
            Fitur <strong>Data Laporan Rukem</strong> sedang kami siapkan agar dapat digunakan secepatnya.
        </p>

        <div class="alert alert-info mt-4">
            Mohon maaf atas ketidaknyamanan ini 🙏<br>
            Silakan kembali lagi nanti.
        </div>

        <a href="{{ route('homeWarga') }}" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>




@endsection
