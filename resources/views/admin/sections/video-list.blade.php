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

            <div class="video-list-element">
                {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
                {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}
                <div class="row">
                    <div class="col-sm-4">
                        <!-- <div class="play-button"><i class="fa fa-youtube-play"></i></div> -->
                        <div class="image-field image-target imgMinHeight" style="{{ $style }}">

                            @include('admin.attachments.dropzone', [
                                'id' => $unique,
                                'into' => '.video-image-'.$unique,
                                'files' => '.jpg,.png,.gif',
                            ])
                            {!! Form::hidden("sections[{$counter}][image]", $image_value, ['class' => 'video-image-'.$unique]) !!}

                        </div>
                    </div>
                    <div class="col-sm-8">
                         <div class="details">
                            {!! Form::text("sections[{$counter}][title]", $section['fields']->title, ['class' => 'form-control', 'id' => "sections[{$counter}][title]", 'data-editable-class' => 'title', 'placeholder' => 'Title']) !!}
                            {!! Form::text("sections[{$counter}][description]", $section['fields']->description, ['class' => 'form-control editable-description', 'id' => "sections[{$counter}][description]", 'data-tag' => 'p', 'data-editable-class' => 'description', 'placeholder' => 'Description']) !!}
                            {!! Form::text("sections[{$counter}][url]", $section['fields']->url, ['class' => 'form-control video-url', 'id' => "sections[{$counter}][body]", 'placeholder' => 'Video url']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
