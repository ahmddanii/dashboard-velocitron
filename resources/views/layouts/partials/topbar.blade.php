<header id="topbar"
    class="fixed top-0 right-0 w-[calc(100%-16rem)] h-14 z-40 bg-white border-b border-slate-200 flex justify-between items-center px-6 transition-all duration-150">
    <div class="flex items-center flex-1">
        <span class="text-base font-bold text-slate-900 mr-8">Warehouse Central</span>
        <div class="relative w-80 max-w-full">
            <span
                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
            <input
                class="w-full bg-surface-container-low border-none rounded-lg pl-10 pr-4 py-1.5 text-sm focus:ring-2 focus:ring-blue-500/20 transition-all"
                placeholder="Search tables, pipelines..." type="text" />
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 border-r border-slate-200 pr-4 mr-2">
            <button class="p-2 text-slate-500 hover:text-slate-900 transition-all relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            <button class="p-2 text-slate-500 hover:text-slate-900 transition-all">
                <span class="material-symbols-outlined">history</span>
            </button>
            <button class="p-2 text-slate-500 hover:text-slate-900 transition-all">
                <span class="material-symbols-outlined">help</span>
            </button>
        </div>


        {{-- User Avatar + Logout --}}
        <div class="ml-2 pl-4 border-l border-slate-200 relative group">
            <div
                class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 ring-2 ring-slate-100 cursor-pointer">
                {{ strtoupper(substr(Auth::user()->name ?? 'JD', 0, 2)) }}
            </div>
            {{-- Dropdown on hover --}}
            <div
                class="absolute right-0 top-10 w-44 bg-white border border-slate-200 rounded-lg shadow-lg py-1 hidden group-hover:block z-50">
                <p class="px-4 py-2 text-xs text-slate-500 border-b border-slate-100">{{ Auth::user()->email ?? '' }}
                </p>
                <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>