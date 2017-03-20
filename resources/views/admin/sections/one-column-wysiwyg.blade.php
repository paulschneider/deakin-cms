<?php
    $unique      = uniqid();
    $image_value = !empty($section['fields']->background_image) ? $section['fields']->background_image : null;
    $image_url   = $image_value ? $section['attachments'][$image_value]->file->url() : '';

    $container_color = empty($section['fields']->container_color) ? null : $section['fields']->container_color;

    if ($image_value) {
        $style = 'background-image: url(' . $image_url . '); background-repeat: no-repeat;';
    }

?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 color-target-container {{ $container_color }}">

            @include('admin.sections.color-selector', ['selector_class' => 'section-container-color', 'field' => 'container_color', 'option' => 'colors'])

            <div class="row">
                <div class="col-sm-12 image-target" style="{{ $style or null }}">

                    <div class="image-field">
                        @include('admin.attachments.dropzone', [
                            'id' => $unique,
                            'into' => '.one-column-image-'.$unique,
                            'files' => '.jpg,.png,.gif',
                        ])
                        {!! Form::hidden("sections[{$counter}][background_image]", $image_value, ['class' => 'one-column-image-'.$unique]) !!}
                    </div>

                    {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}

                    {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

                    {!! Form::textarea("sections[{$counter}][body]", $section['fields']->body, ['class' => 'form-control wysiwyg inline-editor', 'id' => "sections[{$counter}][body]"]) !!}
                </div>
            </div>
        </div>
    </div>
</div>
