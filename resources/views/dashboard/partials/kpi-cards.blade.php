<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">payments</span>
        </div>
        <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Sales</p>
            <p class="font-headline-md text-headline-md">${{ number_format($summary['total_sales'] ?? 0, 0) }}</p>
            <p class="text-xs text-on-surface-variant mt-0.5">Semua periode</p>
        </div>
    </div>
    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600 shrink-0">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">trending_up</span>
        </div>
        <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Profit</p>
            <p class="font-headline-md text-headline-md">${{ number_format($summary['total_profit'] ?? 0, 0) }}</p>
            <p class="text-xs text-green-600 mt-0.5">Keuntungan bersih</p>
        </div>
    </div>
    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">shopping_cart</span>
        </div>
        <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Total Orders</p>
            <p class="font-headline-md text-headline-md">{{ number_format($summary['total_orders'] ?? 0) }}</p>
            <p class="text-xs text-on-surface-variant mt-0.5">Order unik</p>
        </div>
    </div>
    <div class="bg-white border border-outline-variant p-5 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">percent</span>
        </div>
        <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant uppercase">Avg Profit Margin</p>
            <p class="font-headline-md text-headline-md">{{ $summary['avg_profit_pct'] ?? 0 }}%</p>
            <p class="text-xs text-on-surface-variant mt-0.5">Rata-rata margin</p>
        </div>
    </div>
</div>