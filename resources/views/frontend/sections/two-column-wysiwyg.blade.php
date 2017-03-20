<?php
    $classes = [];
    foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
        if (get_property($section['fields'], $property)) $classes[] = $section['fields']->{$property};
    }
?>
<section class="multiple-section multiple-field two-column-wysiwyg {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            @if ( ! empty($section['fields']->section_title))
                <div class="row">
                    <div class="col-sm-12 center">
                        <h2 class="pull-out">{!! $section['fields']->section_title !!}</h2>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="row-same-height">
                    @foreach (['column_one_content', 'column_two_content'] as $field)
                        <div class="col col-md-6">
                            <?php
                                $classes = [];
                                $base = str_replace('content', '', $field);
                                $classes[] = empty($section['fields']->{$base.'color'}) ? null : $section['fields']->{$base.'color'};
                                if ( ! empty($section['fields']->{$base.'arrow'})) $classes[] = 'has-arrow';
                            ?>

                            <div class="inner color-changer {{ implode(' ', $classes) }}">
                                <div class="content-wrapper">
                                    {!! Filter::filter($section['fields']->{$field}, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']); !!}
                                </div>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
