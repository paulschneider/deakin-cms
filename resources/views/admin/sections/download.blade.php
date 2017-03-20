<?php
    $unique = uniqid();
    $dl_value = ! empty($section['fields']->download) ? $section['fields']->download : old('download');
    $dl_url = $dl_value ? $section['attachments'][$dl_value]->file->url() : '';

    $classes = ['color-changer'];
    if ( ! empty($section['fields']->color)) $classes[] = $section['fields']->color;

?>
<div class="container color-target-top">
    <div class="row">
        <div class="col-sm-12">
            @include('admin.sections.color-selector', ['selector_class' => 'section-column-color', 'field' => 'color', 'option' => 'colors'])
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <div class="download-element {{ implode(' ', $classes) }}">

                {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
                {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

                <div class="col-sm-7">
                    {!! Form::text("sections[{$counter}][title]", $section['fields']->title, ['class' => 'form-control editable-title', 'id' => "sections[{$counter}][title]", 'data-editable-class' => 'title', 'placeholder' => 'Title']) !!}
                    {!! Form::text("sections[{$counter}][description]", $section['fields']->description, ['class' => 'form-control editable-title', 'id' => "sections[{$counter}][description]", 'data-tag' => 'p', 'data-editable-class' => 'description', 'placeholder' => 'Description']) !!}
                </div>

                <div class="col-sm-5 download-link">

                {!! Form::text("sections[{$counter}][action]", $section['fields']->action, ['class' => 'form-control editable-title', 'id' => "sections[{$counter}][action]", 'data-tag' => 'p', 'data-editable-class' => 'action', 'placeholder' => 'Download now text']) !!}

                    <div class="download-field">
                        @include('admin.attachments.dropzone', [
                            'id' => $unique,
                            'into' => '.download-file-'.$unique,
                            'files' => '.pdf,.doc,.docx,.xls,.xlsx,.zip',
                            'old' => $dl_value,
                        ])
                        {!! Form::hidden("sections[{$counter}][download]", $dl_value, ['class' => 'download-file-'.$unique]) !!}
                        <div class="preview-testimonial download-target"></div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
