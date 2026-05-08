<aside id="sidebar"
    class="fixed left-0 top-0 h-full w-64 border-r border-slate-800 bg-slate-900 flex flex-col py-6 z-50">
    <div class="px-6 mb-8">
        <a href="{{ route('dashboard') }}" class="block">
            <h1 class="text-xl font-bold text-white tracking-wider uppercase hover:text-blue-400 transition-colors">
                VELOCITRON</h1>
        </a>
        <p class="text-xs text-slate-400 font-medium uppercase tracking-widest mt-1">Business Intelligence</p>
    </div>

    <nav class="flex-1 space-y-1">

        @php

            $role = auth()->user()

                ->roles

                ->first()

                    ?->name;

            $historyLabel = match ($role) {

                'procurement-director' =>

                'Procurement History',

                'logistics-officer' =>

                'Shipment History',

                'key-account-manager' =>

                'Contract History',

                default =>

                'Transaction History',
            };

        @endphp

        <a href="{{ route('dashboard') }}"
            class="sidebar-item flex items-center px-4 py-2 mx-2 rounded-md text-sm font-medium tracking-tight duration-200
                  {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
            <span class="material-symbols-outlined mr-3">dashboard</span> Dashboard
        </a>
        {{-- DSS — hanya tampil untuk 2 role ini --}}
        @if(auth()->user()->hasAnyRole(['head-analytics', 'financial-controller']))
            <a href="{{ route('dashboard.dss') }}"
                class="sidebar-item flex items-center px-4 py-2 mx-2 rounded-md text-sm font-medium tracking-tight duration-200
                                      {{ request()->routeIs('dashboard.dss') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">
                <span class="material-symbols-outlined mr-3">psychology</span> Prediksi DSS
            </a>
        @endif

        <a href="{{ route('transactions.history') }}" class="sidebar-item flex items-center px-4 py-2 mx-2 rounded-md text-sm font-medium tracking-tight duration-200
    {{ request()->routeIs('transactions.history')
    ? 'bg-blue-600 text-white'
    : 'text-slate-400 hover:text-slate-100 hover:bg-slate-800/50' }}">

            <span class="material-symbols-outlined mr-3">
                history
            </span>

            {{ $historyLabel }}

        </a>
    </nav>

    <div class="mt-auto space-y-1 border-t border-slate-800 pt-4">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center px-4 py-2 text-slate-400 hover:text-slate-100 hover:bg-slate-800/50 transition-colors mx-2 rounded-md text-sm font-medium tracking-tight">
            <span class="material-symbols-outlined mr-3">manage_accounts</span> Profile
        </a>
        <div class="px-6 pt-4 mt-4 border-t border-slate-800">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit"
                    class="w-full text-left flex items-center px-2 py-1.5 text-slate-500 hover:text-red-400 text-xs font-medium transition-colors rounded-md hover:bg-slate-800/50">
                    <span class="material-symbols-outlined mr-2 text-sm">logout</span> Logout
                </button>
            </form>
        </div>
    </div>
</aside>