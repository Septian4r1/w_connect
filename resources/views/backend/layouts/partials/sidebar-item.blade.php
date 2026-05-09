<li
    class="{{ $menu->hasChildren() ? ($menu->hasActiveChild() ? 'mm-active' : '') : ($menu->isActive() ? 'mm-active' : '') }}">

    <a href="{{ $menu->hasChildren() ? 'javascript:;' : $menu->url }}"
        class="{{ $menu->hasChildren() ? 'has-arrow' : '' }}">

        {{-- ICON --}}
        <div class="parent-icon">
            <i class="{{ $menu->icon ?? 'bi bi-circle' }}"></i>
        </div>

        {{-- TITLE --}}
        <div class="menu-title">
            {{ $menu->name }}
        </div>

    </a>

    {{-- CHILDREN --}}
    @if ($menu->hasChildren())
        <ul class="{{ $menu->hasActiveChild() ? 'mm-show' : '' }}">

            @foreach ($menu->childrenRecursive as $child)
                @continue(!$child->canAccess())

                @include('backend.layouts.partials.sidebar-item', ['menu' => $child])
            @endforeach

        </ul>
    @endif

</li>
