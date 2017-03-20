<?php
    $classes = [];
    foreach (['section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }

    $block = BlockManager::getBlock((int) $section['fields']->block_id);
?>

@if ($block)

<section class="multiple-section multiple-field block-widget {{ implode(' ', $classes) }}" id="widget-{{ str_slug($block->name) }}">
    {!! BlockManager::getBlockContent($block, false) !!}
</section>

@endif
