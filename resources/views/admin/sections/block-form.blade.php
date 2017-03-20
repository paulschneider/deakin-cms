<div class="row">
    {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
    {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

    <div class="col-sm-12">
        {!! FormField::{"sections[{$counter}][title]"}(['type' => 'text', 'class' => 'editable-title', 'placeholder' => 'Section title...', 'default' => $section['fields']->title, 'label-class' => 'sr-only', 'data-editable-class' => 'text-center']) !!}
    </div>


    <div class="col-sm-12">
        {!! Form::textarea(
            "sections[{$counter}][body]",
            $section['fields']->body,
            [
                'class' => 'form-control wysiwyg inline-editor designated-initializer',
                'id' => "sections[{$counter}][body]"
            ]
        ) !!}
    </div>

    <p>&nbsp;</p>

    <div class="col-sm-12">
        {!! Form::select("sections[{$counter}][block_id]", $blocks['form'], $section['fields']->block_id, ['class' => 'form-control input-sm', 'style' => 'width: 100%;']) !!}
        <div class="form-placeholder">
            <i class="fa fa-check-square-o"></i> Form
        </div>
    </div>

</div>