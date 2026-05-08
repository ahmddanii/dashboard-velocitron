@include('dashboard.partials.dss-analytics')
@include('dashboard.partials.dss-trend-chart')
@include('dashboard.partials.executive-insights')

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">

        <div class="dashboard-card-header">
            <h3 class="dashboard-title">
                Profit per Kategori
            </h3>
        </div>

        <div class="dashboard-card-body h-64">
            <canvas id="categoryChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-6 overflow-hidden">

        <div class="dashboard-card-header">
            <h3 class="dashboard-title">
                Profit & Sales per Region
            </h3>
        </div>

        <div class="dashboard-card-body h-64">
            <canvas id="regionChart"></canvas>
        </div>

    </x-ui.card>

</div>

<div class="dashboard-grid">

    <x-ui.card class="col-span-12 md:col-span-5 overflow-hidden">

        <div class="dashboard-card-header">
            <h3 class="dashboard-title">
                Tren Profit Tahunan
            </h3>
        </div>

        <div class="dashboard-card-body h-56">
            <canvas id="yearlyChart"></canvas>
        </div>

    </x-ui.card>

    <x-ui.card class="col-span-12 md:col-span-7 overflow-hidden bg-primary-container border-0 relative">

        <div class="absolute -right-6 -bottom-6 opacity-10">

            <span class="material-symbols-outlined material-icon-fill text-white" style="font-size:120px">

                gavel

            </span>

        </div>

        <div class="dashboard-card-body flex flex-col justify-between h-full relative z-10">

            <div>

                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                    Decision Support System
                </p>

                <h3 class="text-white text-xl font-bold mb-2">
                    Approval Transaksi
                </h3>

                <p class="text-slate-300 text-sm leading-relaxed">

                    Gunakan DSS untuk memprediksi apakah sebuah
                    transaksi akan menguntungkan sebelum disetujui.

                    Transaksi dengan prediksi rugi dapat langsung
                    ditolak.

                </p>

            </div>

            <a href="{{ route('requests.pending') }}"
                class="mt-6 inline-flex items-center gap-2 bg-white text-secondary px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-50 transition-all w-fit">

                <span class="material-symbols-outlined material-icon text-sm">
                    psychology
                </span>

                Lihat Request Transaksi

            </a>

        </div>

    </x-ui.card>

</div>