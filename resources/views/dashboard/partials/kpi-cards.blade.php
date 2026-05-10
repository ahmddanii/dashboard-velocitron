<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <x-ui.kpi-card label="Total Sales" value="${{ number_format($summary['total_sales'] ?? 0, 0) }}" icon="payments"
        color="blue" sub="Semua periode" />

    <x-ui.kpi-card label="Total Profit" value="${{ number_format($summary['total_profit'] ?? 0, 0) }}"
        icon="trending_up" color="green" trend="up" trendVal="Keuntungan bersih" />

    <x-ui.kpi-card label="Total Orders" value="{{ number_format($summary['total_orders'] ?? 0) }}" icon="shopping_cart"
        color="orange" sub="Order unik" />

    <x-ui.kpi-card label="Avg Profit Margin" value="{{ $summary['avg_profit_pct'] ?? 0 }}%" icon="percent"
        color="purple" sub="Rata-rata margin" @if(($summary['avg_profit_pct'] ?? 0) >= 10) trend="up"
        trendVal="{{ $summary['avg_profit_pct'] ?? 0 }}% margin" @elseif(($summary['avg_profit_pct'] ?? 0) > 0)
        trend="neutral" trendVal="Perlu ditingkatkan" @else trend="down" trendVal="Margin negatif" @endif />

</div>