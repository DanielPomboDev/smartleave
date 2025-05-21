<div class="flex flex-col sm:flex-row justify-between items-center gap-4">
    <div class="flex items-center">
        <span class="text-sm text-gray-600">
            Showing
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-medium">{{ $paginator->total() }}</span>
            results
        </span>
    </div>
    <div class="flex items-center gap-2">
        <div class="flex items-center">
            <span class="text-sm text-gray-600">Rows per page:</span>
            <select class="select select-bordered border-gray-300 focus:border-blue-500 ml-2" onchange="handleChangePerPage(this)">
                <option value="10" {{ $paginator->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $paginator->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $paginator->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $paginator->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <div class="flex items-center">
            <span class="text-sm text-gray-600">Page:</span>
            <select class="select select-bordered border-gray-300 focus:border-blue-500 ml-2" onchange="handleChangePage(this)">
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    <option value="{{ $i }}" {{ $paginator->currentPage() == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>
    </div>
</div>

<script>
    function handleChangePerPage(selectElement) {
        const value = selectElement.value;
        const url = '{{ $paginator->url(1) }}';
        const params = new URLSearchParams(window.location.search);
        params.set('per_page', value);
        window.location.href = url + '?' + params.toString();
    }

    function handleChangePage(selectElement) {
        const value = selectElement.value;
        const url = '{{ $paginator->url(1) }}';
        window.location.href = url.replace(/\?page=\d+/, '') + '?page=' + value;
    }
</script>

<div class="flex justify-center mt-4">
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
