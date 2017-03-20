<?php
    $image_value = ! empty($section['fields']->image) ? $section['fields']->image : null;
    $image_url = $image_value ? $section['attachments'][$image_value]->file->url() : '';
    $classes = [];
    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }

    GlobalClass::add('body', 'has-video-list');
?>

<section class="multiple-section multiple-field video-list {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row color-target-container">
                <div class="col-sm-12 image-target">
                    <div class="videoList">
                        <div class="item video-list-element" data-video-url="{{ $section['fields']->url }}">
                            <div class="imgWrap">
                                <div class="play-button">
                                    <i class="fa fa-youtube-play"></i>
                                    <div class="play-text">play</div>
                                </div>
                                <img src="{{ $image_url }}" />
                            </div>
                            <h2>{{ $section['fields']->title }}</h2>
                            <p>{!! $section['fields']->description !!}</p>
                            <span>Watch Professional Development Video</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
