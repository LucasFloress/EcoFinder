<div class="menu-section">
    <div class="menu-title">MENU PRINCIPAL</div>
    <a href="{{ route('super-admin.dashboard') }}" class="menu-item {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}">
        <span class="menu-icon">🏠</span>
        <span>Inicio</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-title">GESTIÓN</div>
    <a href="{{ route('super-admin.admins.index') }}" class="menu-item {{ request()->routeIs('super-admin.admins.*') ? 'active' : '' }}">
        <span class="menu-icon">🛡️</span>
        <span>Administradores</span>
    </a>
    <a href="{{ route('super-admin.users.index') }}" class="menu-item {{ request()->routeIs('super-admin.users.*') ? 'active' : '' }}">
        <span class="menu-icon">👥</span>
        <span>Usuarios</span>
    </a>
</div>
<div class="menu-section" style="margin-top: auto;">
    <div class="menu-title">OTROS</div>
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="menu-item" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left; font-family: inherit; font-size: inherit;">
            <span class="menu-icon">🚪</span>
            <span>Cerrar Sesión</span>
        </button>
    </form>
</div>