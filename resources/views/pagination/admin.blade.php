@props(['pageLink'])
@if ($paginator->hasPages())
    <nav class="admin-pagination" aria-label="Pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="admin-page-btn admin-page-disabled">&laquo; Prev</span>
        @else
            <a class="admin-page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Prev</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="admin-page-btn admin-page-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="admin-page-btn admin-page-active">{{ $page }}</span>
                    @else
                        <a class="admin-page-btn" href="{{ $url }}#{{$pageLink}}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a class="admin-page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Next &raquo;</a>
        @else
            <span class="admin-page-btn admin-page-disabled">Next &raquo;</span>
        @endif
    </nav>
@endif
