@if (count($blocks['widget']) > 1)

    {!! GlobalClass::add('body', ['multiple-widget']); !!}

    <div class="ibox float-e-margins">

        <div class="ibox-title">
            <h5>Widgets <small>Any widget blocks that should be displayed in the sidebar</small></h5>
        </div>

        <?php
            // Get the old values or the values from the entity passed in
            $widgets = Request::old('widgets');
            if (empty($widgets)) {
                $widgets = [];
                if ($entity && $entity->widgets->count()) $widgets = $entity->widgets;
            }
        ?>

        <div class="ibox-content clearfix">
                <div class="dd multiple-fields">
                    <ol class="dd-list col-md-12 widget-multiple-fields">
                        <?php $counter = 0; ?>
                        @if ( ! empty($widgets))
                            @foreach ($widgets as $widget)
                                {{-- Make one for each field --}}
                                <li class="dd-item multiple-field clearfix">
                                    <div class="dd-handle drag"><i class="fa fa-arrows"></i></div>
                                    {!! Form::label("widgets[{$counter}]widget", 'Widget', ['class' => 'col-sm-2 control-label']) !!}
                                    <div class="col-sm-10">
                                        {!! Form::select("widgets[{$counter}]widget", $options, $widget->id, ['class' => 'form-control input-lg', 'style' => 'width: 100%;']) !!}
                                    </div>
                                </li>
                                <?php $counter++ ?>
                            @endforeach
                        @else
                            {{-- If it's empty, still output one so that we can use it as the template --}}
                            <li class="dd-item multiple-field">
                                <div class="dd-handle drag"><i class="fa fa-arrows"></i></div>
                                {!! Form::label('widgets[0]widget', 'Widget', ['class' => 'col-sm-2 control-label']) !!}
                                <div class="col-sm-10">
                                    {!! Form::select('widgets[0]widget', $options, null, ['class' => 'form-control input-lg', 'style' => 'width: 100%;']) !!}
                                </div>
                            </li>
                        @endif
                    </ol>
                    <div class="no-more">
                        <small>There are no more widgets to add.</small>
                    </div>
            </div>
        </div>
    </div>
@endif