<?php
    $unique = uniqid();
    $image_value = ! empty($section['fields']->image) ? $section['fields']->image : null;
    $image_url = $image_value ? $section['attachments'][$image_value]->file->url() : '';
?>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 color-target-top">

            @include('admin.sections.color-selector', ['selector_class' => 'section-column-color', 'field' => 'color', 'option' => 'colors'])

            <div class="image-field">
                @include('admin.attachments.dropzone', [
                    'id' => $unique,
                    'into' => '.video-image-'.$unique,
                    'files' => '.jpg,.png,.gif',
                ])
                {!! Form::hidden("sections[{$counter}][image]", $image_value, ['class' => 'video-image-'.$unique]) !!}
            </div>

            <?php
                $style = '';
                $classes = ['image-target', 'color-changer'];
                if ( ! empty($section['fields']->image)) {
                    $style = 'background-image: url('.$image_url.');';
                }
                if ( ! empty($section['fields']->color)) $classes[] = $section['fields']->color;
            ?>

            <div class="video-element {{ implode(' ', $classes) }}" style="{{ $style }}">
                <div class="play-button"><i class="fa fa-youtube-play"></i></div>
                {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
                {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}
                <div class="details">
                    {!! Form::text("sections[{$counter}][title]", $section['fields']->title, ['class' => 'form-control designated-initializer editable-title', 'id' => "sections[{$counter}][title]", 'data-editable-class' => 'title']) !!}
                    {!! Form::text("sections[{$counter}][description]", $section['fields']->description, ['class' => 'form-control editable-title', 'id' => "sections[{$counter}][description]", 'data-tag' => 'p', 'data-editable-class' => 'description']) !!}
                </div>
            </div>
            {!! Form::text("sections[{$counter}][url]", $section['fields']->url, ['class' => 'form-control video-url', 'id' => "sections[{$counter}][body]", 'placeholder' => 'Video url']) !!}
        </div>
    </div>
</div>