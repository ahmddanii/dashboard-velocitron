@php
    $messages = [
        'success' => session('success') ?? session('status'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
    ];

    $config = [
        'success' => [
            'icon' => 'check_circle',
            'ring' => 'ring-green-200',
            'bg' => 'bg-green-50',
            'title' => 'text-green-800',
            'text' => 'text-green-700',
            'bar' => 'bg-green-500',
        ],
        'error' => [
            'icon' => 'cancel',
            'ring' => 'ring-red-200',
            'bg' => 'bg-red-50',
            'title' => 'text-red-800',
            'text' => 'text-red-700',
            'bar' => 'bg-red-500',
        ],
        'warning' => [
            'icon' => 'warning',
            'ring' => 'ring-yellow-200',
            'bg' => 'bg-yellow-50',
            'title' => 'text-yellow-800',
            'text' => 'text-yellow-700',
            'bar' => 'bg-yellow-500',
        ],
        'info' => [
            'icon' => 'info',
            'ring' => 'ring-blue-200',
            'bg' => 'bg-blue-50',
            'title' => 'text-blue-800',
            'text' => 'text-blue-700',
            'bar' => 'bg-blue-500',
        ],
    ];
@endphp

<div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 w-80" role="region" aria-label="Notifications">
    @foreach ($messages as $type => $message)
        @if ($message)
            @php $c = $config[$type]; @endphp
            <div x-data="toastItem()" x-init="init()" x-show="visible" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="relative overflow-hidden rounded-xl shadow-lg ring-1 {{ $c['ring'] }} {{ $c['bg'] }}">
                {{-- Content --}}
                <div class="flex items-start gap-3 p-4">
                    <span class="material-symbols-outlined text-xl mt-0.5 {{ $c['title'] }}">
                        {{ $c['icon'] }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold capitalize {{ $c['title'] }}">{{ $type }}</p>
                        <p class="text-sm mt-0.5 {{ $c['text'] }}">{{ $message }}</p>
                    </div>
                    <button @click="dismiss()" class="text-gray-400 hover:text-gray-600 transition-colors ml-1 mt-0.5"
                        aria-label="Tutup notifikasi">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>

                {{-- Progress bar --}}
                <div class="absolute bottom-0 left-0 h-0.5 {{ $c['bar'] }} transition-all ease-linear"
                    :style="`width: ${progress}%`"></div>
            </div>
        @endif
    @endforeach
</div>