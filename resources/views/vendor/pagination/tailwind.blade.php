@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

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

        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-[var(--text-secondary)] leading-5">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium text-[var(--text-secondary)]">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium text-[var(--text-secondary)]">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium text-[var(--text-secondary)]">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse shadow-sm rounded-md">

                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-[var(--text-tertiary)] bg-[var(--bg-input)] border border-[var(--border-main)] cursor-not-allowed rounded-l-md leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-input)] border border-[var(--border-main)] rounded-l-md leading-5 hover:text-[var(--text-primary)] hover:bg-[var(--bg-hover)] focus:outline-none focus:ring ring-blue-500/30 focus:border-[var(--border-focus)] active:bg-[var(--bg-hover)] transition" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-input)] border border-[var(--border-main)] cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-[var(--text-primary)] bg-blue-600/20 border border-blue-500/30 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-input)] border border-[var(--border-main)] leading-5 hover:text-[var(--text-primary)] hover:bg-[var(--bg-hover)] focus:outline-none focus:ring ring-blue-500/30 focus:border-[var(--border-focus)] active:bg-[var(--bg-hover)] transition" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-[var(--text-secondary)] bg-[var(--bg-input)] border border-[var(--border-main)] rounded-r-md leading-5 hover:text-[var(--text-primary)] hover:bg-[var(--bg-hover)] focus:outline-none focus:ring ring-blue-500/30 focus:border-[var(--border-focus)] active:bg-[var(--bg-hover)] transition" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-[var(--text-tertiary)] bg-[var(--bg-input)] border border-[var(--border-main)] cursor-not-allowed rounded-r-md leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
