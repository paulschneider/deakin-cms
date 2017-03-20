@extends('admin.layouts.master', ['title' => "Create a Image in {$banner->title}"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.banners.images.store', $banner->id), 'class' => 'form-horizontal')) !!}

                {!! Form::hidden('banner_id', $banner->id) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Image Details</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::title(['type' => 'text']) !!}

                        <div class="form-group">
                            <label class="control-label col-sm-2">File:</label>
                            <div class="col-sm-10">
                                @include('admin.attachments.dropzone', [
                                    'id' => uniqid(),
                                    'into' => '#banner_image',
                                    'files' => '.jpg,.png,.gif',
                                    'path' => 'attachments.path.banners',
                                ])

                                {!! Form::hidden('attachment_id', null, ['id' => 'banner_image']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Registered:</label>
                            <div class="col-sm-10">
                                {!! Form::select("method", $methods, null, ['class' => 'form-control input-sm', 'style' => 'width: 100%;']) !!}
                            </div>
                        </div>

                    </div>
                </div>



                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Image Metadata</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::meta_target(['type' => 'select', 'label' => 'Target:', 'options' => config('links.targets')]) !!}
                        {!! FormField::meta_rel(['type' => 'text', 'label' => 'Rel attribute:']) !!}
                        {!! FormField::meta_class(['type' => 'text', 'label' => 'Class attribute:']) !!}
                        {!! FormField::meta_id(['type' => 'text', 'label' => 'ID attribute:']) !!}
                    </div>
                </div>


                @include('admin.common.form_online')


                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save image', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
