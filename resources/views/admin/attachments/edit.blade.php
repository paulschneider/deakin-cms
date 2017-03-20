@extends('admin.layouts.master', ['title' => 'Edit a File'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($attachment, array('method' => 'PATCH', 'route' => array('admin.attachments.update', $attachment->id), 'class' => 'form-horizontal', 'files' => true)) !!}

                <div class="col-sm-12">
                    {!! FormField::title(['type' => 'text']) !!}
                    {!! FormField::alt(['type' => 'text', 'field-description' => 'For blind people and semantic assistance']) !!}
                    {!! FormField::slug(['type' => 'text', 'label' => 'Friendly URL:', 'field-description' => 'Example: <em>custom/path/here.pdf</em>']) !!}
                    {!! FormField::term_id(['type' => 'select', 'label' => 'Folder:', 'options' => Tax::vocabularyOptions(config('attachments.vocab'))]) !!}
                </div>

                <div class="col-sm-12">
                    {!! FormField::file(['type' => 'file']) !!}
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save file', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
