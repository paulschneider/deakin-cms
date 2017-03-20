<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Edit a icon'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.icons.store'), 'class' => 'form-horizontal', 'files' => true)) !!}

                {!! FormField::title(['type' => 'text', 'class' => 'input-lg', 'placeholder' => 'Title...', 'label-class' => 'sr-only']) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Content <small>This is the primary content.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::svg(['type' => 'textarea', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Icon Image <small>This will be shown instead of the svg if the browser doesn't support it.</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <?php $image = Request::old('attachment_id'); ?>
                            @if ($image)
                                <div class="col-sm-2">
                                    @if ( ! empty($icon->attachment_id))
                                        <img src="{{ $icon->image->file->url() }}">
                                    @endif
                                </div>
                                <div class="col-sm-10">
                            @else
                                <div class="class-col-sm-12">
                            @endif
                                @include('admin.attachments.dropzone', [
                                    'id' => uniqid(),
                                    'into' => '#icon-image',
                                    'files' => '.jpg,.png,.gif',
                                    'path' => 'attachments.path.icons',
                                ])
                                <input type="hidden" id="icon-image" name="attachment_id">
                            </div>
                        </div>
                    </div>
                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::button('Save and publish', array('class' => 'btn btn-primary cancels-navigation-message', 'name' => 'status', 'value' => 'current', 'type' => 'submit')) !!}
                    </div>
                </div>


            {!! Form::close() !!}

        </div>
    </div>
@endsection
