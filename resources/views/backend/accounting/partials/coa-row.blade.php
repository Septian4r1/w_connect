@php
    $children = $accounts[$account->id] ?? [];
    $hasChildren = count($children) > 0;

    // indent level tree
    $indent = $level * 26;

    // status helper
    $isActive = (bool) $account->is_active;
@endphp

<tr data-id="{{ $account->id }}" data-parent="{{ $account->parent_id ?? 0 }}" class="coa-row level-{{ $level }}">

    {{-- ================= CODE ================= --}}
    <td class="coa-code-cell">

        <div class="coa-tree-wrap">

            <div class="coa-code-wrap" style="padding-left: {{ $indent }}px;">

                {{-- LINE VISUAL --}}
                <span class="tree-line"></span>

                {{-- TOGGLE --}}
                @if ($hasChildren)
                    <button type="button" class="coa-toggle-btn" data-state="open">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                @else
                    <span class="coa-leaf-dot"></span>
                @endif

                <span class="coa-code-text">
                    {{ $account->code }}
                </span>

            </div>

        </div>

    </td>
    {{-- ================= NAME ================= --}}
    <td class="coa-name-cell">
        <div class="coa-name-text">
            {{ $account->name }}
        </div>
    </td>

    {{-- ================= TYPE ================= --}}
    @php
        $type = strtolower($account->type);
    @endphp

    <td>
        <span class="coa-type-badge type-{{ $type }}">
            {{ $account->type }}
        </span>
    </td>

    {{-- ================= BALANCE ================= --}}
    @php
        $balanceType = strtolower($account->normal_balance);
    @endphp

    <td>
        <span class="coa-balance-badge {{ $balanceType }}">
            {{ strtoupper($account->normal_balance) }}
        </span>
    </td>

    {{-- ================= STATUS ================= --}}
    <td>
        @if ($isActive)
            <span class="coa-status active">Active</span>
        @else
            <span class="coa-status inactive">Inactive</span>
        @endif
    </td>

    {{-- ================= ACTION ================= --}}
    <td class="coa-action-cell text-end">

        <div class="coa-action-group">

            <button class="coa-icon-btn edit" title="Edit">
                <i class="bi bi-pencil"></i>
            </button>

            <button class="coa-icon-btn view" title="View">
                <i class="bi bi-eye"></i>
            </button>

            <button class="coa-icon-btn danger" title="Delete">
                <i class="bi bi-trash"></i>
            </button>

        </div>

    </td>

</tr>

{{-- ================= CHILDREN ================= --}}
@if ($hasChildren)
    @foreach ($children as $child)
        @include('backend.accounting.partials.coa-row', [
            'account' => $child,
            'accounts' => $accounts,
            'level' => $level + 1,
        ])
    @endforeach
@endif
