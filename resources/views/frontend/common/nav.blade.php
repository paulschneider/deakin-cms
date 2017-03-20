<nav id="navbar-menu" class="hidden">
    <ul>
        @foreach ($items as $key => $item)
            @if ($item->link)
                <li class="{{ $item->data('li_class') }} {{ ($item->hasChildren() ? 'dropdown' : null) }}">
                    <a href="{{ $item->url() }}" {!! $item->attributes() !!}>{{ $item->title }}</a>

                    @include('frontend.common.nav-children', compact('item'))
                </li>
            @endif
        @endforeach

        <li class="mobile_menu_search">
            {!! Form::open(['route' => 'search', 'method' => 'GET']) !!}

                    {!! FormField::q([
                        'default' => '',
                        'type' => 'text',
                        'class' => 'input-lg text',
                        'placeholder' => 'Search site',
                        'label' => 'Search site',
                        'input-wrap-class' => 'col-sm-12',
                        'label-class' => 'sr-only'
                    ]) !!}
                <button class="btn btn-primary sr-only">Search</button>
            {!! Form::close () !!}
        </li>
    </ul>
</nav>
