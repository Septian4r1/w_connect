@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid px-2 px-md-3">
        <div class="row">

            {{-- ================= LEFT: ROLES ================= --}}
            <div class="col-md-3 mb-3">

                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-3 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b>Permission</b>
                        </h6>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreatePermissions"
                            class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px; height:32px;">
                            +
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="permissionsTable" class="table modern-table w-100">
                                <thead>
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-3">Permissions</th>
                                        <th class="text-center pe-3">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($permissions as $permission)
                                        <tr>
                                            <td class="ps-3">
                                                <span class="badge-soft badge-info-soft">
                                                    {{ $permission->name }}
                                                </span>
                                            </td>

                                            <td class="text-center pe-3">
                                                <button class="btn-edit-permissions btn btn-sm btn-soft text-warning"
                                                    data-id="{{ encrypt($permission->id) }}"
                                                    data-name="{{ $permission->name }}">
                                                    <i class="bi bi-pencil-fill" style="font-size:12px;"></i>
                                                </button>

                                                <button class="btn-delete-permission btn btn-sm btn-soft text-danger"
                                                    data-id="{{ encrypt($permission->id) }}"
                                                    data-name="{{ $permission->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4">
                                                Belum ada permission
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

            </div>

            {{-- ================= RIGHT: MENU PERMISSIONS ================= --}}

            <div class="col-md-9 mb-3">

                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-dark border-0 d-flex justify-content-between align-items-center px-3 py-3">
                        <h6 class="mb-0 fw-semibold text-danger">
                            <b>Menu</b>
                        </h6>

                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreateMenu"
                            class="btn btn-sm btn-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width:32px; height:32px;">
                            +
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        <div class="table-wrapper-custom">
                            <table id="menuTable" class="table modern-table w-100">
                                <thead>
                                    <tr class="text-muted small text-uppercase">
                                        <th class="ps-3">Menu</th>
                                        <th>Route</th>
                                        <th>Icon</th>
                                        <th>Parent</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Permission</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="permissionTableBody">
                                    @forelse ($menus as $menu)
                                        @include('backend.management.menu.partials.row', [
                                            'menu' => $menu,
                                            'level' => 0,
                                        ])
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                Data tidak ada
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
    </div>

    @include('backend.management.menu.modal_edit')
    @include('backend.management.menu.modal_tambah_menu')
    @include('backend.management.menu.style_menu')
@endsection

@include('backend.management.menu.script_menu')
