@if ($paginator->hasPages())
<nav>
    <ul class="pagination__date">
        {{-- Previous Page Link --}}

        @if ($paginator->onFirstPage())
        <li class="page-item__date disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
            <span class="page__link" aria-hidden="true">&lt;</span>
        </li>
        @else
        <li class="page-item__date">
            <a class="page__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lt;</a>
        </li>
        @endif


        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <li class="page-item__date">
            <a class="page__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&gt;</a>
        </li>
        @else
        <li class="page-item__date disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
            <span class="page__link" aria-hidden="true">&gt;</span>
        </li>
        @endif
    </ul>
</nav>
@endif