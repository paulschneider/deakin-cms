                            <li class="dd-item multiple-field multiple-section clearfix {{ $section_info[$section['template']]['class'] }}" data-id="{{ $counter }}">
                                <div class="dd-handle drag">
                                    <i class="fa fa-arrows"></i>
                                    <h5>{{ $section_info[$section['template']]['name'] }}</h5>
                                </div>
                                <div class="section">
                                    @include($section_info[$section['template']]['admin_template'])
                                </div>
                                {{-- Section Color Switcher --}}
                                @if ( ! empty($options[$section['template']]['section_colors']))
                                    @include('admin.sections.color-selector', ['field' => 'section_colors', 'option' => 'section_colors'])
                                @endif
                                @if ( isset($section['info']['fields']['section_pad_top']))
                                    {!! Form::hidden("sections[{$counter}][section_pad_top]", $section['fields']->section_pad_top, ['class' => 'section-top-padding-field']) !!}
                                @endif
                                @if ( isset($section['info']['fields']['section_pad_bottom']))
                                    {!! Form::hidden("sections[{$counter}][section_pad_bottom]", $section['fields']->section_pad_bottom, ['class' => 'section-bottom-padding-field']) !!}
                                @endif
                                @if ( isset($section['info']['fields']['section_pad_left']))
                                    {!! Form::hidden("sections[{$counter}][section_pad_left]", $section['fields']->section_pad_left, ['class' => 'section-left-padding-field']) !!}
                                @endif
                                @if ( isset($section['info']['fields']['section_pad_right']))
                                    {!! Form::hidden("sections[{$counter}][section_pad_right]", $section['fields']->section_pad_right, ['class' => 'section-right-padding-field']) !!}
                                @endif
                            </li>
