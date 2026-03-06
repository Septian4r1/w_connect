<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">

        <a href="{{ route('management.dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo_w_connect.png') }}" class="logo-main" alt="logo icon">

            <h4 class="logo-text mt-2 ms-2">{{ config('app.name') }}</h4>
        </a>

        <div class="toggle-icon ms-auto">
            <ion-icon name="menu-sharp"></ion-icon>
        </div>

    </div>

    <!-- navigation -->
    <ul class="metismenu" id="menu">

        {{-- DASHBOARD --}}
        <li class="{{ request()->routeIs('management.dashboard*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bi bi-speedometer2"></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li><a href="{{ route('management.dashboard.statistik_warga') }}">Statistik Warga</a></li>
                <li><a href="{{ route('management.dashboard.statistik_keuangan') }}">Statistik Keuangan</a></li>
                <li><a href="{{ route('management.dashboard.grafik_iuran') }}">Grafik Iuran</a></li>
            </ul>
        </li>


        {{-- USER MANAGEMENT --}}
        <li class="{{ request()->routeIs('management.users*', 'management.roles*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="menu-title">User Management</div>
            </a>
            <ul>
                <li><a href="{{ route('management.users.index') }}">Admin</a></li>
                <li><a href="{{ route('management.roles.index') }}">Role Permission</a></li>
            </ul>
        </li>


        {{-- WARGA --}}
        <li
            class="{{ request()->routeIs('management.warga*', 'management.kk*', 'management.mutasi*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bi bi-house"></i>
                </div>
                <div class="menu-title">Warga</div>
            </a>
            <ul>
                <li><a href="{{ route('management.warga.index') }}">Data Warga</a></li>
                <li><a href="{{ route('management.kk.index') }}">Data KK</a></li>
                <li><a href="{{ route('management.mutasi.index') }}">Mutasi Warga</a></li>
            </ul>
        </li>


        {{-- KEUANGAN --}}
        <li
            class="{{ request()->routeIs('management.iuran*', 'management.kas*', 'management.pengeluaran*', 'management.laporan.keuangan') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="menu-title">Keuangan</div>
            </a>
            <ul>
                <li><a href="{{ route('management.iuran.index') }}">Iuran Bulanan</a></li>
                <li><a href="{{ route('management.kas.index') }}">Kas RW</a></li>
                <li><a href="{{ route('management.pengeluaran.index') }}">Pengeluaran</a></li>
                <li><a href="{{ route('management.laporan.keuangan') }}">Laporan</a></li>
            </ul>
        </li>


        {{-- SURAT --}}
        <li
            class="{{ request()->routeIs('management.surat_pengantar*', 'management.surat_keterangan*', 'management.arsip_surat*') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="menu-title">Surat</div>
            </a>
            <ul>
                <li><a href="{{ route('management.surat_pengantar.index') }}">Surat Pengantar</a></li>
                <li><a href="{{ route('management.surat_keterangan.index') }}">Surat Keterangan</a></li>
                <li><a href="{{ route('management.arsip_surat.index') }}">Arsip Surat</a></li>
            </ul>
        </li>

    </ul>
</aside>
