<?php
    $classes     = [];
    $image_value = $section['fields']->background_image;
    
    if (empty($image_value)) {
        $style = '';
    } else {
        $style = ' style="background-image: url(' . $section['attachments'][$image_value]->file->url() . ');"';
        $classes[] = 'has-bg-image';
    }

    $container_color = empty($section['fields']->container_color) ? null : $section['fields']->container_color;

    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) {
            $classes[] = $section['fields']->{$property};
        }
    }
?>

<section class="multiple-section multiple-field one-column-wysiwyg {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row color-target-container {{ $container_color }}"{!! $style !!}>
                <div class="col-sm-12 image-target">
                    <div class="col-content">
                        {!! Filter::filter($section['fields']->body, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
