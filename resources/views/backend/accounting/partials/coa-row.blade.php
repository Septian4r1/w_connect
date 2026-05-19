@php

    $children = $accounts->get($account->id, collect());

    $hasChildren = $children->isNotEmpty();

    $isActive = (bool) $account->is_active;

    $type = strtolower($account->type);

    $balanceType = strtolower($account->normal_balance);

@endphp

<tr data-id="{{ $account->id }}" data-parent="{{ $account->parent_id }}" data-level="{{ $level }}"
    class="coa-row level-{{ $level }}">

    {{-- CODE --}}
    <td class="coa-code-cell">

        <div class="coa-code-wrap level-{{ $level }}">

            {{-- TREE --}}
            <span class="tree-lines">

                @for ($i = 0; $i < $level; $i++)
                    <span class="v-line"></span>
                @endfor

            </span>

            {{-- TOGGLE --}}
            @if ($hasChildren)
                <button type="button" class="coa-toggle-btn" data-state="open">

                    <i class="bi bi-chevron-down"></i>

                </button>
            @else
                <span class="coa-leaf-dot"></span>
            @endif

            {{-- CODE --}}
            <span class="coa-code-text">

                {{ $account->code }}

            </span>

        </div>

    </td>

    {{-- NAME --}}
    <td>

        <div class="coa-name-text">

            {{ $account->name }}

        </div>

    </td>

    {{-- TYPE --}}
    <td>

        <span class="coa-type-badge type-{{ $type }}">

            {{ ucfirst($type) }}

        </span>

    </td>

    {{-- BALANCE --}}
    <td>

        <span class="coa-balance-badge {{ $balanceType }}">

            {{ strtoupper($balanceType) }}

        </span>

    </td>

    {{-- STATUS --}}
    <td>

        <span class="coa-status {{ $isActive ? 'active' : 'inactive' }}">

            {{ $isActive ? 'Active' : 'Inactive' }}

        </span>

    </td>

    {{-- ACTION --}}
    <td class="text-center">

        <div class="coa-action-group">

            <div class="coa-action-group">

                {{-- 👁 VIEW DETAIL --}}
                <button type="button" class="coa-icon-btn view coa-btn-view" title="View Detail COA"
                    data-id="{{ $account->id }}" data-name="{{ $account->name }}">

                    <i class="bi bi-eye"></i>

                </button>
                <button type="button" class="coa-icon-btn edit btn-edit-account btn-warning-soft" title="Edit Account"
                    data-id="{{ $account->id }}" data-account_id="{{ $account->id }}"
                    data-parent_id="{{ $account->parent_id }}" data-code="{{ $account->code }}"
                    data-name="{{ $account->name }}" data-type="{{ $account->type }}"
                    data-is_header="{{ $account->is_header }}">
                    <i class="bi bi-pencil"></i>
                </button>

                <button type="button"
                    class="coa-icon-btn toggle-status-btn {{ $account->is_active == 1 ? 'success' : 'danger' }}"
                    title="{{ $account->is_active == 1 ? 'Active' : 'Inactive' }}"
                    data-id="{{ Crypt::encryptString($account->id) }}" data-status="{{ $account->is_active }}"
                    data-name="{{ $account->name }}">
                    <i
                        class="bi {{ $account->is_active == 1 ? 'bi-arrow-up-circle-fill' : 'bi-arrow-down-circle-fill' }}">
                    </i>
                </button>

            </div>

    </td>

</tr>

{{-- RECURSIVE --}}
@if ($hasChildren)

    @foreach ($children as $child)
        @include('backend.accounting.partials.coa-row', [
            'account' => $child,
            'accounts' => $accounts,
            'level' => $level + 1,
        ])
    @endforeach

@endif
