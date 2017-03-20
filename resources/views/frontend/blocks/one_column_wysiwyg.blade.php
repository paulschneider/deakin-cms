<div class="block {{ $block->class }}">
    <div class="container">
        @if ( ! empty($block->title))
            <h2>{{{ $block->title }}}</h2>
        @endif
        {!! Filter::filter($block->body, ['filter' => ['attachments', 'icons']]) !!}
    </div>
</div>
