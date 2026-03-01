@extends('frontend.layouts.app')

@section('title', 'Profil')
@section('header-title', 'Profil')

@section('content')
    {{-- @dd($rumah) --}}
    <!-- ================= PROFILE CARD ================= -->
    <div class="card border-0 shadow-sm p-3 mb-3 text-center"
        style="background: linear-gradient(135deg, #1abc9c, #16a085); color:white;">

        <div class="mx-auto mb-2 border border-3 border-white rounded-circle"
            style="width: 90px; height: 90px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <img src="{{ $rumah->kepalaKeluarga && $rumah->kepalaKeluarga->foto
                ? asset($rumah->kepalaKeluarga->foto)
                : 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                style="width: 100%; height: auto; object-fit: cover;">
        </div>
        <h6 class="mb-0">{{ $rumah->kepalaKeluarga->nama ?? '-' }}</h6>
        <small>NIK : {{ $rumah->kepalaKeluarga->nik ?? '-' }}</small>

        <div class="d-flex justify-content-center gap-3 mt-2">
            <span class="badge bg-light text-dark">
                Status: {{ ucfirst($rumah->keluarga->status ?? '-') }}
            </span>
            <span class="badge bg-light text-dark">
                Rumah: {{ $rumah->nomor_rumah ?? '-' }}
            </span>
        </div>
    </div>

    <!-- ================= QUICK INFO ================= -->
    <div class="row text-center mb-3">
        <div class="col-4">
            <div class="card shadow-sm p-2">
                <i class="bi bi-house fs-4 text-success"></i>
                <div class="small">Rumah</div>
                <strong> {{ $rumah->nomor_rumah ?? '-' }}</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow-sm p-2">
                <i class="bi bi-shield-check fs-4 text-primary"></i>
                <div class="small">Penduduk</div>
                <strong>{{ ucfirst($rumah->keluarga->kependudukan ?? '-') }}</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="card shadow-sm p-2">
                <i class="bi bi-clock-history fs-4 text-warning"></i>
                <div class="small">Login</div>
                <strong>{{ $rumah->status_login ?? 'Offline' }}</strong>
            </div>
        </div>
    </div>

    <!-- ================= MENU PROFIL ================= -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="list-group list-group-flush">

            <a href="{{ route('profil.dataPribadi') }}"
                class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-person me-2 text-success"></i>
                    Data Pribadi
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-card-checklist me-2 text-primary"></i>
                    Data Keluarga
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-lock me-2 text-danger"></i>
                    Keamanan Akun
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-qr-code me-2 text-primary"></i>
                    Digital ID
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

            <a href="#" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-info-circle me-2 text-info"></i>
                    Tentang Aplikasi
                </div>
                <i class="bi bi-chevron-right"></i>
            </a>

        </div>
    </div>

    <!-- ================= LOGOUT ================= -->
    <div class="card border-0 shadow-sm">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="list-group-item list-group-item-action text-danger text-center fw-bold">
            <i class="bi bi-box-arrow-right me-1"></i> Keluar
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

@endsection
