{!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
{!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

<div class="row-same-height">
    @foreach (['column_one_content', 'column_two_content'] as $field)
        <div class="half-width">
        <?php
            $class = 'form-control wysiwyg inline-editor basic';
            if ($field == 'column_one_content') $class .= ' designated-initializer';
            $field_name = str_replace('content', '', $field);
            $color_value = empty($section['fields']->{$field_name.'color'}) ? null : $section['fields']->{$field_name.'color'};
        ?>
            @include('admin.sections.color-selector', ['selector_class' => 'section-column-color', 'field' => $field_name.'color', 'option' => 'colors'])


            <div class="image-field">
                <?php
                    $classes = ['image-target', 'color-changer', $color_value];
                    $unique = uniqid();
                    $image_value = ! empty($section['fields']->{$field_name.'image'}) ? $section['fields']->{$field_name.'image'} : null;
                    $image_url = '';
                    $style = '';

                    if ($image_value) {
                        $image_url = $section['attachments'][$image_value]->file->url();
                        $style = 'background-image: url('.$image_url.');';
                    }
                ?>

                @include('admin.attachments.dropzone', [
                    'id' => $unique,
                    'into' => '.two-column-full-width-image-'.$unique,
                    'files' => '.jpg,.png,.gif',
                ])

                {!! Form::hidden(
                    "sections[{$counter}][{$field_name}image]",
                    $section['fields']->{$field_name.'image'},
                    ['class' => 'two-column-full-width-image-'.$unique]
                ) !!}
            </div>

            <div class="inner {{ implode(' ', $classes) }}" style="{{ $style }}">

                {!! Form::textarea(
                    "sections[{$counter}][{$field}]",
                    $section['fields']->{$field},
                    ['class' => $class, 'id' => "sections[{$counter}][{$field}]"]
                ) !!}
            </div>

        </div>
    @endforeach
</div>
