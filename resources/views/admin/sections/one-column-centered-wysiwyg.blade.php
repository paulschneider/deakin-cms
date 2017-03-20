<?php
    $unique = uniqid();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}

            {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

            {!! Form::textarea(
                "sections[{$counter}][body]",
                $section['fields']->body,
                [
                    'class' => 'form-control wysiwyg inline-editor',
                    'id' => "sections[{$counter}][body]"
                ]
            ) !!}
        </div>
    </div>
</div>