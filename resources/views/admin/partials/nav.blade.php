<header class="bg-ink text-nude">
    <nav class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="font-display text-xl tracking-widest">
            APRILIANA<span class="text-gold">FAST</span> <span class="text-xs text-nude/50 tracking-normal">Admin</span>
        </a>
        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gold transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-gold' : '' }}">Dashboard</a>
            <a href="{{ route('admin.layanan.index') }}" class="hover:text-gold transition-colors {{ request()->routeIs('admin.layanan.*') ? 'text-gold' : '' }}">Layanan</a>
            <a href="{{ route('admin.scan') }}" class="hover:text-gold transition-colors {{ request()->routeIs('admin.scan') ? 'text-gold' : '' }}">Scan QR</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="text-nude/60 hover:text-gold transition-colors">Logout</button>
            </form>
        </div>
    </nav>
</header>

