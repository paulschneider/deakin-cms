
    @if ($item->hasChildren() && $item->data('depth') < 2)

        <ul class="nav-{{ number_spellout_ordinal($item->data('depth')) }}-level">

            @foreach($item->children() as $child)

                <?php
                    $class = $child->hasChildren() ? ' has-children' : '';
                ?>

                <li class="child-list {{ $child->data('li_class') }}{{ $class }}">

                    <a href="{{ $child->url() }}" {!! $child->attributes() !!}>{{ $child->title }}</a>

                    @include('frontend.common.nav-children', ['item' => $child])
                </li>
            @endforeach

        </ul>

    @endif
