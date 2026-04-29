@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-[var(--text-tertiary)] bg-[var(--bg-input)] border border-[var(--border-main)] cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-input)] border border-[var(--border-main)] leading-5 rounded-md hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)] focus:outline-none focus:ring ring-blue-500/30 focus:border-[var(--border-focus)] active:bg-[var(--bg-hover)] active:text-[var(--text-primary)] transition">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-input)] border border-[var(--border-main)] leading-5 rounded-md hover:bg-[var(--bg-hover)] hover:text-[var(--text-primary)] focus:outline-none focus:ring ring-blue-500/30 focus:border-[var(--border-focus)] active:bg-[var(--bg-hover)] active:text-[var(--text-primary)] transition">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-[var(--text-tertiary)] bg-[var(--bg-input)] border border-[var(--border-main)] cursor-not-allowed leading-5 rounded-md">
                {!! __('pagination.next') !!}
            </span>
        @endif

    </nav>
@endif
