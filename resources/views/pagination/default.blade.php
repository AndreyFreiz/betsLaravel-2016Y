<?php
// config
$link_limit = 20; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
    <br>
    <ul class="pagination">
        @if($paginator->currentPage() !== 1)
        <li id="page-{{ $paginator->currentPage()-1 }}">
            <a style="font-size: 13px;" ajax-load="true" href="{{ $paginator->url($paginator->currentPage()-1) }}">‹</a>
        </li>
        @endif

        @if($paginator->currentPage() > 13)
        <li id="page-{{ $paginator->url($paginator->currentPage()-11) }}">
            <a style="font-size: 13px;" ajax-load="true" href="{{ $paginator->url($paginator->currentPage()-11) }}">...</a>
        </li>
        @endif

        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
                $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li id="page-{{ $i }}" class="{{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
                    <a style="font-size: 13px;" ajax-load="true" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        @if($paginator->total() > 10)
        <li id="page-{{ $paginator->url($paginator->currentPage()+10) }}">
            <a style="font-size: 13px;" ajax-load="true" href="{{ $paginator->url($paginator->currentPage()+10) }}">...</a>
        </li>
        @endif

        @if($paginator->currentPage() != $paginator->lastPage())
        <li id="page-{{ $paginator->currentPage()+1 }}">
            <a style="font-size: 13px;" ajax-load="true" href="{{ $paginator->url($paginator->currentPage()+1) }}">›</a>
        </li>
        @endif

    </ul>
        @endif