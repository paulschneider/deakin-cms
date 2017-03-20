<div class="arrow-selector">
<label>
    {!! Form::checkbox("sections[{$counter}][{$field}]", 1, $selected); !!}
    <div class="btn btn-xs arrow-swatch{{ $selected ? ' selected' : '' }}"><i class="fa fa-arrow-right"></i></div>
</label>
</div>
