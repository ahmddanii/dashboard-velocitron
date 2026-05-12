@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-400 bg-slate-50 border border-outline-variant cursor-default rounded-xl">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-on-surface bg-white border border-outline-variant rounded-xl hover:bg-surface-container transition-all active:scale-95 shadow-sm">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-secondary border border-transparent rounded-xl hover:bg-secondary/90 transition-all active:scale-95 shadow-lg shadow-secondary/20">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-200 border border-transparent cursor-default rounded-xl">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-on-surface-variant font-medium">
                    {!! __('Showing') !!}
                    <span class="font-bold text-on-surface">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-bold text-on-surface">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-bold text-on-surface">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-outline-variant">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3 py-2 bg-slate-50 text-slate-300 cursor-default" aria-hidden="true">
                                <span class="material-symbols-outlined text-sm">chevron_left</span>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-2 bg-white text-on-surface-variant hover:text-secondary hover:bg-secondary/5 transition-all" aria-label="{{ __('pagination.previous') }}">
                            <span class="material-symbols-outlined text-sm">chevron_left</span>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 bg-white text-slate-400 cursor-default text-xs font-bold">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 bg-secondary text-white text-xs font-black shadow-inner">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 bg-white text-on-surface-variant hover:text-secondary hover:bg-secondary/5 text-xs font-bold transition-all" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-2 bg-white text-on-surface-variant hover:text-secondary hover:bg-secondary/5 transition-all" aria-label="{{ __('pagination.next') }}">
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3 py-2 bg-slate-50 text-slate-300 cursor-default" aria-hidden="true">
                                <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
