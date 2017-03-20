@extends('admin.layouts.iframe')

@section('content')

    @if (isset($attachment))
        <div class="row">
            <div class="col-sm-12">
                <strong>{{ $attachment->title }}</strong>
            </div>
            <div class="col-sm-12">
                @if (stristr($attachment->file->contentType(), 'image/'))
                    <img src="{{ $attachment->file->url('medium') }}" class="img-responsive">
                @else
                    Not an image file.
                @endif
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-12">
                <p>Please select a file.</p>
            </div>
        </div>
    @endif
@endsection
