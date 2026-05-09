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
                            <b>Role</b>
                        </h6>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">
                        <form id="formSelectRole" method="POST" action="">
                            @csrf

                            <div class="row align-items-end g-2">

                                <div class="col-md-12">
                                    <label class="form-label small text-muted">Pilih Role</label>

                                    <select name="role_id" id="selectRole" class="form-select">
                                        <option value="">-- Pilih Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ optional($selectedRole)->id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>



                            </div>
                        </form>
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

                        {{-- 🔥 ACTION BUTTON --}}
                        <div class="d-flex align-items-center gap-2">

                            {{-- indikator perubahan --}}
                            <span id="unsavedBadge" class="badge bg-warning text-dark d-none">
                                Belum disimpan
                            </span>

                            <button id="btnSavePermissions" class="btn btn-sm btn-danger">
                                💾 Simpan
                            </button>

                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="card-body">

                        {{-- 🔍 SEARCH --}}
                        <input type="text" id="menuSearch" class="form-control mb-3" placeholder="🔍 Cari menu...">

                        <div class="table-responsive permission-table-wrapper">
                            <table id="menuTable" class="table modern-table w-100">
                                <thead>
                                    <tr class="text-muted small text-uppercase">
                                        <th>Menu</th>
                                        <th>Route</th>
                                        <th>Icon</th>
                                        <th colspan="2">Permissions</th>
                                    </tr>
                                </thead>

                                <tbody id="permissionTreeBody"></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>
    @include('backend.management.roles.style')
@endsection

@include('backend.management.menu.role_permissions.script_role_permissions')
