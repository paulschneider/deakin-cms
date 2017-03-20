<div class="row">
    {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
    {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}
    <div class="col-sm-12">
        {!! Form::select("sections[{$counter}][block_id]", $blocks['widget'], $section['fields']->block_id, ['class' => 'form-control input-sm block-switcher', 'style' => 'width: 100%;']) !!}
    </div>
    <div class="block-content"></div>
</div>