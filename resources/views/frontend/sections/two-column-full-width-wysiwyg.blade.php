<?php
    $classes = [];
    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }
?>
<section class="multiple-section multiple-field two-column-full-width-wysiwyg {{ implode(' ', $classes) }}">
    <div class="section">

        <div class="row-same-height">
            @foreach (['column_one_content', 'column_two_content'] as $field)
                <div class="half-width">
                    <?php
                        $class = 'form-control wysiwyg inline-editor basic';
                        $field_name = str_replace('content', '', $field);
                        $color_value = empty($section['fields']->{$field_name.'color'}) ? null : $section['fields']->{$field_name.'color'};
                        $classes = ['image-target', 'color-changer', $color_value];

                        $image_value = empty($section['fields']->{$field_name.'image'}) ? null : $section['fields']->{$field_name.'image'};
                        $image_url = '';
                        $style = '';

                        if ($image_value) {
                            $image_url = $section['attachments'][$image_value]->file->url();
                            $style = 'background-image: url('.$image_url.');';
                        }
                    ?>

                    <div class="inner {{ implode(' ', $classes) }}" style="{{ $style }}">
                        <div class="content-wrapper">
                            {!! Filter::filter($section['fields']->{$field}, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']); !!}
                        </div>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</section>
