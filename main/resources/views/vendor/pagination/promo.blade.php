@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true" class="page-link">PREVIOUS</span>
                </li>
            @else
                <li>
                    <a class="page-link" href="{{ url('sale/'.request()->segment(2).'/'.$paginator->previousPageUrl()) }}" rel="prev" aria-label="@lang('pagination.previous')">PREVIOUS</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><span class="page-link"><a href="{{ url('sale/'.request()->segment(2).'/'.$url) }}">{{ $page }}</a></span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a class="page-link" href="{{ url('sale/'.request()->segment(2).'/'.$paginator->nextPageUrl()) }}" rel="next" aria-label="@lang('pagination.next')">NEXT</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true" class="page-link">NEXT</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
