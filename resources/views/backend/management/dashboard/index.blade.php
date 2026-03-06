@extends('backend.layouts.app')

@section('content')
    <div class="card radius-10 p-5">

        <div class="d-flex flex-column align-items-center text-center">

            <img src="{{ asset('images/logo_w_connect.png') }}" width="120" class="mb-4">

            <h3 class="fw-bold">
                Selamat Datang, {{ Auth::user()->name }}
            </h3>

            <p class="text-muted">
                Selamat datang di sistem management <strong>W-Connect</strong>.
                Silakan gunakan menu di sebelah kiri untuk mengelola data warga,
                keuangan, dan surat menyurat baik RT / RW.
            </p>

        </div>

    </div>
@endsection
