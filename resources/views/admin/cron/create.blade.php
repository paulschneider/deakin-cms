@extends('admin.layouts.master', ['title' => 'Create a Job'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.cron.store'), 'class' => 'form-horizontal')) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Command</h5>
                    </div>
                    <div class="ibox-content">

                        {!! FormField::command(['type' => 'text', 'field-description' => '
                            This command MUST be accessible from php artisan. This ensures scheduled tasks are codebase controlled.
                            <a href="#" class="btn btn-xs btn-outline btn-default" data-toggle="modal" data-target="#all-commands">Show commands</a>
                        ']) !!}


                        <div class="form-group">
                            <label class="col-sm-2 control-label">Schedule</label>
                            <div class="col-sm-10">
                                <div class="row">

                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">Min</span>
                                            {!! Form::text('min', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">Hr</span>
                                            {!! Form::text('hour', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">Day Mth</span>
                                            {!! Form::text('day_month', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">Mth</span>
                                            {!! Form::text('month', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">Day Wk</span>
                                            {!! Form::text('day_week', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                </div>

                                <span class="help-block m-b-one">
                                    See <a href="http://en.wikipedia.org/wiki/Cron" target="_blank">http://en.wikipedia.org/wiki/Cron</a> for more information.
                                </span>
                            </div>

                        </div>


                    </div>
                </div>


                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Special conditions</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Run once</label>
                            <div class="col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('once', 1, 1) !!}
                                        Only run once.
                                    </label>
                                </div>
                            </div>
                        </div>

                        {!! FormField::year(['type' => 'select','label' => 'Year to run once','options' => $years, 'field-description' => 'If once is selected, you need to select a year.']) !!}

                    </div>
                </div>

                @include('admin.common.form_online')

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save job', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>

    @include('admin.cron.modal')

@endsection
