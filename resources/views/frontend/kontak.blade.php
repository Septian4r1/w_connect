@extends('frontend.layouts.app')

@section('title','Kontak')
@section('header-title','Kontak')

@section('content')
    <div class="card p-2 mb-2">
        <strong class="fs-6">Kontak Support Pengisian Data Awal</strong>
        <p class="text-muted mb-2" style="font-size: 12px;">
            Silakan hubungi Humas RW berikut jika mengalami kesulitan saat pengisian data awal.
        </p>

        <ul class="list-group list-group-flush">
            <li class="list-group-item p-2">
                <small>
                    <strong>Humas RW 1</strong><br>
                    👤 Nama : Pak Hasan<br>
                    📞 WhatsApp : +62 857-1556-6290
                </small>
            </li>
            <li class="list-group-item p-2">
                <small>
                    <strong>Humas RW 2</strong><br>
                    👤 Nama : Pak Maman <br>
                    📞 WhatsApp : +62 812-9772-0078
                </small>
            </li>
            <li class="list-group-item p-2">
                <small>
                    <strong>Humas RW 3</strong><br>
                    👤 Nama : Pak Supri<br>
                    📞 WhatsApp : +62 895-3380-88034
                </small>
            </li>
            <li class="list-group-item p-2">
                <small>
                    <strong>Humas RW 4</strong><br>
                    👤 Nama : Pak Anjang <br>
                    📞 WhatsApp : +62 812-9296-5431
                </small>
            </li>
        </ul>
    </div>
@endsection
