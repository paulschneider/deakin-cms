<?php if (empty($selector_class)) {
        $selector_class = 'section-color';
    }
?>
<div class="{{ $selector_class }}">
    <?php
        $keys     = array_keys($options[$section['template']][$option]);
        $selected = (!empty($section['fields']->{$field})) ? $section['fields']->{$field} : reset($keys);
    ?>
    @foreach ($options[$section['template']][$option] as $key => $value)
        <?php
            $section_class = $selected == $key ? ' selected' : '';
        ?>
        <label>
            {!! Form::radio("sections[{$counter}][{$field}]", $key, ($key == $selected)); !!} <div class="btn btn-xs color-swatch {{ $key }}{{ $section_class }}">{{ $value }}</div>
        </label>
    @endforeach
</div>