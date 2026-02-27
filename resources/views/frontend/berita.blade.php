@extends('frontend.layouts.app')

@section('title','Berita')
@section('header-title','Berita')

@section('content')
@for($i=1;$i<=5;$i++)
    <div class="card p-3 mb-2">
        <strong>Berita {{ $i }}</strong>
        <div class="text-muted">Isi berita warga {{ $i }}</div>
    </div>
@endfor
@endsection
