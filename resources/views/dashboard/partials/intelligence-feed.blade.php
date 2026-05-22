<x-ui.card class="{{ $colSpan ?? 'col-span-12' }} overflow-hidden">
    <div class="dashboard-card-header flex justify-between items-center bg-surface-container-lowest/50">
        <div>
            <h3 class="dashboard-title">Intelligence Feed</h3>
            <p class="dashboard-subtitle">DSS-driven recommendations & alerts</p>
        </div>
        @if (!empty($intelligenceFeed))
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                {{ count($intelligenceFeed) }} insights
            </span>
        @endif
    </div>

    <div class="dashboard-card-body p-6 h-[400px] overflow-y-auto custom-scrollbar">
        <div class="space-y-4">
            @forelse($intelligenceFeed as $feed)
                @php
                    $severity = match ($feed['status']) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'info',
                    };

                    $sc = [
                        'success' => [
                            'bar' => 'bg-green-500', 
                            'bg' => 'bg-green-50/50 dark:bg-green-500/5', 
                            'border' => 'border-green-100 dark:border-green-500/10', 
                            'text' => 'text-green-700 dark:text-green-400', 
                            'icon' => 'check_circle'
                        ],
                        'danger' => [
                            'bar' => 'bg-red-500', 
                            'bg' => 'bg-red-50/50 dark:bg-red-500/5', 
                            'border' => 'border-red-100 dark:border-red-500/10', 
                            'text' => 'text-red-700 dark:text-red-400', 
                            'icon' => 'warning'
                        ],
                        'warning' => [
                            'bar' => 'bg-amber-400', 
                            'bg' => 'bg-amber-50/50 dark:bg-amber-500/5', 
                            'border' => 'border-amber-100 dark:border-amber-500/10', 
                            'text' => 'text-amber-700 dark:text-amber-400', 
                            'icon' => 'schedule'
                        ],
                        'info' => [
                            'bar' => 'bg-blue-400', 
                            'bg' => 'bg-blue-50/50 dark:bg-blue-500/5', 
                            'border' => 'border-blue-100 dark:border-blue-500/10', 
                            'text' => 'text-blue-700 dark:text-blue-400', 
                            'icon' => 'info'
                        ],
                    ][$severity];
                @endphp

                <div class="relative rounded-xl border {{ $sc['border'] }} {{ $sc['bg'] }} overflow-hidden transition-all hover:shadow-sm">
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $sc['bar'] }}"></div>
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $sc['bg'] }} {{ $sc['text'] }} flex items-center justify-center shrink-0 border border-current/10">
                            <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">
                                {{ $sc['icon'] }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <p class="font-bold text-sm text-on-surface">{{ $feed['title'] }}</p>
                                <span class="text-[10px] font-semibold text-on-surface-variant bg-white/50 dark:bg-surface-container-high px-2 py-0.5 rounded-full border border-black/5 dark:border-white/5">
                                    {{ \Carbon\Carbon::parse($feed['created_at'])->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-on-surface-variant leading-relaxed">{{ $feed['message'] }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-sm text-on-surface-variant">No active insights at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-ui.card>