@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row">

            {{-- ================= LEFT: ROLES ================= --}}
            <div class="col-md-3 mb-3">

                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-3 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b> Daftar Strukture </b>
                        </h6>

                        {{-- BUTTON TAMBAH --}}
                        <button type="button"
                            class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px; height:32px;" data-bs-toggle="modal" data-bs-target="#modalCreateRole">
                            +
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="rolesTable" class="table modern-table">
                                <thead>
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-3">Role</th>
                                        <th class="text-center pe-3">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($roles as $role)
                                        <tr>

                                            {{-- ROLE --}}
                                            <td data-label="Role" class="ps-3">
                                                <div class="fw-semibold text-dark">
                                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                </div>
                                            </td>

                                            {{-- ACTION --}}
                                            <td data-label="Action" class="text-center pe-3">
                                                <div class="d-flex justify-content-center gap-2">

                                                    <a href="#" class="btn-soft btn-edit-role"
                                                        data-id="{{ $role->id }}" data-name="{{ $role->name }}"
                                                        title="Edit">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4 text-muted">
                                                Data role belum tersedia
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ================= RIGHT: PERMISSIONS ================= --}}
            {{-- ================= RIGHT: PERMISSIONS ================= --}}
            <div class="col-md-9 ">

                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-4 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b>Akses User</b>
                        </h6>

                        {{-- BUTTON TAMBAH --}}
                        <button type="button"
                            class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px; height:32px;" data-bs-toggle="modal" data-bs-target="#modalTambahPengurus">
                            +
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="permissionTable" class="table modern-table w-100">
                                <thead>
                                    <tr class="text-dark small text-uppercase text-center">
                                        <th class="text text-center">#</th>
                                        <th class="text text-center">Image</th>
                                        <th class="text text-center">Nama</th>
                                        <th class="text text-center">No Rumah</th>
                                        <th class="text text-center">No Tlp</th>
                                        <th class="text text-center">Role</th>
                                        <th class="text text-center">RT</th>
                                        <th class="text text-center">RW</th>
                                        <th class="text text-center">Status</th>
                                        <th class="text text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($pengurus as $index => $pw)
                                        <tr>
                                            {{-- NO --}}
                                            <td class="text-center">{{ $index + 1 }}</td>

                                            {{-- FOTO --}}
                                            <td class="text-center">
                                                <img src="{{ optional($pw->user?->warga)->foto
                                                    ? asset($pw->user->warga->foto)
                                                    : asset('frontend/data_warga/image/sample/user.png') }}"
                                                    class="table-img">
                                            </td>

                                            {{-- NAMA --}}
                                            <td>
                                                {{ optional($pw->user?->warga)->nama ?? '-' }}
                                            </td>

                                            {{-- RUMAH --}}
                                            <td class="text-center">
                                                {{ optional($pw->user?->warga?->keluarga?->rumah)->nomor_rumah ?? '-' }}
                                            </td>

                                            {{-- HP --}}
                                            <td class="text-center">
                                                {{ optional($pw->user?->warga)->no_hp ?? '-' }}
                                            </td>

                                            {{-- ROLE --}}
                                            <td class="text-center">
                                                <span class="badge-soft badge-role">
                                                    <i class="bx bx-user-circle"></i>
                                                    {{ optional($pw->role)->name ?? '-' }}
                                                </span>
                                            </td>

                                            {{-- RT --}}
                                            <td class="text-center">
                                                <span class="badge-soft badge-rt">
                                                    <i class="bx bx-map"></i>
                                                    RT {{ optional($pw->rt)->nama_rt ?? '-' }}
                                                </span>
                                            </td>

                                            {{-- RW --}}
                                            <td class="text-center">
                                                <span class="badge-soft badge-rw">
                                                    <i class="bx bx-map-pin"></i>
                                                    RW {{ optional($pw->rw)->nama_rw ?? '-' }}
                                                </span>
                                            </td>

                                            {{-- STATUS --}}
                                            <td class="text-center">
                                                <span
                                                    class="badge-soft {{ $pw->status == 'aktif' ? 'badge-active' : 'badge-inactive' }}">
                                                    <i
                                                        class="bx {{ $pw->status == 'aktif' ? 'bx-check-circle' : 'bx-x-circle' }}"></i>
                                                    {{ ucfirst($pw->status ?? '-') }}
                                                </span>
                                            </td>

                                            {{-- ACTION --}}
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="#" class="btn-soft view" title="Lihat">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="#" class="btn-soft edit" title="Edit">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    <a href="#" class="btn-soft delete" title="Hapus">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('backend.management.roles.style')
    @include('backend.management.roles.modal_tambah_roles')
    @include('backend.management.roles.modal_tambah_akses_user')
@endsection

@include('backend.management.roles.script_roles')
