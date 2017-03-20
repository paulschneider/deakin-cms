<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => "Edit {$term->name} in {$vocabulary->name}"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($term, array('method' => 'PATCH', 'route' => array('admin.vocabularies.terms.update', $vocabulary->id, $term->id), 'class' => 'form-horizontal')) !!}

                {!! Form::hidden('vocabulary_id', $term->vocabulary_id) !!}

                <div class="col-sm-12">
                    {!! FormField::name(['type' => 'text']) !!}
                </div>

                <div class="col-sm-12">
                    {!! FormField::stub(['type' => 'text', 'label' => 'Machine Name:']) !!}
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save term', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
