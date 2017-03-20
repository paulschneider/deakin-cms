<?php
    $image_value = ! empty($section['fields']->image) ? $section['fields']->image : null;
    $image_url = $image_value ? $section['attachments'][$image_value]->file->url() : '';
    $classes = [];
    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }

    GlobalClass::add('body', 'has-video');
?>
<section class="multiple-section multiple-field video {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?php
                        $style = '';
                        $classes = ['image-target', 'color-changer'];
                        if ( ! empty($section['fields']->image)) {
                            $style = 'background-image: url('.$section['attachments'][$section['fields']->image]->file->url().'); background-repeat: no-repeat; background-position-x: 50%; background-position-y: 50%;';
                        }

                        if ( ! empty($section['fields']->color)) $classes[] = $section['fields']->color;
                    ?>
                    <div class="video-element {{ implode(' ', $classes) }}" style="{{ $style }}" data-video-url="{{ $section['fields']->url }}">
                        <div class="play-button"><i class="fa fa-youtube-play"></i></div>
                        <div class="details">
                            <h2 class="title {{ heading_class($section['fields']->title) }}">
                                {!! $section['fields']->title !!}
                            </h2>
                            <div class="description">
                                {!! $section['fields']->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
