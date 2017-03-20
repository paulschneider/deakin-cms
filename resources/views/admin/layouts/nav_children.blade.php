
    @if($item->hasChildren())
        <ul class="nav nav-{{ number_spellout_ordinal($item->data('depth')) }}-level">
            @foreach($item->children() as $child)
                <li class="{{ $child->data('li_class') }}">
                    <a href="{{ $child->url() }}" {!! $child->attributes() !!}>{{ $child->title }}
                    @if ($child->hasChildren())
                        <span class="fa arrow"></span>
                    @endif
                    </a>

                    @include('admin.layouts.nav_children', ['item' => $child])
                </li>
            @endforeach
        </ul>
    @endif