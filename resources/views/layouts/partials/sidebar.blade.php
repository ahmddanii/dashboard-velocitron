@php
    $role = auth()->user()->roles->first()?->name;

    $historyLabel = match($role) {
        'procurement-director' => 'Procurement History',
        'logistics-officer'    => 'Shipment History',
        'key-account-manager'  => 'Contract History',
        'financial-controller' => 'Transaction History',
        default                => 'Transaction History',
    };

    $historyIcon = match($role) {
        'procurement-director' => 'inventory_2',
        'logistics-officer'    => 'local_shipping',
        'key-account-manager'  => 'handshake',
        default                => 'history',
    };

    $createLabel = match($role) {
        'procurement-director' => 'Create Procurement',
        'logistics-officer'    => 'Create Shipment',
        'key-account-manager'  => 'Create Contract',
        default                => 'Create Request',
    };
@endphp

<aside id="sidebar"
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

            <x-ui.sidebar-item route="dashboard.dss" icon="psychology" label="Prediksi DSS" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Reports</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />

            <a href="{{ route('analytics.export') }}"
               class="flex items-center px-4 py-2 mx-2 rounded-md text-sm font-medium tracking-tight duration-200
                      text-slate-400 hover:text-slate-100 hover:bg-slate-800/50">
                <span class="material-symbols-outlined mr-3 text-base">download</span>
                Export DSS Report
            </a>

        {{-- ── FINANCIAL CONTROLLER ────────────────── --}}
        @elseif($role === 'financial-controller')

            <x-ui.sidebar-item route="dashboard.dss" icon="psychology" label="Prediksi DSS" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Approvals</p>
            </div>

            <x-ui.sidebar-item route="requests.pending" icon="pending_actions" label="Pending Requests" :badge="App\Models\TransactionRequest::where('status','pending')->count()" />

            {{-- Divider --}}
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Reports</p>
            </div>

            <x-ui.sidebar-item route="transactions.history" :icon="$historyIcon" :label="$historyLabel" />

            <a href="{{ route('transactions.export') }}"
               class="flex items-center px-4 py-2 mx-2 rounded-md text-sm font-medium tracking-tight duration-200
                      text-slate-400 hover:text-slate-100 hover:bg-slate-800/50">
                <span class="material-symbols-outlined mr-3 text-base">download</span>
                Export CSV
            </a>

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

    {{-- Bottom: Profile + Logout --}}
    <div class="mt-auto border-t border-slate-800 pt-4 space-y-1">

        <x-ui.sidebar-item route="profile.edit" icon="manage_accounts" label="Profile" />

        <div class="px-6 pt-4 mt-2 border-t border-slate-800">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left flex items-center px-2 py-1.5 text-slate-500 hover:text-red-400
                           text-xs font-medium transition-colors rounded-md hover:bg-slate-800/50">
                    <span class="material-symbols-outlined mr-2 text-sm">logout</span> Logout
                </button>
            </form>
        </div>
    </div>

</aside>