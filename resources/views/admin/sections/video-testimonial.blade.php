<?php
    $unique = uniqid();
    $image_value = ! empty($section['fields']->image) ? $section['fields']->image : null;
    $image_url = $image_value ? $section['attachments'][$image_value]->file->url() : '';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">

            <?php
                $style = '';
                if ( ! empty($section['fields']->image)) {
                    $style = 'background-image: url('.$image_url.');';
                }
            ?>

            <div class="video-testimonial-element">

                <div class="play-button"><i class="fa fa-youtube-play"></i></div>

                {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
                {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

                <div class="details">
                    {!! Form::text("sections[{$counter}][type]", $section['fields']->type, ['class' => 'form-control designated-initializer editable-type', 'id' => "sections[{$counter}][type]", 'data-editable-class' => 'type', 'placeholder' => 'Type']) !!}
                    {!! Form::text("sections[{$counter}][title]", $section['fields']->title, ['class' => 'form-control', 'id' => "sections[{$counter}][title]", 'data-editable-class' => 'title', 'placeholder' => 'Quote']) !!}
                    {!! Form::text("sections[{$counter}][description]", $section['fields']->description, ['class' => 'form-control editable-description', 'id' => "sections[{$counter}][description]", 'data-tag' => 'p', 'data-editable-class' => 'description', 'placeholder' => 'Person']) !!}
                    {!! Form::text("sections[{$counter}][position]", $section['fields']->position, ['class' => 'form-control editable-position', 'id' => "sections[{$counter}][position]", 'data-tag' => 'p', 'data-editable-class' => 'position', 'placeholder' => 'Position']) !!}
                    {!! Form::text("sections[{$counter}][url]", $section['fields']->url, ['class' => 'form-control video-url', 'id' => "sections[{$counter}][body]", 'placeholder' => 'Video url']) !!}
                </div>

                <div class="image-field image-target" style="{{ $style }}">

                    @include('admin.attachments.dropzone', [
                        'id' => $unique,
                        'into' => '.video-image-'.$unique,
                        'files' => '.jpg,.png,.gif',
                    ])
                    {!! Form::hidden("sections[{$counter}][image]", $image_value, ['class' => 'video-image-'.$unique]) !!}

                </div>

            </div>
        </div>

    </div>
</div>