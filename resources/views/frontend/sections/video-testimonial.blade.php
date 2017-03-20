<?php
    $image_value = ! empty($section['fields']->image) ? $section['fields']->image : null;
    $image_url = $image_value ? $section['attachments'][$image_value]->file->url() : '';
    $classes = [];
    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }

    GlobalClass::add('body', 'has-video');
?>
<section class="multiple-section multiple-field video-testimonial {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col-sm-12">
                    <div class="video-testimonial-element" data-video-url="{{ $section['fields']->url }}">
                        <div class="play-button"><i class="fa fa-youtube-play"></i><div class="play-text">play</div></div>
                        <div class="details">
                            <div class="description">
                                <strong class="type">
                                    {{ $section['fields']->type }}
                                </strong>
                                <h2 class="title quote {{ heading_class($section['fields']->title) }}">
                                    &ldquo;{{ $section['fields']->title }}&rdquo;
                                </h2>
                                <div class="position">
                                    {!! $section['fields']->description !!}<br>
                                </div>
                                <div class="company">
                                    {!! $section['fields']->position !!}
                                </div>
                            </div>
                        </div>

                        @if ( ! empty($section['fields']->image))
                            <div class="image-field image-target" style="background-image: url({{ $section['attachments'][$section['fields']->image]->file->url() }}); no-repeat;"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
