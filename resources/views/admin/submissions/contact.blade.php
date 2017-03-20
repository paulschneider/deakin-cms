<?php $title = ucfirst($type); ?>
@extends('admin.layouts.master', ['title' => "{$title} submission #{$submission->id}"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($submission, array('method' => 'PATCH', 'route' => array('admin.submissions.type.update', $type, $submission->id), 'class' => 'form-horizontal')) !!}


                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Submission <small>The submission from the user.</small></h5>
                    </div>
                    <div class="ibox-content">

                        {!! Form::hidden('type', $type) !!}

                        @foreach ($fields as $field)

                            <?php
                                $name = str_replace('_', ' ', $field);
                                $name = ucfirst($name);
                            ?>
                            <p>
                                <strong>{{ $name }}:</strong>
                                {{ $submission->getField($field) }}
                            </p>

                        @endforeach
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Status <small>The submission from the user.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::status(['type' => 'select', 'class' => 'input-lg', 'label-class' => 'sr-only', 'label' => 'Status', 'options' => config('forms.status')]) !!}
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save term', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
