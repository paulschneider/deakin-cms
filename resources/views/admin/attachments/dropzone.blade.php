<?php
    $dropzoneOptions = [
        'id'       => $id,
        'url'      => route('admin.attachments.dropzone'),
        'multiple' => ! empty($multiple) ? true : false,
    ];

    if ( ! empty($into)) $dropzoneOptions['into'] = $into;
    if (empty($multiple)) $dropzoneOptions['maxFiles'] = 1;
    if ( ! empty($path)) $dropzoneOptions['path'] = $path;
    if ( ! empty($files)) $dropzoneOptions['acceptedFiles'] = $files;

    if ( ! empty($old)) {
        $image = Attachment::getAttachment($old);
    }

?>

@if ( ! empty($fullscreen))
    <div class="dropzone-fullscreen embedded-dropzone" data-dropzone-options='{!! json_encode($dropzoneOptions) !!}'>
@else
    <div id="dropzone_{{ $id }}" class="embedded-dropzone col-xs-12" data-dropzone-options='{!! json_encode($dropzoneOptions) !!}'>
@endif
        <div class="fallback">
            {!! FormField::file(['type' => 'file', 'multiple' => ( ! empty($multiple)), 'name' => 'dropzone_' . $id]) !!}
        </div>
    </div>

@if (!empty($old) && !empty($image))
    <div class="preview" style="padding: 10px 0 0 0; clear: both;">

        @if (preg_match('/^image/', $image->file_content_type))
            <img src="{{ $image->file->url('micro') }}">
        @else
            <a href="{{ $image->file->url() }}" target="_blank">{{ $image->title or 'Preview' }}</a>
        @endif
        &nbsp;
        <a href="#" onclick="$('{{ $into }}').val(''); $(this).parents('.preview:first').remove(); return false" class="btn btn-xs btn-danger">Remove</a>
    </div>
@endif


@section('dropzone_template')

<div class="dz-preview dz-file-preview sr-only">
    <div class="dz-details row">
        <div class="col-sm-1 col-xs-3"><img data-dz-thumbnail class="img-responsive"/></div>

        <div class="col-sm-11 col-xs-9">
            <div class="col-sm-6"><div class="filename" data-dz-name></div></div>
            <div class="col-sm-4">
                <div class="progress-wrap">
                    <div class="dz-progress progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                      <div class="dz-upload progress-bar progress-bar-info" data-dz-uploadprogress></div>
                    </div>
                    <span class="data-dz-errormessage" data-dz-errormessage><i class="fa fa-remove"></i></span>
                </div>
            </div>
            <div class="col-sm-2"><div class="filesize" data-dz-size></div></div>
        </div>
    </div>
</div>

@stop
