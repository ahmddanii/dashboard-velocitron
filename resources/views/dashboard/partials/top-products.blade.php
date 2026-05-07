<div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex justify-between items-center">
        <h3 class="font-title-sm text-title-sm">Top 10 Produk Terlaris</h3>
        <span class="text-xs font-bold bg-blue-50 text-blue-700 px-2 py-1 rounded-full border border-blue-100">
            Berdasarkan Total Sales
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low">
                <tr>
                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">#</th>
                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">PRODUK</th>
                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant">KATEGORI</th>
                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant text-right">TOTAL SALES
                    </th>
                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant text-right">TOTAL
                        PROFIT</th>
                    <th class="px-4 py-3 font-label-caps text-label-caps text-on-surface-variant text-right">QTY</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($products as $i => $p)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-bold text-on-surface-variant">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-semibold text-on-surface leading-snug">{{ $p['product_name'] }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $p['sub_category'] }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                    @if($p['category'] === 'Technology') bg-blue-50 text-blue-700
                                    @elseif($p['category'] === 'Furniture') bg-amber-50 text-amber-700
                                    @else bg-green-50 text-green-700 @endif">
                                {{ $p['category'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-sm font-semibold">
                            ${{ number_format($p['total_sales'], 0) }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-sm font-semibold
                                {{ $p['total_profit'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            ${{ number_format($p['total_profit'], 0) }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-sm">{{ $p['total_qty'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>