            <?php GlobalClass::add('body', ['multiple-sections', 'collapsibles']); ?>

            <div class="sections">
               <div class="dd multiple-fields">

                    <?php
                        // Get the old values or the values from the entity passed in
                        $sections = SectionsSession::get();
                        $options = config('sections.options');
                        if (empty($sections)) {
                            $sections = [];
                            if ($entity && $entity->revision->sections->count()) $sections = $entity->revision->section_fields;
                        }
                    ?>

                    <ol class="dd-list col-md-12 section-multiple-fields">
                        @if (count($sections))
                            <?php $counter = 0; ?>
                            @foreach ($sections as $section)
                                <?php
                                    // Section level styles
                                    $classes = [
                                        $section['info']['class']
                                    ];

                                    if ( ! empty($section['fields']->section_colors)) $classes[] = $section['fields']->section_colors;

                                    if ($pad_top = get_property($section['fields'], 'section_pad_top')) $classes[] = $pad_top;
                                    if ($pad_bottom = get_property($section['fields'], 'section_pad_bottom')) $classes[] = $pad_bottom;
                                    if ($pad_left = get_property($section['fields'], 'section_pad_left')) $classes[] = $pad_left;
                                    if ($pad_right = get_property($section['fields'], 'section_pad_right')) $classes[] = $pad_right;
                                ?>

                                <li class="dd-item multiple-field multiple-section clearfix {{ implode(' ', $classes) }}" data-id="{{ $counter }}">
                                    <div class="dd-handle drag">
                                        <i class="fa fa-arrows"></i>
                                        <h5>{{ $section['info']['name'] }}</h5>
                                    </div>
                                    <div class="section">
                                        {{-- The section template --}}
                                        @include($section['info']['admin_template'], ['section' => $section, 'counter' => $counter])
                                    </div>

                                    {{-- Section Color Switcher --}}
                                    @if ( ! empty($options[$section['template']]['section_colors']))
                                        @include('admin.sections.color-selector', ['field' => 'section_colors', 'option' => 'section_colors'])
                                    @endif
                                    @if ( isset($section['info']['fields']['section_pad_top']))
                                        {!! Form::hidden("sections[{$counter}][section_pad_top]", get_property($section['fields'], 'section_pad_top'), ['class' => 'section-top-padding-field']) !!}
                                    @endif
                                    @if ( isset($section['info']['fields']['section_pad_bottom']))
                                        <?php $pad_bottom = ! empty($section['fields']->section_pad_bottom) ? $section['fields']->section_pad_bottom : null; ?>
                                        {!! Form::hidden("sections[{$counter}][section_pad_bottom]", get_property($section['fields'], 'section_pad_bottom'), ['class' => 'section-bottom-padding-field']) !!}
                                    @endif
                                    @if ( isset($section['info']['fields']['section_pad_left']))
                                        <?php $pad_left = ! empty($section['fields']->section_pad_left) ? $section['fields']->section_pad_left : null; ?>
                                        {!! Form::hidden("sections[{$counter}][section_pad_left]", get_property($section['fields'], 'section_pad_left'), ['class' => 'section-left-padding-field']) !!}
                                    @endif
                                    @if ( isset($section['info']['fields']['section_pad_right']))
                                        <?php $pad_right = ! empty($section['fields']->section_pad_right) ? $section['fields']->section_pad_right : null; ?>
                                        {!! Form::hidden("sections[{$counter}][section_pad_right]", get_property($section['fields'], 'section_pad_right'), ['class' => 'section-right-padding-field']) !!}
                                    @endif


                                    <?php $counter++; ?>
                                </li>
                            @endforeach
                        @endif
                   </ol>

                    {{-- Template switcher --}}
                   <?php $templates = config('sections.sections'); ?>
                   <div class="ibox template-picker-wrapper hidden">
                        <div class="ibox-title">
                            <h5>Choose the template</h5>
                        </div>
                        <div class="template-picker ibox-content clearfix">
                            <div class="thumbnails clearfix">
                                @foreach($templates as $template)
                                    {!! $template['picker'] !!}
                                @endforeach
                            </div>
                            <div class="cancel">
                                <a href="#" class="btn btn-danger btn-outline btn-xs cancel-picker"><i class="fa fa-close"></i> Cancel</a>
                            </div>
                        </div>
                   </div>


               </div>
           </div>

@section('js')

    @parent

    <script type="text/javascript">
        CMSAdmin.sectionOptions = {!! json_encode($options) !!};
    </script>
@endsection
