<?php
    $classes = [];

    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }
?>
<section class="multiple-section multiple-field one-column-centered-wysiwyg {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="col-content">
                        {!! Filter::filter($section['fields']->body, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
