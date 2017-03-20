
<div class="text-center paginator">
    @if ($paginator->lastPage() > 1)
        <div class="btn-group">

            <a href="{{ $paginator->url(1) }}" class="btn {{ $class or 'btn-white' }} previous {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}" type="button"><i class="fa fa-chevron-left"></i></a>

            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <a href="{{ $paginator->url($i) }}" data-page="{{ $i }}" class="btn {{ $class or 'btn-white' }} page {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">{{ $i }}</a>
            @endfor

            <a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="btn {{ $class or 'btn-white' }} next {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}" type="button"><i class="fa fa-chevron-right"></i></a>

        </div>
    @endif
</div>
