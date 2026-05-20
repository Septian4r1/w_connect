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
            {{-- FUND ACCOUNT MAPPINGS --}}
            {{-- ========================================= --}}
            <div class="col-lg-8">

                <div class="card coa-card border-0 shadow-sm h-100">

                    {{-- ========================================= --}}
                    {{-- HEADER --}}
                    {{-- ========================================= --}}
                    <div class="card-header coa-card-header">

                        <div class="d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center gap-2">

                                <div class="coa-mini-icon">
                                    <i class="bi bi-wallet2"></i>
                                </div>

                                <div>
                                    <h5 class="mb-0">
                                        Fund Account Mapping
                                    </h5>

                                    <small>
                                        Mapping akun dana & COA accounting
                                    </small>
                                </div>

                            </div>

                            <a href="#" class="btn btn-sm btn-primary">

                                <i class="bi bi-plus-lg"></i>

                                <span class="ms-1">
                                    Add Mapping
                                </span>
                            </a>

                        </div>

                    </div>

                    {{-- ========================================= --}}
                    {{-- BODY --}}
                    {{-- ========================================= --}}
                    <div class="card-body p-0 d-flex flex-column">

                        <div class="table-responsive coa-table-scroll">

                            <table class="table align-middle mb-0 coa-fund-table">

                                {{-- ========================================= --}}
                                {{-- TABLE HEADER --}}
                                {{-- ========================================= --}}
                                <thead>

                                    <tr>

                                        <th width="120">
                                            Fund Code
                                        </th>

                                        <th width="220">
                                            Fund Name
                                        </th>

                                        <th>
                                            COA Mapping
                                        </th>

                                        <th width="120">
                                            Default
                                        </th>

                                        <th width="120">
                                            Status
                                        </th>

                                        <th width="120">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                {{-- ========================================= --}}
                                {{-- TABLE BODY --}}
                                {{-- ========================================= --}}
                                <tbody>

                                    @forelse($fundMappings as $mapping)
                                        <tr>

                                            {{-- CODE --}}
                                            <td>
                                                <strong>
                                                    {{ $mapping->fundType?->code }}
                                                </strong>
                                            </td>

                                            {{-- FUND NAME --}}
                                            <td>

                                                <div class="fw-semibold">
                                                    {{ $mapping->fundType?->name }}
                                                </div>

                                                @if ($mapping->notes)
                                                    <small class="text-muted">
                                                        {{ $mapping->notes }}
                                                    </small>
                                                @endif

                                            </td>

                                            {{-- ACCOUNT INFO --}}
                                            <td>

                                                <div class="small">

                                                    {{-- CASH --}}
                                                    <div class="mb-1">
                                                        <span class="fw-semibold">
                                                            Cash:
                                                        </span>

                                                        {{ $mapping->cashAccount?->code }}
                                                        -
                                                        {{ $mapping->cashAccount?->name }}
                                                    </div>

                                                    {{-- REVENUE --}}
                                                    <div class="mb-1">
                                                        <span class="fw-semibold">
                                                            Revenue:
                                                        </span>

                                                        {{ $mapping->revenueAccount?->code }}
                                                        -
                                                        {{ $mapping->revenueAccount?->name }}
                                                    </div>

                                                    {{-- EXPENSE --}}
                                                    <div class="mb-1">
                                                        <span class="fw-semibold">
                                                            Expense:
                                                        </span>

                                                        {{ $mapping->expenseAccount?->code }}
                                                        -
                                                        {{ $mapping->expenseAccount?->name }}
                                                    </div>

                                                </div>

                                            </td>

                                            {{-- DEFAULT --}}
                                            <td>

                                                @if ($mapping->is_default)
                                                    <span class="badge bg-primary">
                                                        Default
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        Optional
                                                    </span>
                                                @endif

                                            </td>

                                            {{-- STATUS --}}
                                            <td>

                                                @if ($mapping->is_active)
                                                    <span class="badge bg-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        Inactive
                                                    </span>
                                                @endif

                                            </td>

                                            <td>

                                                <div class="coa-action-group">

                                                    {{-- EDIT --}}
                                                    <button type="button" class="coa-icon-btn warning btnEditFundType"
                                                        title="Edit">

                                                        <i class="bi bi-pencil-square"></i>

                                                    </button>

                                                    {{-- ===================================================== --}}
                                                    {{-- DELETE BUTTON --}}
                                                    {{-- ===================================================== --}}
                                                    <form
                                                       >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="coa-icon-btn danger"
                                                            title="Delete">

                                                            <i class="bi bi-trash"></i>

                                                        </button>

                                                    </form>

                                                </div>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="5" class="text-center py-4">

                                                <div class="text-muted">

                                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>

                                                    Belum ada mapping dana.

                                                </div>

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

    @include('backend.accounting.components.modal_tambah_funding-types')
    @include('backend.accounting.components.modal_edit_funding_types')





    @include('backend.accounting.funding-types.style-funding-types')
@endsection
@include('backend.accounting.funding-types.script_funding-types')
