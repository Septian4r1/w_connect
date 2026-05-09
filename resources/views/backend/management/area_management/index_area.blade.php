@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row">

            {{-- ================= LEFT: RW ================= --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-3 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b>Data RW</b>
                        </h6>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body p-0">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th class="ps-3">RW</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($rws as $rw)
                                    <tr>
                                        <td class="ps-3 fw-semibold">
                                            RW {{ $rw->nama_rw }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-4 text-muted">
                                            Belum ada data RW
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- ================= RIGHT: RT ================= --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-4 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b>Data RT</b>
                        </h6>

                        <button type="button"
                            class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px; height:32px;" data-bs-toggle="modal" data-bs-target="#modalTambahRT">
                            +
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">

                        <table class="table modern-table w-100">
                            <thead>
                                <tr class="text-center small text-uppercase">
                                    <th>#</th>
                                    <th>RW</th>
                                    <th>RT</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($rts as $rt)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration + ($rts->currentPage() - 1) * $rts->perPage() }}
                                        </td>

                                        <td class="text-center">
                                            <span class="fw-semibold">
                                                RW {{ $rt->rw->nama_rw ?? '-' }}
                                            </span>
                                        </td>


                                        <td class="text-center">
                                            <span class="fw-semibold">
                                                RT {{ $rt->nama_rt ?? '-' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge-soft
                                            {{ $rt->status == 'aktif' ? 'badge-active' : 'badge-inactive' }}">
                                                {{ ucfirst($rt->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            Belum ada data RT
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $rts->links() }}
                        </div>

                    </div>

                </div>
            </div>

            {{-- ================= BLOCK ================= --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-4 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b>Data Block</b>
                        </h6>

                        <button type="button"
                            class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px; height:32px;" data-bs-toggle="modal" data-bs-target="#modalTambahBlock">
                            +
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table modern-table w-100" id="blockTable">
                                <thead>
                                    <tr class="text-center small text-uppercase">
                                        <th>#</th>
                                        <th>RW</th>
                                        <th>RT</th>
                                        <th>Block</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($blockList as $block)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td class="text-center">
                                                RW {{ $block->rt->rw->nama_rw ?? '-' }}
                                            </td>

                                            <td class="text-center">
                                                RT {{ $block->rt->nama_rt ?? '-' }}
                                            </td>

                                            <td class="text-center fw-semibold">
                                                {{ $block->nama_blok }}
                                            </td>

                                            <td class="text-center">
                                                <span
                                                    class="badge-soft {{ $block->status === 'aktif' ? 'badge-active' : 'badge-inactive' }}">
                                                    {{ ucfirst($block->status) }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <button class="btn btn-icon btn-sm btn-soft-warning" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditBlock" data-id="{{ encrypt($block->id) }}"
                                                    data-nama="{{ $block->nama_blok }}" data-status="{{ $block->status }}"
                                                    data-rt="{{ $block->rt_id }}"
                                                    data-rw="{{ $block->rt->rw->nama_rw ?? '-' }}" title="Edit Block">

                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                Belum ada data Block
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>


        </div>

        {{-- ================= Center : Data All ================= --}}
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4">

                {{-- HEADER --}}
                <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-4 py-3">
                    <h6 class="mb-0 fw-semibold text-danger">
                        <b>Teritory Wilayah</b>
                    </h6>


                </div>

                {{-- BODY --}}
                <div class="card-body">

                    @php
                        // 🔥 GROUP BY RT
                        $groupedBlocks = $blocks->getCollection()->groupBy('rt_id');
                    @endphp

                    <div class="table-responsive">
                        <table class="table modern-table w-100">
                            <thead>
                                <tr class="text-center small text-uppercase">
                                    <th>#</th>
                                    <th>RW</th>
                                    <th>RT</th>
                                    <th>Blocks</th>


                                </tr>
                            </thead>

                            <tbody>
                                @forelse($blocks as $rt)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration + ($blocks->currentPage() - 1) * $blocks->perPage() }}
                                        </td>

                                        <td class="text-center">
                                            RW {{ $rt->rw->nama_rw ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            RT {{ $rt->nama_rt ?? '-' }}
                                        </td>

                                        <td class="text text-center">
                                            @forelse($rt->blocks as $block)
                                                <span
                                                    class="badge-soft {{ $block->status === 'aktif' ? 'badge-active' : 'badge-inactive' }} me-1 mb-1 d-inline-block">
                                                    {{ $block->nama_blok }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">Belum ada block</span>
                                            @endforelse
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            Belum ada data RT
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- pagination tetap --}}
                    <div class="mt-3">
                        {{ $blocks->links() }}
                    </div>

                </div>

            </div>
        </div>

    </div>

    @include('backend.management.area_management.modal_tambah_rt')
    @include('backend.management.area_management.modal_tambah_block')
    @include('backend.management.area_management.style')

    {{-- ================= MODAL EDIT BLOCK ================= --}}
    <div class="modal fade" id="modalEditBlock" tabindex="-1" aria-labelledby="modalEditBlockLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <form id="formEditBlock" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- HEADER --}}
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title fw-semibold" id="modalEditBlockLabel">
                            Edit Block
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-4 py-3">

                        {{-- RW (readonly info) --}}
                        <div class="mb-3">
                            <label class="form-label">RW</label>
                            <input type="text" id="edit_rw" class="form-control" readonly>
                        </div>

                        {{-- RT (readonly info) --}}
                        <div class="mb-3">
                            <label class="form-label">RT</label>
                            <select name="rt_id" id="edit_rt_id" class="form-select" required>
                                <option value="">-- Pilih RT --</option>
                                @foreach ($rts_all as $rt)
                                    <option value="{{ $rt->id }}">
                                        RT {{ $rt->nama_rt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Nama Block --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Block</label>
                            <input type="text" name="nama_blok" id="edit_nama" class="form-control" required>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non Aktif</option>
                            </select>
                        </div>


                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


@endsection

@include('backend.management.area_management.script')
