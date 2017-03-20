
<div class="form-group">
    {!! Form::label('entity_color', 'Color', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">

        <?php
            $keys     = array_keys($colors);
            $selected = empty($entity->revision->entity_color) ? old('entity_color') : $entity->revision->entity_color;
        ?>

        <div class="entity-color static">
        @foreach ($colors as $key => $value)
            <?php
                $section_class = $selected == $key ? ' selected' : '';
            ?>
            <label>
                {!! Form::radio("entity_color", $key, ($key == $selected)); !!} <div class="btn btn-xs color-swatch {{ $key }}{{ $section_class }}">{{ $value }}</div>
            </label>
        @endforeach
        </div>

        <div id="entity-color-box" class="{{ $selected or null }}"></div>
    </div>
</div>
