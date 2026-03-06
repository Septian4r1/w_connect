@extends('backend.layouts.app')

@section('content')

<div class="text-center p-5">

    <img src="https://cdn-icons-png.flaticon.com/512/5956/5956592.png"
         width="120"
         class="mb-4">

    <h3 class="fw-bold">
        {{ $title }}
    </h3>

    <p class="text-muted mt-3">
        Halaman ini sedang dalam tahap pengembangan.
    </p>

    <span class="badge bg-warning text-dark mt-2">
        Under Construction
    </span>

</div>

@endsection
