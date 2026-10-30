@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-content-muted">
            Showing <span class="font-medium text-content">{{ $paginator->firstItem() }}</span>
            to <span class="font-medium text-content">{{ $paginator->lastItem() }}</span>
            of <span class="font-medium text-content">{{ $paginator->total() }}</span> results
        </p>

        <div class="inline-flex items-center gap-1 self-start rounded-lg border border-border bg-surface-card p-1 sm:self-auto">
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="@lang('pagination.previous')"
                      class="inline-flex h-9 w-9 items-center justify-center rounded-md text-content-muted/50">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.56 10l4.21 3.71a.75.75 0 1 1-.99 1.12l-4.8-4.22a.75.75 0 0 1 0-1.12l4.75-4.28a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"
                   class="inline-flex h-9 w-9 items-center justify-center rounded-md text-content-secondary hover:bg-surface-alt hover:text-content focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.56 10l4.21 3.71a.75.75 0 1 1-.99 1.12l-4.8-4.22a.75.75 0 0 1 0-1.12l4.75-4.28a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span aria-disabled="true" class="inline-flex h-9 min-w-9 items-center justify-center px-2 text-sm text-content-muted">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="inline-flex h-9 min-w-9 items-center justify-center rounded-md bg-primary px-2 text-sm font-semibold text-primary-foreground">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" aria-label="Go to page {{ $page }}"
                               class="inline-flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm text-content-secondary hover:bg-surface-alt hover:text-content focus:outline-none focus:ring-2 focus:ring-primary/40">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"
                   class="inline-flex h-9 w-9 items-center justify-center rounded-md text-content-secondary hover:bg-surface-alt hover:text-content focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.44 10 7.23 6.29a.75.75 0 1 1 .99-1.12l4.8 4.22a.75.75 0 0 1 0 1.12l-4.75 4.28a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span aria-disabled="true" aria-label="@lang('pagination.next')"
                      class="inline-flex h-9 w-9 items-center justify-center rounded-md text-content-muted/50">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.44 10 7.23 6.29a.75.75 0 1 1 .99-1.12l4.8 4.22a.75.75 0 0 1 0 1.12l-4.75 4.28a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
