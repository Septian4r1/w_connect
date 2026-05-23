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
                                        <th class="text text-center">Organization</th>
                                        <th class="text text-center">Mulai Menjabat</th>
                                        <th class="text text-center">Akhir Menjabat</th>
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

                                            <td class="text-center">
                                                @if ($pw->organization)
                                                    <span class="badge-soft badge-org">
                                                        <i class="bx bx-buildings"></i>
                                                        {{ strtoupper($pw->organization->type) }} -
                                                        {{ $pw->organization->code }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center start-date-cell">
                                                {{ $pw->start_date_format }}
                                            </td>

                                            <td class="text-center">
                                                @if ($pw->status === 'aktif')
                                                    <span class="badge-status badge-active-soft">
                                                        <i class="bx bx-up-arrow-alt"></i>
                                                        Masih Menjabat
                                                    </span>
                                                @else
                                                    <span class="badge-status badge-inactive-soft">
                                                        <i class="bx bx-down-arrow-alt"></i>
                                                        Masa Jabatan Berakhir
                                                    </span>
                                                @endif
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
                                                    {{-- EDIT --}}
                                                    <a href="javascript:void(0);" class="btn-soft edit btn-edit-pengurus"
                                                        data-id="{{ $pw->id }}" data-user_id="{{ $pw->user_id }}"
                                                        data-role_id="{{ $pw->role_id }}"
                                                        data-role_name="{{ $pw->role->name }}"
                                                        data-org_id="{{ $pw->organization_id }}"
                                                        data-rw_id="{{ $pw->rw_id }}" data-rt_id="{{ $pw->rt_id }}"
                                                        data-status="{{ $pw->status }}"
                                                        data-email="{{ $pw->user->email }}"
                                                        data-name="{{ $pw->user->name }}"
                                                        data-start_date="{{ $pw->start_date }}"
                                                        data-end_date="{{ $pw->end_date }}">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    {{-- TOGGLE STATUS --}}
                                                    @php
                                                        $isSuperAdmin = auth()->user()->hasRole('super_admin');
                                                    @endphp

                                                    <a href="javascript:void(0);"
                                                        class="btn-soft btn-toggle-status {{ $pw->status == 'aktif' ? 'text-success' : 'text-danger' }} {{ $isSuperAdmin ? '' : 'disabled opacity-50' }}"
                                                        data-id="{{ $pw->id }}" data-status="{{ $pw->status }}"
                                                        data-user="{{ $pw->user->name ?? '-' }}"
                                                        data-role="{{ $pw->role->name ?? '-' }}"
                                                        data-org="{{ $pw->organization->name ?? '-' }}"
                                                        data-rw="{{ $pw->rw->nama_rw ?? '-' }}"
                                                        data-rt="{{ $pw->rt->nama_rt ?? '-' }}" title="Toggle Status">

                                                        <i
                                                            class="bx {{ $pw->status == 'aktif' ? 'bx-caret-up' : 'bx-caret-down' }}"></i>
                                                    </a>

                                                    <a href="javascript:void(0);"
                                                        class="btn-soft delete btn-delete-pengurus"
                                                        data-id="{{ $pw->id }}"
                                                        data-user="{{ $pw->user->name ?? '-' }}"
                                                        data-url="{{ route('management.pengurus_wilayah.delete', $pw->id) }}"
                                                        data-role="{{ $pw->role->name ?? '-' }}"
                                                        data-org="{{ $pw->organization->name ?? '-' }}"
                                                        data-rw="{{ $pw->rw->nama_rw ?? '-' }}"
                                                        data-rt="{{ $pw->rt->nama_rt ?? '-' }}" title="Hapus">
                                                        <i class="bx bx-trash "></i>
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
    @include('backend.management.roles.modal_edit_akses_user')




@endsection

@include('backend.management.roles.script_roles')
