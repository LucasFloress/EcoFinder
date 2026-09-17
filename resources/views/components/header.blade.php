<header class="header">
    <div class="header-content">
        <div>
            EcoFinder
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <input type="text" placeholder="Busca lo que necesites...">
        </div>

        <!-- Right Side Actions -->
        <div class="header-actions">

            <div class="menu-section" style="margin-top: auto;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="menu-item" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left; font-family: inherit; font-size: inherit;">
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>