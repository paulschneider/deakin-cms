<?php
	$classes = [];
	foreach (['section_colors', 'section_pad_top', 'section_pad_bottom', 'section_pad_left', 'section_pad_right'] as $property) {
	    if (get_property($section['fields'], $property)) {
	        $classes[] = $section['fields']->{$property};
	    }

	}

	$container_color = empty($section['fields']->container_color) ? null : $section['fields']->container_color;

	$ray_bg = null;

	if (!empty($section['fields']->section_title)) {
	    if ($container_color == "grey") {
	        $ray_bg = 'ray-bg';
	    } else {
	        $ray_bg = 'transparent-ray-bg';
	    }
	}
?>
<section class="multiple-section multiple-field three-column-wysiwyg sections-margin-bottom {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col-sm-12 color-target-container {{ $container_color }} {{ $ray_bg }}">

                    @if ( ! empty($section['fields']->section_title))
                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="pull-out text-center">{!! $section['fields']->section_title !!}</h1>
                            </div>
                        </div>
                    @endif

                    <div class="row-same-height">
                        @foreach (['column_one_content', 'column_two_content', 'column_three_content'] as $field)
                            <div class="col col-sm-4">
                                <?php
                                	$classes   = [];
                                	$base      = str_replace('content', '', $field);
                                	$classes[] = empty($section['fields']->{$base . 'color'}) ? null : $section['fields']->{$base . 'color'};
                                	if (!empty($section['fields']->{$base . 'arrow'})) {
                                	    $classes[] = 'has-arrow';
                                	}

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

                    @if ( ! empty($section['fields']->body))
                        <div class="row">
                            <div class="col-sm-12 last-paragraph">
                                {!! Filter::filter($section['fields']->body, ['filter' => ['attachments', 'icons', 'figures'], 'purifier' => 'full_html']); !!}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>
