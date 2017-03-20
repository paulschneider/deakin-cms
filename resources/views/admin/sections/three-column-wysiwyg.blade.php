<div class="container">
    {!! Form::hidden("sections[{$counter}][id]", $section['id'], ['class' => 'section-id']) !!}
    {!! Form::hidden("sections[{$counter}][template]", $section['template'], ['class' => 'section-template']) !!}

    <?php
        $container_color = empty($section['fields']->container_color) ? null : $section['fields']->container_color;
        $body = empty($section['fields']->body) ? null : $section['fields']->body;
    ?>

    <div class="row">
        <div class="col-sm-12 color-target-container {{ $container_color }}">

            @include('admin.sections.color-selector', ['selector_class' => 'section-container-color', 'field' => 'container_color', 'option' => 'colors'])

            <div class="row">
                <div class="col-sm-12">
                    <?php $section_title = isset($section['fields']->section_title) ? $section['fields']->section_title : null; ?>
                    {!! FormField::{"sections[{$counter}][section_title]"}(['type' => 'text', 'class' => 'editable-title', 'placeholder' => 'Section title...', 'default' => $section_title, 'label-class' => 'sr-only']) !!}
                </div>
            </div>

            <div class="row-same-height">
                @foreach (['column_one_content', 'column_two_content', 'column_three_content'] as $field)
                    <div class="col col-md-4">
                    <?php
                        $class = 'form-control wysiwyg inline-editor basic';
                        $base = str_replace('content', '', $field);
                        $color_value = empty($section['fields']->{$base.'color'}) ? null : $section['fields']->{$base.'color'};
                        $arrow_selected = empty($section['fields']->{$base.'arrow'}) ? false : $section['fields']->{$base.'arrow'};
                    ?>

                        @include('admin.sections.color-selector', ['selector_class' => 'section-column-color', 'field' => $base.'color', 'option' => 'colors'])

                        @include('admin.sections.arrow-selector', ['field' => $base.'arrow', 'selected' => $arrow_selected])

                        <div class="inner color-changer {{ $color_value }}{{ $arrow_selected ? ' has-arrow' : '' }}">
                            {!! Form::textarea("sections[{$counter}][{$field}]", $section['fields']->{$field}, ['class' => $class, 'id' => "sections[{$counter}][{$field}]"]) !!}
                        </div>
                        <div style="clear: both"></div>
                    </div>
                @endforeach
            </div>

            {!! Form::textarea("sections[{$counter}][body]", $body, ['class' => 'form-control wysiwyg inline-editor', 'id' => "sections[{$counter}][body]"]) !!}

        </div>
    </div>
</div>