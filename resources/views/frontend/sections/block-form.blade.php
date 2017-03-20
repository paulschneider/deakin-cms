<?php
    $classes = [];
    foreach (['section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }
?>

<section class="container block-form container-mobile-no-padding {{ implode(' ', $classes) }}">
    <div class="col-sm-12">

        @if ( ! empty($section['fields']->title))
            <h2 class="text-center pull-out">{{ $section['fields']->title }}</h2>
        @endif

        @if ( ! empty($section['fields']->body))
            {!! $section['fields']->body !!}
            <p>&nbsp;</p>
        @endif

        <div class="row">
            @include('common.alerts')
        </div>

        {!! BlockManager::getBlockContent((int) $section['fields']->block_id, false) !!}

    </div>
</section>
