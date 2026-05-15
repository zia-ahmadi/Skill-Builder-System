<nav class="backdrop-blur bg-slate-900/80 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-10 w-10" />
                    <div>
                        <div class="text-sm uppercase tracking-[0.2em] text-indigo-300">Skills Builder</div>
                        <div class="text-lg font-semibold text-white">System</div>
                    </div>
                </a>
                @auth
                    <div class="hidden md:flex items-center gap-2 ml-8">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Dashboard</a>
                        <a href="{{ route('analytics.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('analytics.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Analytics</a>
                        <a href="{{ route('learned.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('learned.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Learned</a>
                        <a href="{{ route('skills.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('skills.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Skills</a>
                        <a href="{{ route('data.export') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('data.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Backup</a>
                        <a href="{{ route('skills.create') }}" class="px-3 py-2 rounded-md text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-500">+ New Skill</a>
                    </div>
                @endauth
            </div>
            <div class="hidden md:flex items-center gap-3">
                <button id="theme-toggle" class="flex items-center gap-2 px-3 py-2 text-sm rounded-md bg-slate-800 text-slate-200 hover:bg-indigo-600 hover:text-white">
                    <span id="theme-toggle-label">Dark</span>
                </button>
                @auth
                    <span class="text-sm text-slate-300">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-2 text-sm rounded-md bg-slate-800 text-slate-200 hover:bg-indigo-600 hover:text-white">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium text-slate-200 hover:text-white hover:bg-slate-800">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-500">Register</a>
                    @endif
                @endauth
            </div>
            <button id="mobile-toggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-slate-200 hover:bg-slate-800">
                <svg id="mobile-icon-open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="mobile-icon-close" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="md:hidden hidden border-t border-slate-800 bg-slate-900/90">
        <div class="px-4 py-3 space-y-2">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Home</a>
                <a href="{{ route('analytics.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('analytics.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Analytics</a>
                <a href="{{ route('learned.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('learned.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Learned</a>
                <a href="{{ route('skills.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('skills.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Skills</a>
                <a href="{{ route('data.export') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('data.*') ? 'bg-indigo-600 text-white' : 'text-slate-200 hover:text-white hover:bg-slate-800' }}">Backup</a>
                <a href="{{ route('skills.create') }}" class="block px-3 py-2 rounded-md text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-500">+ New Skill</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-200 hover:text-white hover:bg-slate-800">Profile</a>
                <button id="mobile-theme-toggle" class="w-full text-left px-3 py-2 rounded-md text-sm font-medium bg-slate-800 text-slate-200 hover:bg-indigo-600 hover:text-white">Toggle Light / Dark</button>
                <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-slate-800">
                    @csrf
                    <button class="w-full text-left px-3 py-2 rounded-md text-sm font-medium bg-slate-800 text-slate-200 hover:bg-indigo-600 hover:text-white">Log out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-200 hover:text-white hover:bg-slate-800">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-500">Register</a>
                @endif
                <button id="mobile-theme-toggle" class="w-full text-left px-3 py-2 rounded-md text-sm font-medium bg-slate-800 text-slate-200 hover:bg-indigo-600 hover:text-white">Toggle Light / Dark</button>
            @endauth
        </div>
    </div>
</nav>
<script>
    const toggle = document.getElementById('mobile-toggle');
    const menu = document.getElementById('mobile-menu');
    const openIcon = document.getElementById('mobile-icon-open');
    const closeIcon = document.getElementById('mobile-icon-close');
    const themeToggle = document.getElementById('theme-toggle');
    const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden', isOpen);
            openIcon.classList.toggle('hidden', !isOpen);
            closeIcon.classList.toggle('hidden', isOpen);
        });
    }
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isLight = document.body.classList.contains('theme-light');
            setTheme(isLight ? 'dark' : 'light');
        });
    }
    if (mobileThemeToggle) {
        mobileThemeToggle.addEventListener('click', () => {
            const isLight = document.body.classList.contains('theme-light');
            setTheme(isLight ? 'dark' : 'light');
        });
    }
</script>
