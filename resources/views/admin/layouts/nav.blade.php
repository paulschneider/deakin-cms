
@foreach($items as $item)

    <li class="{{ $item->data('li_class') }}">
        @if ($item->link)
            <a href="{{ $item->url() }}" {!! $item->attributes() !!}>
                @if ( ! $item->icon && isset($default_icon) )
                    <i class="fa fa-fw {{ $default_icon }}"></i>
                @else
                    {!! $item->icon !!}
                @endif

                <span class="nav-label">{!! $item->title !!}</span>

                @if ($item->hasChildren())
                    <span class="fa arrow"></span>
                @endif
            </a>
        @endif

        @include('admin.layouts.nav_children', ['item' => $item])

    </li>

    @if ($item->divider)
        <li{{ HTML::attributes($item->divider) }}></li>
    @endif

@endforeach
