{{-- ══════════════════════════════════════════════════════════
    FINANCIAL CONTROLLER DASHBOARD
    Urutan: Charts (side by side) → DSS Analytics → Trend → Insights → CTA
══════════════════════════════════════════════════════════ --}}

{{-- Baris 1: Profit per Kategori + Profit & Sales per Region --}}
<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">
        <div class="dashboard-card-header">
            <h3 class="dashboard-title">Profit per Kategori</h3>
            <p class="dashboard-subtitle">Perbandingan sales & profit tiap kategori produk.</p>
        </div>
        <div class="dashboard-card-body h-72">
            <canvas id="categoryChart"></canvas>
        </div>
    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">
        <div class="dashboard-card-header">
            <h3 class="dashboard-title">Profit & Sales per Region</h3>
            <p class="dashboard-subtitle">Distribusi pendapatan di setiap region penjualan.</p>
        </div>
        <div class="dashboard-card-body h-72">
            <canvas id="regionChart"></canvas>
        </div>
    </x-ui.card>

</div>

{{-- Baris 2: DSS Analytics + DSS Trend --}}
@include('dashboard.partials.dss-analytics')
@include('dashboard.partials.dss-trend-chart')

{{-- Baris 3: Executive Insights --}}
@include('dashboard.partials.executive-insights')

{{-- Baris 4: DSS Approval CTA --}}
<div class="dashboard-grid">
    <x-ui.card class="col-span-12 overflow-hidden bg-primary-container border-0 relative">
        <div class="absolute -right-6 -bottom-6 opacity-10">
            <span class="material-symbols-outlined material-icon-fill text-white" style="font-size:120px">gavel</span>
        </div>
        <div class="dashboard-card-body flex flex-col justify-between h-full relative z-10">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Decision Support System</p>
                <h3 class="text-white text-xl font-bold mb-2">Approval Transaksi</h3>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Gunakan DSS untuk memproses pengajuan transaksi.
                    Prediksi DSS membantu Anda memutuskan apakah transaksi layak disetujui.
                </p>
            </div>
            <a href="{{ route('requests.pending') }}"
                class="mt-6 inline-flex items-center gap-2 bg-white text-secondary px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-50 transition-all w-fit">
                <span class="material-symbols-outlined material-icon text-sm">psychology</span>
                Review & Setujui Transaksi
            </a>
        </div>
    </x-ui.card>
</div>
