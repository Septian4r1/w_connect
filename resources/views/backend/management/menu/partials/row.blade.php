<tr>
    <td class="tree-cell ps-3" style="padding-left: {{ $level * 20 }}px;">
        {{-- INDENT LEVEL --}}
        @for ($i = 0; $i < $level; $i++)
            <span class="tree-line" style="left: {{ 10 + $i * 18 }}px;"></span>
        @endfor

        <span style="margin-left: {{ $level * 18 }}px;">
            @if ($level > 0)
                <span class="tree-branch"></span>
            @endif

            {{-- ICON --}}
            <i class="{{ $menu->icon }} menu-icon me-1"></i>

            {{-- NAME --}}
            {{ $menu->name }}
        </span>

    </td>

    <td>{{ $menu->route ?? '-' }}</td>

    <td>
        <i class="{{ $menu->icon }} menu-icon"></i>
    </td>

    <td>
        <span class="badge-soft badge-secondary-soft">
            {{ $menu->parent->name ?? 'Main' }}
        </span>
    </td>

    <td>
        <span class="badge-soft badge-secondary-soft">
            {{ $menu->order }}
        </span>
    </td>

    <td>
        @if ($menu->is_active)
            <span class="badge-soft badge-success-soft">Aktif</span>
        @else
            <span class="badge-soft badge-secondary-soft">Nonaktif</span>
        @endif
    </td>

    <td>
        @forelse($menu->permissions as $perm)
            <span class="badge-soft badge-info-soft">
                {{ $perm->name }}
            </span>
        @empty
            <span class="text-muted">-</span>
        @endforelse
    </td>

    <td class="text-center">
        <button class="btn-edit-menu btn btn-sm btn-soft text-warning" data-id="{{ encrypt($menu->id) }}"
            data-name="{{ $menu->name }}" data-route="{{ $menu->route }}" data-icon="{{ $menu->icon }}"
            data-order="{{ $menu->order }}" data-status="{{ $menu->is_active }}"
            data-permissions='@json($menu->permissions->pluck('id'))'>
            <i class="bi bi-pencil-fill" style="font-size:12px;"></i>
        </button>

        {{-- <button class="btn-soft text-danger">
            <i class="bi bi-trash"></i>
        </button> --}}
    </td>
</tr>

{{-- RECURSIVE CHILD --}}
@if ($menu->childrenRecursive && $menu->childrenRecursive->count())
    @foreach ($menu->childrenRecursive as $child)
        @include('backend.management.menu.partials.row', [
            'menu' => $child,
            'level' => $level + 1,
        ])
    @endforeach
@endif
