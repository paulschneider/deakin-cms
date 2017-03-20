<?php

    $items  = $items->toArray();
    $chunks = [];

    $chunks[] = array_slice($items, 0, 1); // First item.
    $chunks[] = array_slice($items, 1, 2); // 2nd and 3rd.
    $chunks[] = array_slice($items, 3);    // Remaining.

?>

<div class="row">
    @foreach ($chunks as $chunk)
        <div class="col-sm-4">
            <ul class="nav nav-pills">
            @foreach ($chunk as $item)
                @if ($item->link)

                    <li class="parent-list">
                        <a href="{{ $item->url() }}" {!! $item->attributes() !!}>{{ $item->title }}</a>

                        @include('frontend.common.nav-children', compact('item'))
                    </li>

                @endif
            @endforeach
            </ul>
        </div>
    @endforeach
</div>
