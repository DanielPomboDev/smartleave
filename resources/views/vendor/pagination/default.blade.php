@if ($paginator->hasPages())
    <nav class="flex justify-end">
        <div class="btn-group">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-sm" disabled>«</button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm">«</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <button class="btn btn-sm" disabled>{{ $element }}</button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn btn-sm btn-active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="btn btn-sm">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm">»</a>
            @else
                <button class="btn btn-sm" disabled>»</button>
            @endif
        </div>
    </nav>
@endif 