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
            {{-- FUND ACCOUNT LINKS --}}
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
                                    <h5 class="mb-0">Fund Account Links</h5>
                                    <small>Links akun dana & COA accounting</small>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-body p-0 d-flex flex-column">

                        <div class="table-responsive coa-table-scroll">

                            <table class="table align-middle mb-0 coa-fund-table">
                                <thead>
                                    <tr>
                                        <th width="120">Fund Code</th>
                                        <th width="220">Fund Name</th>
                                        <th>Organization</th>
                                        <th>COA Mapping</th>
                                        <th width="120">Default</th>
                                        <th width="120">Status</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse($fundLinks as $fundTypeId => $orgGroups)
                                        @foreach ($orgGroups as $orgKey => $group)
                                            @php
                                                $fundType = $group->fundType;
                                                $organization = $group->organization;
                                                $items = $group->items;

                                                // SAFE FIRST ITEM (untuk edit)
                                                $firstItem = $items->first();
                                            @endphp

                                            <tr>

                                                {{-- FUND CODE --}}
                                                <td>
                                                    <span class="badge bg-dark">
                                                        {{ $fundType?->code }}
                                                    </span>
                                                </td>

                                                {{-- FUND NAME --}}
                                                <td>
                                                    <div class="fw-semibold">
                                                        {{ $fundType?->name }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $fundType?->description }}
                                                    </small>
                                                </td>

                                                {{-- ORGANIZATION --}}
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $organization?->name ?? 'GLOBAL' }}
                                                    </span>
                                                </td>

                                                {{-- COA MAPPING --}}
                                                <td>

                                                    @php
                                                        $groupedByRole = $items->groupBy(function ($item) {
                                                            return strtolower($item->accountRole?->coa_type ?? 'other');
                                                        });
                                                    @endphp

                                                    @forelse($groupedByRole as $type => $rows)
                                                        <div class="mb-3">

                                                            <span class="badge bg-primary text-uppercase mb-2">
                                                                {{ $type }}
                                                            </span>

                                                            @foreach ($rows as $item)
                                                                <div class="small mb-1">
                                                                    <i class="bi bi-arrow-right"></i>

                                                                    <span class="fw-semibold">
                                                                        {{ $item->coa?->code }}
                                                                    </span>

                                                                    - {{ $item->coa?->name }}

                                                                    @if ($item->is_default)
                                                                        <span class="badge bg-success ms-1">default</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @empty
                                                        <span class="text-muted">Tidak ada mapping COA</span>
                                                    @endforelse

                                                </td>

                                                {{-- DEFAULT --}}
                                                <td>
                                                    @php
                                                        $default = $items->firstWhere('is_default', 1);
                                                    @endphp

                                                    @if ($default)
                                                        <span class="badge bg-primary">
                                                            {{ $default->accountRole?->coa_type }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                {{-- STATUS --}}
                                                <td>
                                                    @if ($items->first()?->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>

                                                {{-- ACTION --}}
                                                <td>
                                                    <div class="coa-action-group">

                                                        {{-- EDIT --}}
                                                        @if ($firstItem)
                                                            <a href="{{ route('management.funding-account.edit', encrypt($firstItem->id)) }}"
                                                                class="coa-icon-btn warning">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        @endif

                                                        {{-- DELETE --}}
                                                        <form method="POST" class="deleteFundTypeForm"
                                                            action="{{ route('management.funding-account.destroy', [
                                                                'fundTypeId' => $fundType?->id,
                                                                'organizationId' => $organization?->id,
                                                            ]) }}">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="coa-icon-btn danger"
                                                                data-fund="{{ $fundType?->name }}"
                                                                data-org-name="{{ $organization?->name }}"
                                                                data-org-code="{{ $organization?->code }}">

                                                                <i class="bi bi-trash"></i>

                                                            </button>
                                                        </form>

                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach

                                    @empty

                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                Belum ada fund account mapping
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>




            @include('backend.accounting.components.modal_tambah_funding-types')
            @include('backend.accounting.components.modal_edit_funding_types')

        </div>


        @include('backend.accounting.funding-types.style-funding-types')
        @include('backend.accounting.funding-types.script_funding-types')
        @include('backend.accounting.funding-types.script_funding_account')
    </div>

@endsection
