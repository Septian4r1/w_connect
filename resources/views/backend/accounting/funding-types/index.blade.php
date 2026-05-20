@extends('backend.layouts.app')

@section('content')
    <div class="coa-page">

        {{-- ===================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ===================================================== --}}
        <div class="coa-header">

            <div class="coa-header-left">

                <div class="coa-title-wrap">

                    <div class="coa-title-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>

                    <div>
                        <h2>Funding</h2>

                        <p>
                            Manajemen Funding,
                            Funding Type, Funding Account
                        </p>
                    </div>

                </div>

            </div>

        </div>

        {{-- ===================================================== --}}
        {{-- CONTENT --}}
        {{-- ===================================================== --}}
        <div class="row g-4 mt-1">

            {{-- ========================================= --}}
            {{-- FUNDING TYPE --}}
            {{-- ========================================= --}}
            <div class="col-lg-4">

                <div class="card coa-card border-0 shadow-sm h-100">

                    <div class="card-header coa-card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center gap-2">

                                <div class="coa-mini-icon">
                                    <i class="bi bi-tags"></i>
                                </div>

                                <div>
                                    <h5 class="mb-0">Funding Type</h5>

                                    <small>
                                        Master jenis dana
                                    </small>
                                </div>

                            </div>

                            <button class="btn btn-sm btn-primary" id="openCreateFundTypeModal">
                                <i class="bi bi-plus-lg"></i>
                            </button>

                        </div>

                    </div>

                    <div class="card-body p-0 d-flex flex-column">

                        <div class="table-responsive coa-table-scroll">

                            <table class="table align-middle mb-0">

                                <thead>
                                    <tr>
                                        <th width="90">Code</th>
                                        <th>Name</th>
                                        <th width="90">Status</th>
                                        <th width="90" class="text-center">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($fundTypes as $fundType)
                                        <tr>

                                            {{-- CODE --}}
                                            <td>
                                                <span class="badge bg-dark">
                                                    {{ $fundType->code }}
                                                </span>
                                            </td>

                                            {{-- NAME --}}
                                            <td>

                                                <div class="fw-semibold">
                                                    {{ $fundType->name }}
                                                </div>

                                                <small class="text-muted">
                                                    {{ $fundType->description }}
                                                </small>

                                            </td>

                                            {{-- STATUS --}}
                                            <td>

                                                @if ($fundType->is_active)
                                                    <span class="badge bg-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        Inactive
                                                    </span>
                                                @endif

                                            </td>

                                            {{-- ===================================================== --}}
                                            {{-- ACTION --}}
                                            {{-- ===================================================== --}}
                                            <td>

                                                <div class="coa-action-group">

                                                    {{-- EDIT --}}
                                                    <button type="button" class="coa-icon-btn warning btnEditFundType"
                                                        title="Edit" data-id="{{ $fundType->id }}"
                                                        data-code="{{ $fundType->code }}" data-name="{{ $fundType->name }}"
                                                        data-description="{{ $fundType->description }}"
                                                        data-status="{{ $fundType->is_active }}"
                                                        data-update-url="{{ route('management.funding-types.update', $fundType->id) }}">

                                                        <i class="bi bi-pencil-square"></i>

                                                    </button>

                                                    {{-- ===================================================== --}}
                                                    {{-- DELETE BUTTON --}}
                                                    {{-- ===================================================== --}}
                                                    <form
                                                        action="{{ route('management.funding-types.destroy', $fundType->id) }}"
                                                        method="POST" class="formDeleteFundingType"
                                                        data-code="{{ $fundType->code }}"
                                                        data-name="{{ $fundType->name }}"
                                                        data-description="{{ $fundType->description }}">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="coa-icon-btn danger" title="Delete">

                                                            <i class="bi bi-trash"></i>

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="text-muted">
                                                    Data funding type belum tersedia
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="p-3 border-top">
                                {{ $fundTypes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ========================================= --}}
            {{-- FUNDING ACCOUNT --}}
            {{-- ========================================= --}}
            <div class="col-lg-8">

                <div class="card coa-card border-0 shadow-sm h-100">

                    <div class="card-header coa-card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center gap-2">

                                <div class="coa-mini-icon">
                                    <i class="bi bi-wallet2"></i>
                                </div>

                                <div>
                                    <h5 class="mb-0">Funding Account</h5>

                                    <small>
                                        Daftar rekening dana
                                    </small>
                                </div>

                            </div>

                            <button class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg"></i>
                            </button>

                        </div>

                    </div>

                    <div class="card-body p-0 d-flex flex-column">

                        <div class="table-responsive coa-table-scroll">

                            <table class="table align-middle mb-0">

                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Funding Name</th>
                                        <th>Type</th>
                                        <th>RW / RT</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td>
                                            <strong>RW016</strong>
                                        </td>

                                        <td>
                                            Dana RW 016
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">
                                                Dana RW
                                            </span>
                                        </td>

                                        <td>
                                            RW 016
                                        </td>

                                        <td>
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>RT001</strong>
                                        </td>

                                        <td>
                                            Dana RT 001
                                        </td>

                                        <td>
                                            <span class="badge bg-info">
                                                Dana RT
                                            </span>
                                        </td>

                                        <td>
                                            RW 016 / RT 001
                                        </td>

                                        <td>
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>SMP001</strong>
                                        </td>

                                        <td>
                                            Dana Sampah
                                        </td>

                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                Sampah
                                            </span>
                                        </td>

                                        <td>
                                            RW 016
                                        </td>

                                        <td>
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('backend.accounting.components.modal_tambah_funding-types')

    {{-- ===================================================== --}}
    {{-- MODAL EDIT FUND TYPE --}}
    {{-- ===================================================== --}}
    <div class="coa-modal" id="editFundTypeModal">

        {{-- OVERLAY --}}
        <div class="coa-modal-overlay closeEditFundTypeModal"></div>

        {{-- MODAL BOX --}}
        <div class="coa-modal-box">

            {{-- ========================================= --}}
            {{-- MODAL HEADER --}}
            {{-- ========================================= --}}
            <div class="coa-modal-header">

                <div class="coa-modal-title">

                    <div class="coa-modal-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>

                        <h4>
                            Edit Funding Type
                        </h4>

                        <p>
                            Update data funding type
                        </p>

                    </div>

                </div>

                <button type="button" class="coa-modal-close closeEditFundTypeModal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            {{-- ========================================= --}}
            {{-- FORM --}}
            {{-- ========================================= --}}
            <form id="editFundTypeForm" method="POST">

                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- CODE --}}
                    <div class="form-group">

                        <label>
                            Code
                        </label>

                        <input type="text" name="code" id="edit_code" class="form-control"
                            placeholder="Contoh: RW-OPS" required>

                        <small class="form-hint">
                            Gunakan kode unik funding
                        </small>

                    </div>

                    {{-- STATUS --}}
                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <select name="is_active" id="edit_is_active" class="form-select">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                    </div>

                    {{-- NAME --}}
                    <div class="form-group full">

                        <label>
                            Funding Name
                        </label>

                        <input type="text" name="name" id="edit_name" class="form-control"
                            placeholder="Contoh: Dana RW" required>

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="form-group full">

                        <label>
                            Description
                        </label>

                        <textarea name="description" id="edit_description" rows="4" class="form-control"
                            placeholder="Deskripsi funding type..."></textarea>

                    </div>

                </div>

                {{-- ACTION --}}
                <div class="modal-actions">

                    {{-- CANCEL --}}
                    <button type="button" class="coa-action-btn cancel-btn closeEditFundTypeModal">

                        <span class="btn-icon">
                            <i class="bi bi-x-lg"></i>
                        </span>

                        <span>
                            Batal
                        </span>

                    </button>

                    {{-- SUBMIT --}}
                    <button type="submit" class="coa-action-btn submit-btn">

                        <span class="btn-icon">
                            <i class="bi bi-check2"></i>
                        </span>

                        <span>
                            Update Funding Type
                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>


    @include('backend.accounting.funding-types.style-funding-types')
@endsection
@include('backend.accounting.funding-types.script_funding-types')
