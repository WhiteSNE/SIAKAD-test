<div class="flex flex-col h-full py-6 px-4">
    <div class="flex items-center px-4 mb-8">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <x-application-logo class="block h-9 w-auto fill-current text-indigo-600" />
            <span class="font-bold text-xl text-gray-800">SIM PKL</span>
        </a>
    </div>

    <nav class="flex-1 space-y-2">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            {{ __('Dashboard') }}
        </x-sidebar-link>

        @if(Auth::user()->role === 'guru')
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Menu Guru
        </div>

        <x-sidebar-link :href="route('guru.siswa.index')" :active="request()->routeIs('guru.siswa.*')">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            {{ __('Siswa Bimbingan') }}
        </x-sidebar-link>

        <x-sidebar-link :href="route('guru.jurnal.index')" :active="request()->routeIs('guru.jurnal.*')">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H4a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('Validasi Jurnal') }}
        </x-sidebar-link>

        <x-sidebar-link :href="route('guru.penilaian.index')" :active="request()->routeIs('guru.penilaian.*')">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            {{ __('Penilaian PKL') }}
        </x-sidebar-link>
        @endif
        @if(Auth::user()->role === 'siswa')
        <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Menu Siswa
        </div>

        <x-sidebar-link :href="route('siswa.jurnal.index')" :active="request()->routeIs('siswa.jurnal.*')">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            {{ __('Jurnal Harian') }}
        </x-sidebar-link>
        @endif
    </nav>

    <div class="mt-auto border-t border-gray-200 pt-4">
        <div class="px-4 mb-4">
            <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>