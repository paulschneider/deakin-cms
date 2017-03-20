
    <ul class="nav nav-pills">

        @foreach ($items as $item)
            @if ($item->link)

                <li><a href="{{ $item->url() }}" {!! $item->attributes() !!}>{{ $item->title }}</a></li>

            @endif
        @endforeach

    </ul>