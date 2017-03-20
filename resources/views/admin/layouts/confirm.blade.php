@extends('admin.layouts.master')


@section('content')

{!! Form::open($action) !!}
{!! Form::hidden('destination', $return_url) !!}
<div class="panel panel-danger">
    <div class="panel-heading">
         {{ $confirm_text or 'Delete' }}
    </div>
    <div class="panel-body">
        <p>{{ $question or "Are you sure?" }}</p>
    </div>
    <div class="panel-footer">
        {!! Form::submit($confirm_text, array('class' => 'btn btn-danger')) !!}
        {!! link_to(URL::previous(), $cancel_text, ['class' => 'btn btn-default']) !!}
    </div>
</div>
{!! Form::close() !!}

@endsection