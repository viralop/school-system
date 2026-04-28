@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white/5 border border-blue-500/10 cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white/5 border border-blue-500/10 leading-5 rounded-md hover:bg-white/10 hover:text-white focus:outline-none focus:ring ring-blue-500/30 focus:border-blue-500/30 active:bg-white/10 active:text-white transition">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-300 bg-white/5 border border-blue-500/10 leading-5 rounded-md hover:bg-white/10 hover:text-white focus:outline-none focus:ring ring-blue-500/30 focus:border-blue-500/30 active:bg-white/10 active:text-white transition">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white/5 border border-blue-500/10 cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
