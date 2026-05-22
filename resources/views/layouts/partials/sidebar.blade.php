@php
    $role = auth()->user()->roles->first()?->name;

    $historyLabel = match ($role) {
        'procurement-director' => 'Procurement History',
        'logistics-officer' => 'Shipment History',
        'key-account-manager' => 'Contract History',
        'financial-controller' => 'Transaction History',
        default => 'Transaction History',
    };

    $historyIcon = match ($role) {
        'procurement-director' => 'inventory_2',
        'logistics-officer' => 'local_shipping',
        'key-account-manager' => 'handshake',
        default => 'history',
    };

    $createLabel = match ($role) {
        'procurement-director' => 'Create Procurement',
        'logistics-officer' => 'Create Shipment',
        'key-account-manager' => 'Create Contract',
        default => 'Create Request',
    };
@endphp

<aside id="sidebar"
    x-data="{ showLogoutModal: false }"
    class="fixed left-0 top-0 h-full w-64 border-r border-slate-800 bg-slate-900 flex flex-col py-6 z-50">

    {{-- Logo --}}
    <div class="px-6 mb-8">
        <a href="{{ route('dashboard') }}" class="block">
            <h1 class="text-xl font-bold text-white tracking-wider uppercase hover:text-blue-400 transition-colors">
                VELOCITRON
            </h1>
        </a>
        <p class="text-xs text-slate-400 font-medium uppercase tracking-widest mt-1">Business Intelligence</p>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto">

        {{-- ── SEMUA ROLE ─────────────────────────── --}}
        <x-ui.sidebar-item route="dashboard" icon="dashboard" label="Dashboard" />

        {{-- ── HEAD ANALYTICS ─────────────────────── --}}
        @if($role === 'head-analytics')

            <x-ui.sidebar-item route="dashboard.dss" active="dashboard.dss,dashboard.predict" icon="psychology" label="Prediksi DSS" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Reports</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />
            <x-ui.sidebar-item route="users.index" icon="manage_accounts" label="User Management" />

            {{-- ── FINANCIAL CONTROLLER ────────────────── --}}
        @elseif($role === 'financial-controller')

            <x-ui.sidebar-item route="dashboard.dss" active="dashboard.dss,dashboard.predict" icon="psychology" label="Prediksi DSS" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Approvals</p>
            </div>

            <x-ui.sidebar-item route="requests.pending" icon="pending_actions" label="Pending Requests"
                :badge="App\Models\TransactionRequest::where('status', 'pending')->count()" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Reports</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />

            {{-- ── LOGISTICS OFFICER ───────────────────── --}}
        @elseif($role === 'logistics-officer')

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Actions</p>
            </div>

            <x-ui.sidebar-item route="requests.create" icon="add_circle" label="Create Shipment" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">History</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />

            {{-- ── PROCUREMENT DIRECTOR ────────────────── --}}
        @elseif($role === 'procurement-director')

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Actions</p>
            </div>

            <x-ui.sidebar-item route="requests.create" icon="add_circle" label="Create Procurement" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">History</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />

            {{-- ── KEY ACCOUNT MANAGER ─────────────────── --}}
        @elseif($role === 'key-account-manager')

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Actions</p>
            </div>

            <x-ui.sidebar-item route="requests.create" icon="add_circle" label="Create Contract" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">History</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />

        @endif

    </nav>

    {{-- Bottom Section --}}
    <div class="mt-auto px-4 space-y-4">
        {{-- BI Engine Status Widget --}}
        <div class="bg-slate-800/40 rounded-2xl p-4 border border-slate-700/50 backdrop-blur-md">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">BI Engine Status</p>
                <div class="flex items-center gap-1.5">
                    <span class="flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-[10px] font-semibold text-green-400">Live</span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-[10px]">
                    <span class="text-slate-400">DSS Prediction</span>
                    <span class="text-slate-200">Active</span>
                </div>
                <div class="w-full bg-slate-700 h-1 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full w-[85%] rounded-full shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                </div>
                <p class="text-[9px] text-slate-500 italic">Engine v2.4 — Optimized</p>
            </div>
        </div>

        {{-- Theme Toggle --}}
        <div class="px-2 pb-4 border-b border-slate-800" x-data="{ 
            darkMode: document.documentElement.classList.contains('dark'),
            toggle() {
                this.darkMode = !this.darkMode;
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        }">
            <button @click="toggle()" type="button" class="w-full flex items-center justify-between p-3 rounded-2xl bg-slate-800/50 hover:bg-slate-800 border border-slate-700/50 transition-all duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-700 text-amber-400" x-show="!darkMode">
                        <span class="material-symbols-outlined text-lg">light_mode</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-slate-700 text-blue-400" x-show="darkMode">
                        <span class="material-symbols-outlined text-lg">dark_mode</span>
                    </div>
                    <span class="text-xs font-bold text-slate-300" x-text="darkMode ? 'Dark Mode' : 'Light Mode'"></span>
                </div>
                <div class="w-10 h-5 bg-slate-700 rounded-full relative transition-colors duration-300" :class="darkMode ? 'bg-blue-600' : 'bg-slate-600'">
                    <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full transition-transform duration-300" :class="darkMode ? 'translate-x-5' : ''"></div>
                </div>
            </button>
        </div>

        {{-- Profile Section --}}
        <div class="pt-4 pb-2">
            <div class="group flex items-center gap-3 p-2 rounded-2xl hover:bg-slate-800/50 transition-all duration-300 cursor-pointer mb-2" 
                 onclick="window.location.href='{{ route('profile.edit') }}'">
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-900/20 group-hover:scale-105 transition-transform duration-300">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-slate-900 rounded-full"></div>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-500 truncate uppercase tracking-tighter">{{ str_replace('-', ' ', $role) }}</p>
                </div>
            </div>

            <button @click="showLogoutModal = true" type="button" class="w-full group flex items-center justify-between px-4 py-2.5 rounded-xl text-slate-400 hover:text-red-400 hover:bg-red-500/5 transition-all duration-300">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-lg group-hover:rotate-12 transition-transform">logout</span>
                    <span class="text-xs font-semibold">Sign Out</span>
                </div>
                <span class="material-symbols-outlined text-sm opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
            </button>
        </div>
    </div>

    {{-- Logout Confirmation Modal --}}
    <div x-show="showLogoutModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center" @click.away="showLogoutModal = false">
            <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-3xl text-red-500">logout</span>
            </div>
            
            <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Keluar</h3>
            <p class="text-sm text-slate-400 mb-8 leading-relaxed">
                Apakah Anda yakin ingin mengakhiri sesi VELOCITRON saat ini?
            </p>
            
            <div class="flex gap-3">
                <button @click="showLogoutModal = false" type="button"
                    class="flex-1 px-5 py-3 rounded-xl border border-slate-800 text-sm font-bold text-slate-400 hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" 
                        class="w-full px-5 py-3 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-colors shadow-lg shadow-red-900/20">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>