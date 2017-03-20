<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => "Create a term in {$vocabulary->name}"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.vocabularies.terms.store', $vocabulary->id), 'class' => 'form-horizontal')) !!}

                {!! Form::hidden('vocabulary_id', $vocabulary->id) !!}

                <div class="col-sm-12">
                    {!! FormField::name(['type' => 'text']) !!}
                </div>

                <div class="col-sm-12">
                    {!! FormField::stub(['type' => 'text', 'label' => 'Machine Name:']) !!}
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Create term', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
