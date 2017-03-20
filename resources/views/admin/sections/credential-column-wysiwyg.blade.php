<?php
    $unique      = uniqid();
    $image_value = !empty($section['fields']->logo) ? $section['fields']->logo : null;
    $image_url   = $image_value ? $section['attachments'][$image_value]->file->url() : '';

    $style = '';
    if ($image_url) {
        $style = 'background-image: url(' . $image_url . ');';
    }

    $container_color = empty($section['fields']->container_color) ? null : $section['fields']->container_color;

    $stars_options = [
        '' => 'No stars',
        1  => '1 star',
        2  => '2 star',
        3  => '3 star',
        4  => '4 star',
        5  => '5 star',
    ];

?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 color-target-container {{ $container_color }}">

            {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
            {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

            @include('admin.sections.color-selector', ['selector_class' => 'section-container-color', 'field' => 'container_color', 'option' => 'colors'])

            <div class="row content-row teaser">
                <div class="col-sm-3 side">
                    <div class="inner">
                        {!! Form::text("sections[{$counter}][level]", $section['fields']->level, ['class' => 'form-control editable-title', 'id' => "sections[{$counter}][level]", 'data-editable-class' => 'level', 'placeholder' => 'Level']) !!}
                        {!! Form::select("sections[{$counter}][stars]", $stars_options, $section['fields']->stars, ['class' => 'form-control editable-stars', 'id' => "sections[{$counter}][stars]", 'data-editable-class' => 'stars']) !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::textarea("sections[{$counter}][description]", $section['fields']->description, ['class' => 'form-control wysiwyg inline-editor', 'id' => "sections[{$counter}][description]"]) !!}
                </div>
            </div>

            <div class="row content-row body">
                <div class="col-sm-3 side">
                    <div class="inner">
                        <div class="image-field">
                            @include('admin.attachments.dropzone', [
                                'id' => $unique,
                                'into' => '.logo-'.$unique,
                                'files' => '.jpg,.png,.gif',
                            ])
                            {!! Form::hidden("sections[{$counter}][logo]", $image_value, ['class' => 'logo-'.$unique]) !!}
                            <div class="logo-box image-target" style="{{ $style }}"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    {!! Form::text("sections[{$counter}][title]", $section['fields']->title, ['class' => 'form-control editable-title', 'id' => "sections[{$counter}][title]", 'data-editable-class' => 'title', 'placeholder' => 'Title']) !!}
                    {!! Form::textarea("sections[{$counter}][body]", $section['fields']->body, ['class' => 'form-control wysiwyg inline-editor', 'id' => "sections[{$counter}][body]"]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
