@php
    $navUser = Auth::user();
    $isAdmin = $navUser->isAdmin();
    $dashboardUrl = $isAdmin ? route('admin.dashboard') : route('petugas.dashboard');
@endphp
<style>
    nav.app-nav { background:#111; color:#fff; padding:.75rem 2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem; }
    nav.app-nav a { color:#aaa; text-decoration:none; font-size:.875rem; margin-right:1rem; }
    nav.app-nav a:hover { color:#fff; }
    nav.app-nav a.active { color:#fff; font-weight:600; }
    nav.app-nav .right { display:flex; align-items:center; gap:1rem; }
    nav.app-nav .right span { font-size:.8rem; color:#aaa; }
    nav.app-nav .right form { display:inline; }
    nav.app-nav .right button { background:none; border:1px solid #555; color:#fff; padding:.3rem .75rem; font-size:.8rem; cursor:pointer; }
    nav.app-nav .right button:hover { border-color:#fff; }
</style>
<nav class="app-nav">
    <div>
        <a href="{{ $dashboardUrl }}" class="{{ role_route_is('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ role_route('pasien.index') }}" class="{{ role_route_is('pasien.*') ? 'active' : '' }}">Pasien</a>
        <a href="{{ role_route('dokters.index') }}" class="{{ role_route_is('dokters.*') ? 'active' : '' }}">Dokter</a>
        <a href="{{ role_route('polikliniks.index') }}" class="{{ role_route_is('polikliniks.*') ? 'active' : '' }}">Poliklinik</a>
        <a href="{{ role_route('jadwal-dokter.index') }}" class="{{ role_route_is('jadwal-dokter.*') ? 'active' : '' }}">Jadwal</a>
        <a href="{{ role_route('registrasi.index') }}" class="{{ role_route_is('registrasi.*') ? 'active' : '' }}">Registrasi</a>
        @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="{{ role_route_is('users.*') ? 'active' : '' }}">Pengguna</a>
        @endif
    </div>
    <div class="right">
        <span>{{ $navUser->name }} <em style="color:#666;font-style:normal">({{ ucfirst($navUser->role) }})</em></span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</nav>
