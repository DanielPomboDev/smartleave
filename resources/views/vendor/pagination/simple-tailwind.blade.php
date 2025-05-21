<div class="flex justify-center">
    <nav class="flex items-center">
        <button
            class="btn btn-ghost btn-sm {{ $paginator->onFirstPage() ? 'btn-disabled' : '' }}"
            onclick="window.location.href='{{ $paginator->previousPageUrl() }}'"
        >
            <i class="fi-rr-arrow-left mr-2"></i>
            Previous
        </button>

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="mx-1">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <button
                        class="btn btn-sm {{ $page == $paginator->currentPage() ? 'btn-active' : 'btn-ghost' }}"
                        onclick="window.location.href='{{ $url }}'"
                    >
                        {{ $page }}
                    </button>
                @endforeach
            @endif
        @endforeach

        <button
            class="btn btn-ghost btn-sm {{ $paginator->hasMorePages() ? '' : 'btn-disabled' }}"
            onclick="window.location.href='{{ $paginator->nextPageUrl() }}'"
        >
            Next
            <i class="fi-rr-arrow-right ml-2"></i>
        </button>
    </nav>
</div>
