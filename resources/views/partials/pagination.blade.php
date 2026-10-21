@if ($items->hasPages())
    <nav class="mt-5 flex flex-wrap items-center justify-end gap-2" aria-label="Navigasi halaman">
        @if ($items->onFirstPage())
            <span class="page-item page-item-disabled"><i class="bi bi-chevron-left"></i></span>
        @else
            <a class="page-item" href="{{ $items->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
        @endif

        @php
            $from = max(1, $items->currentPage() - 2);
            $to = min($items->lastPage(), $items->currentPage() + 2);
        @endphp

        @if ($from > 1)
            <a class="page-item" href="{{ $items->url(1) }}">1</a>
            @if ($from > 2)<span class="page-item page-item-disabled">…</span>@endif
        @endif

        @foreach ($items->getUrlRange($from, $to) as $page => $url)
            @if ($page == $items->currentPage())
                <span class="page-item page-item-active">{{ $page }}</span>
            @else
                <a class="page-item" href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if ($to < $items->lastPage())
            @if ($to < $items->lastPage() - 1)<span class="page-item page-item-disabled">…</span>@endif
            <a class="page-item" href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
        @endif

        @if ($items->hasMorePages())
            <a class="page-item" href="{{ $items->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
        @else
            <span class="page-item page-item-disabled"><i class="bi bi-chevron-right"></i></span>
        @endif
    </nav>
@endif
