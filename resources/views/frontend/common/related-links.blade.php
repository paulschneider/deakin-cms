@if ($related_links->count())

<aside class="related-links container">

    <h3>Related Links</h3>

    <ul>
        @foreach ($related_links as $link)
            <li>
                <a href="{!! $link->url() !!}">
                    @if ($link->icon)
                        <span class="svg-icon">
                            {!! $link->icon->svg !!}
                        </span>
                    @endif
                    <span class="title">{{ $link->title }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</aside>
@endif