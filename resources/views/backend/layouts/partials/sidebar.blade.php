<aside class="sidebar-wrapper" data-simplebar="true">

    {{-- ================= HEADER ================= --}}
    <div class="sidebar-header">

        <a href="{{ route('management.dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo_w_connect.png') }}" class="logo-main" alt="logo icon">
            <h4 class="logo-text mt-2 ms-2">{{ config('app.name') }}</h4>
        </a>

        <div class="toggle-icon ms-auto">
            <ion-icon name="menu-sharp"></ion-icon>
        </div>

    </div>

    {{-- ================= MENU ================= --}}
    <ul class="metismenu" id="menu">

        @foreach ($menus as $menu)
            @continue(!$menu->canAccess())

            @include('backend.layouts.partials.sidebar-item', ['menu' => $menu])
        @endforeach

    </ul>

</aside>
