<?php
    $classes     = [];
    $image_value = $section['fields']->logo;

    $logo = empty($image_value) ? null : $section['attachments'][$image_value]->file->url('medium');

    $container_color = empty($section['fields']->container_color) ? null : $section['fields']->container_color;

    GlobalClass::add('body', ['collapsibles']);
?>

<section class="multiple-section multiple-field credential-column-wysiwyg collapsible-element {{ implode(' ', $classes) }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 contents {{ $container_color }}">

                <div class="row content-row teaser clickable">
                    <div class="col-sm-3 side">
                        <div class="inner">
                            <h2>{!! $section['fields']->level !!}</h2>

                            @if (!empty($section['fields']->stars) && is_numeric($section['fields']->stars))
                                @for ($i=0; $i<$section['fields']->stars; $i++)
                                    <i class="fa fa-star"></i>
                                @endfor
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        {!! Filter::filter($section['fields']->description, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']); !!}
                    </div>
                </div>

                <div class="row content-row body">
                    <div class="col-sm-3 side">
                        <div class="inner">
                            <div class="image-field">
                                @if ($logo)
                                    <img src="{{ $logo }}" class="logo-display img-responsive">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h2>{!! $section['fields']->title !!}</h2>

                        {!! Filter::filter($section['fields']->body, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
