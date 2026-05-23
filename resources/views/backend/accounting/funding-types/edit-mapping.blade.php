@extends('backend.layouts.app')

@section('content')
    <div class="coa-page">

        <div class="container-fluid">

            {{-- ===================================================== --}}
            {{-- PAGE HEADER --}}
            {{-- ===================================================== --}}
            <div class="coa-page-header">

                <div class="coa-page-title">

                    <div class="coa-page-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>

                    <div>
                        <h4>Edit Fund Account Mapping</h4>

                        <p>
                            Manage COA mapping and configure default accounts
                            dynamically for each organization and fund type.
                        </p>
                    </div>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- MAIN CARD --}}
            {{-- ===================================================== --}}
            <div class="card coa-card">

                <form method="POST" action="{{ route('management.funding-account.update', encrypt($current->id)) }}"
                    class="coa-form" id="fundMappingForm">

                    @csrf
                    @method('PUT')

                    {{-- ===================================================== --}}
                    {{-- SCROLL WRAPPER --}}
                    {{-- ===================================================== --}}
                    <div class="coa-scroll-wrapper">

                        {{-- ===================================================== --}}
                        {{-- FORM BODY --}}
                        {{-- ===================================================== --}}
                        <div class="card-body">

                            {{-- ===================================================== --}}
                            {{-- FUND CONFIGURATION --}}
                            {{-- ===================================================== --}}
                            <div class="mapping-section">

                                <div class="mapping-header">

                                    <div class="mapping-icon asset">
                                        <i class="bi bi-sliders"></i>
                                    </div>

                                    <div>
                                        <h6>Fund Configuration</h6>

                                        <p>
                                            Configure organization and fund type.
                                        </p>
                                    </div>

                                </div>

                                <div class="row g-4">

                                    <div class="col-lg-6">

                                        <label class="form-label">
                                            Fund Type
                                        </label>

                                        <select name="fund_type_id" class="form-control" required>

                                            <option value="">
                                                -- Pilih Fund Type --
                                            </option>

                                            @foreach ($fundTypes as $fund)
                                                <option value="{{ $fund->id }}"
                                                    {{ $fund->id == $current->fund_type_id ? 'selected' : '' }}>

                                                    {{ $fund->code }} - {{ $fund->name }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-lg-6">

                                        <label class="form-label">
                                            Organization
                                        </label>

                                        <select name="organization_id" class="form-control" required>

                                            <option value="">
                                                -- Pilih Organization --
                                            </option>

                                            @foreach ($organizations as $org)
                                                <option value="{{ $org->id }}"
                                                    {{ $org->id == $current->organization_id ? 'selected' : '' }}>

                                                    {{ strtoupper($org->type) }}
                                                    -
                                                    {{ $org->code }}
                                                    -
                                                    {{ $org->name }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>

                            </div>

                            @php
                                $mapped = $mappedCoaIds ?? collect();
                            @endphp

                            {{-- ===================================================== --}}
                            {{-- ASSET --}}
                            {{-- ===================================================== --}}
                            <div class="mapping-section">

                                <div class="mapping-header">

                                    <div class="mapping-icon asset">
                                        <i class="bi bi-wallet2"></i>
                                    </div>

                                    <div>

                                        <h6>Asset Accounts</h6>

                                        <p>
                                            Select asset accounts and define
                                            default account mapping.
                                        </p>

                                    </div>

                                </div>

                                {{-- GRID --}}
                                <div class="coa-grid">

                                    @forelse($accounts['asset'] ?? [] as $coa)
                                        <label class="coa-item">

                                            <input type="checkbox" class="mapping-checkbox" data-target="default_asset_id"
                                                data-code="{{ $coa->code }}" data-name="{{ $coa->name }}"
                                                value="{{ $coa->id }}" name="mapping[asset][]"
                                                {{ isset($mapped[$coa->id]) ? 'checked' : '' }}>

                                            <div class="coa-item-content">

                                                <div class="coa-item-title">
                                                    {{ $coa->code }}
                                                </div>

                                                <div class="coa-item-subtitle">
                                                    {{ $coa->name }}
                                                </div>

                                            </div>

                                        </label>

                                    @empty

                                        <div class="coa-empty">

                                            <i class="bi bi-inbox me-2"></i>

                                            No asset accounts found

                                        </div>
                                    @endforelse

                                </div>

                                {{-- DEFAULT --}}
                                <div class="default-box">

                                    <label class="form-label">
                                        Default Asset Account
                                    </label>

                                    <select name="default_asset_id" id="default_asset_id" class="form-control">

                                        <option value="">
                                            -- Pilih Default Asset --
                                        </option>

                                        @foreach ($accounts['asset'] ?? [] as $coa)
                                            @if (isset($mapped[$coa->id]))
                                                <option value="{{ $coa->id }}"
                                                    {{ ($defaultByType['asset'] ?? null) == $coa->id ? 'selected' : '' }}>

                                                    {{ $coa->code }}
                                                    -
                                                    {{ $coa->name }}

                                                </option>
                                            @endif
                                        @endforeach

                                    </select>

                                    <small>
                                        Only checked accounts can be selected
                                        as default account.
                                    </small>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- LIABILITY --}}
                            {{-- ===================================================== --}}
                            <div class="mapping-section">

                                <div class="mapping-header">

                                    <div class="mapping-icon liability">
                                        <i class="bi bi-bank2"></i>
                                    </div>

                                    <div>

                                        <h6>Liability Accounts</h6>

                                        <p>
                                            Select liability accounts and define
                                            default liability mapping.
                                        </p>

                                    </div>

                                </div>

                                {{-- GRID --}}
                                <div class="coa-grid">

                                    @forelse($accounts['liability'] ?? [] as $coa)
                                        <label class="coa-item">

                                            <input type="checkbox" class="mapping-checkbox"
                                                data-target="default_liability_id" data-code="{{ $coa->code }}"
                                                data-name="{{ $coa->name }}" value="{{ $coa->id }}"
                                                name="mapping[liability][]"
                                                {{ isset($mapped[$coa->id]) ? 'checked' : '' }}>

                                            <div class="coa-item-content">

                                                <div class="coa-item-title">
                                                    {{ $coa->code }}
                                                </div>

                                                <div class="coa-item-subtitle">
                                                    {{ $coa->name }}
                                                </div>

                                            </div>

                                        </label>

                                    @empty

                                        <div class="coa-empty">

                                            <i class="bi bi-inbox me-2"></i>

                                            No liability accounts found

                                        </div>
                                    @endforelse

                                </div>

                                {{-- DEFAULT --}}
                                <div class="default-box">

                                    <label class="form-label">
                                        Default Liability Account
                                    </label>

                                    <select name="default_liability_id" id="default_liability_id" class="form-control">

                                        <option value="">
                                            -- Pilih Default Liability --
                                        </option>

                                        @foreach ($accounts['liability'] ?? [] as $coa)
                                            @if (isset($mapped[$coa->id]))
                                                <option value="{{ $coa->id }}"
                                                    {{ ($defaultByType['liability'] ?? null) == $coa->id ? 'selected' : '' }}>

                                                    {{ $coa->code }}
                                                    -
                                                    {{ $coa->name }}

                                                </option>
                                            @endif
                                        @endforeach

                                    </select>

                                    <small>
                                        Only checked accounts can be selected
                                        as default account.
                                    </small>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- REVENUE --}}
                            {{-- ===================================================== --}}
                            <div class="mapping-section">

                                <div class="mapping-header">

                                    <div class="mapping-icon revenue">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>

                                    <div>

                                        <h6>Revenue Accounts</h6>

                                        <p>
                                            Select revenue accounts and define
                                            default revenue mapping.
                                        </p>

                                    </div>

                                </div>

                                {{-- GRID --}}
                                <div class="coa-grid">

                                    @forelse($accounts['revenue'] ?? [] as $coa)
                                        <label class="coa-item">

                                            <input type="checkbox" class="mapping-checkbox" data-target="default_revenue_id"
                                                data-code="{{ $coa->code }}" data-name="{{ $coa->name }}"
                                                value="{{ $coa->id }}" name="mapping[revenue][]"
                                                {{ isset($mapped[$coa->id]) ? 'checked' : '' }}>

                                            <div class="coa-item-content">

                                                <div class="coa-item-title">
                                                    {{ $coa->code }}
                                                </div>

                                                <div class="coa-item-subtitle">
                                                    {{ $coa->name }}
                                                </div>

                                            </div>

                                        </label>

                                    @empty

                                        <div class="coa-empty">

                                            <i class="bi bi-inbox me-2"></i>

                                            No revenue accounts found

                                        </div>
                                    @endforelse

                                </div>

                                {{-- DEFAULT --}}
                                <div class="default-box">

                                    <label class="form-label">
                                        Default Revenue Account
                                    </label>

                                    <select name="default_revenue_id" id="default_revenue_id" class="form-control">

                                        <option value="">
                                            -- Pilih Default Revenue --
                                        </option>

                                        @foreach ($accounts['revenue'] ?? [] as $coa)
                                            @if (isset($mapped[$coa->id]))
                                                <option value="{{ $coa->id }}"
                                                    {{ ($defaultByType['revenue'] ?? null) == $coa->id ? 'selected' : '' }}>

                                                    {{ $coa->code }}
                                                    -
                                                    {{ $coa->name }}

                                                </option>
                                            @endif
                                        @endforeach

                                    </select>

                                    <small>
                                        Only checked accounts can be selected
                                        as default account.
                                    </small>

                                </div>

                            </div>

                            {{-- ===================================================== --}}
                            {{-- EXPENSE --}}
                            {{-- ===================================================== --}}
                            <div class="mapping-section">

                                <div class="mapping-header">

                                    <div class="mapping-icon expense">
                                        <i class="bi bi-receipt-cutoff"></i>
                                    </div>

                                    <div>

                                        <h6>Expense Accounts</h6>

                                        <p>
                                            Select expense accounts and define
                                            default expense mapping.
                                        </p>

                                    </div>

                                </div>

                                {{-- GRID --}}
                                <div class="coa-grid">

                                    @forelse($accounts['expense'] ?? [] as $coa)
                                        <label class="coa-item">

                                            <input type="checkbox" class="mapping-checkbox"
                                                data-target="default_expense_id" data-code="{{ $coa->code }}"
                                                data-name="{{ $coa->name }}" value="{{ $coa->id }}"
                                                name="mapping[expense][]" {{ isset($mapped[$coa->id]) ? 'checked' : '' }}>

                                            <div class="coa-item-content">

                                                <div class="coa-item-title">
                                                    {{ $coa->code }}
                                                </div>

                                                <div class="coa-item-subtitle">
                                                    {{ $coa->name }}
                                                </div>

                                            </div>

                                        </label>

                                    @empty

                                        <div class="coa-empty">

                                            <i class="bi bi-inbox me-2"></i>

                                            No expense accounts found

                                        </div>
                                    @endforelse

                                </div>

                                {{-- DEFAULT --}}
                                <div class="default-box">

                                    <label class="form-label">
                                        Default Expense Account
                                    </label>

                                    <select name="default_expense_id" id="default_expense_id" class="form-control">

                                        <option value="">
                                            -- Pilih Default Expense --
                                        </option>

                                        @foreach ($accounts['expense'] ?? [] as $coa)
                                            @if (isset($mapped[$coa->id]))
                                                <option value="{{ $coa->id }}"
                                                    {{ ($defaultByType['expense'] ?? null) == $coa->id ? 'selected' : '' }}>

                                                    {{ $coa->code }}
                                                    -
                                                    {{ $coa->name }}

                                                </option>
                                            @endif
                                        @endforeach

                                    </select>

                                    <small>
                                        Only checked accounts can be selected
                                        as default account.
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- ===================================================== --}}
                    {{-- FOOTER --}}
                    {{-- ===================================================== --}}
                    <div class="card-footer coa-footer-sticky">

                        <div class="d-flex justify-content-end align-items-center gap-2 w-100">

                            <a href="{{ route('management.funding-types.index') }}" class="btn btn-light">

                                <i class="bi bi-arrow-left"></i>

                                Batal

                            </a>

                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-check-circle"></i>

                                Simpan Perubahan

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    @include('backend.accounting.funding-types.style_edit_fundaccount')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | ALL MAPPING CHECKBOXES
            |--------------------------------------------------------------------------
            */
            const checkboxes = document.querySelectorAll('.mapping-checkbox');

            /*
            |--------------------------------------------------------------------------
            | HANDLE CHECKBOX CHANGE
            |--------------------------------------------------------------------------
            */
            checkboxes.forEach((checkbox) => {

                checkbox.addEventListener('change', function() {

                    /*
                    |--------------------------------------------------------------------------
                    | TARGET SELECT
                    |--------------------------------------------------------------------------
                    | example:
                    | data-target="default_asset_id"
                    | data-target="default_liability_id"
                    | data-target="default_revenue_id"
                    | data-target="default_expense_id"
                    |--------------------------------------------------------------------------
                    */
                    const targetId = this.dataset.target;

                    /*
                    |--------------------------------------------------------------------------
                    | SELECT ELEMENT
                    |--------------------------------------------------------------------------
                    */
                    const select = document.getElementById(targetId);

                    if (!select) {
                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CHECKBOX DATA
                    |--------------------------------------------------------------------------
                    */
                    const value = this.value;

                    const label =
                        `${this.dataset.code} - ${this.dataset.name}`;

                    /*
                    |--------------------------------------------------------------------------
                    | CHECKED -> ADD OPTION
                    |--------------------------------------------------------------------------
                    */
                    if (this.checked) {

                        /*
                        |--------------------------------------------------------------------------
                        | PREVENT DUPLICATE OPTION
                        |--------------------------------------------------------------------------
                        */
                        const exists = select.querySelector(
                            `option[value="${value}"]`
                        );

                        if (!exists) {

                            const option = document.createElement('option');

                            option.value = value;
                            option.textContent = label;

                            select.appendChild(option);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UNCHECKED -> REMOVE OPTION
                    |--------------------------------------------------------------------------
                    */
                    else {

                        const option = select.querySelector(
                            `option[value="${value}"]`
                        );

                        if (option) {

                            /*
                            |--------------------------------------------------------------------------
                            | RESET IF CURRENTLY SELECTED
                            |--------------------------------------------------------------------------
                            */
                            if (select.value === value) {
                                select.value = '';
                            }

                            option.remove();
                        }
                    }
                });

            });

        });
    </script>
@endpush
