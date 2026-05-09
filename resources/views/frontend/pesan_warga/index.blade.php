@extends('frontend.layouts.app')

@section('title', 'Pesan')
@section('header-title', 'Pesan')

@section('content')

    <div class="container">

        <h6 class="mb-3 fw-bold">Pesan</h6>

        {{-- KETERANGAN STATUS --}}
        {{-- <div class="mb-3 small">
            <span class="badge bg-success me-2">Pesan Baru</span>
            <span class="badge bg-warning text-dark">Update Status</span>
        </div> --}}

        <div class="accordion mobile-accordion" id="accordionPengajuan">

            @forelse ($pengajuanList as $i => $pengajuan)

                <div class="accordion-item
            @if ($pengajuan->is_new) bg-success-subtle border-success
            @elseif ($pengajuan->has_update) bg-warning-subtle border-warning @endif"
                    data-pengajuan="{{ $pengajuan->no_pengajuan }}">

                    <h2 class="accordion-header">

                        @php
                            $notif = $notifications->first(function ($n) use ($pengajuan) {
                                return ($n->data['no_pengajuan'] ?? null) == $pengajuan->no_pengajuan;
                            });
                        @endphp

                        <button class="accordion-button collapsed mark-read" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $i }}"
                            data-id="{{ $notif ? Crypt::encryptString($notif->id) : '' }}">

                            <div class="w-100">

                                <div class="fw-bold text-primary small">
                                    {{ $pengajuan->no_pengajuan }}

                                    @if ($pengajuan->is_new)
                                        <span class="badge bg-success ms-2">Pesan Baru</span>
                                    @elseif ($pengajuan->has_update)
                                        <span class="badge bg-warning text-dark ms-2">Update</span>
                                    @endif
                                </div>

                                <div class="small text-muted">
                                    {{ $pengajuan->nama_pengaju }}
                                </div>

                                <div class="small">
                                    Perubahan :
                                    <strong>{{ $pengajuan->field_perubahan }}</strong>
                                </div>

                            </div>

                        </button>

                    </h2>

                    <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                        data-bs-parent="#accordionPengajuan">

                        <div class="accordion-body">

                            {{-- TIMELINE STATUS --}}
                            <ul class="timeline">

                                @foreach ($pengajuan->approvals as $loopIndex => $step)
                                    <li
                                        class="timeline-item
                            @if ($loop->last) timeline-latest @endif">

                                        {{-- ICON --}}
                                        <div class="timeline-icon">

                                            @if ($step->status == 'approved')
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                            @elseif ($step->status == 'rejected')
                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                            @else
                                                <i class="bi bi-hourglass-split text-primary"></i>
                                            @endif

                                        </div>

                                        {{-- CONTENT --}}
                                        <div class="timeline-content">

                                            <div class="fw-semibold small">

                                                @switch($step->level)
                                                    @case('admin')
                                                        <i class="bi bi-shield-lock"></i>
                                                        Verifikasi Admin
                                                    @break

                                                    @case('rt')
                                                        <i class="bi bi-house"></i>
                                                        Verifikasi RT
                                                    @break

                                                    @case('rw')
                                                        <i class="bi bi-clipboard-check"></i>
                                                        Verifikasi RW
                                                    @break

                                                    @default
                                                        Proses
                                                @endswitch

                                            </div>

                                            <div class="small text-muted">
                                                {{ $step->created_at->format('d M Y H:i') }}
                                            </div>

                                        </div>

                                    </li>
                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

                @empty

                    <div class="text-center text-muted py-4">
                        Belum ada pengajuan
                    </div>

                @endforelse

            </div>

        </div>
        </div>


    @endsection
