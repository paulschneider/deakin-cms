<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Edit an Article'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($article, array('method' => 'PATCH', 'route' => array('admin.articles.update', $article->id), 'class' => 'form-horizontal', 'files' => true)) !!}

                {!! Form::hidden('revision', $article->revision_id) !!}

                {!! FormField::title(['type' => 'text', 'class' => 'input-lg', 'placeholder' => 'Page Title...', 'label-class' => 'sr-only']) !!}

                 <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Summary <small>This is shorter version of the content.</small></h5>
                    </div>
                    <div class="ibox-content symmary">
                        {!! FormField::summary(['type' => 'textarea', 'label-class' => 'sr-only', 'class' => 'wysiwyg micro inline-editor']) !!}
                    </div>
                </div>                

                <div class="sections">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Content <small>This is the primary content.</small></h5>
                        </div>
                        <div class="ibox-content">
                            <div class="multiple-section">
                                {!! FormField::body(['type' => 'textarea', 'label-class' => 'sr-only', 'class' => 'wysiwyg inline-editor']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Author <small>Who was the author of this article?</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::author(['type' => 'text', 'class' => 'input-lg', 'placeholder' => 'Author name...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>
                
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Featured Article <small>Should this article be displayed as the featured item.</small></h5>
                    </div>
                    <div class="ibox-content featured">
                        {!! Form::checkbox('is_featured', 1, $article->is_featured ? true : null) !!}
                        Show as featured article
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Images</h5>
                    </div>
                    <div class="ibox-content summary">
                        <div class="form-group">
                            {!! Form::label('thumbnail_id', 'Thumbnail', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                @include('admin.attachments.dropzone', [
                                    'id' => uniqid(),
                                    'into' => '#thumbnail_id',
                                    'files' => '.jpg,.png,.gif',
                                    'path' => 'attachments.path.articles',
                                    'old' => $article->revision->thumbnail_id
                                ])
                                {!! Form::hidden('thumbnail_id', null, ['id' => 'thumbnail_id']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('image_id', 'Hero image', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                @include('admin.attachments.dropzone', [
                                    'id' => uniqid(),
                                    'into' => '#image_id',
                                    'files' => '.jpg,.png,.gif',
                                    'path' => 'attachments.path.articles',
                                    'old' => $article->revision->image_id
                                ])
                                {!! Form::hidden('image_id', null, ['id' => 'image_id']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Categories</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Type</label>
                            <div class="col-sm-10">
                                {!! Form::select('terms[article_type]', Tax::vocabularyOptions('article_types'), $article->revision->article_types->pluck('id')->all(), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                @if (config('articles.events'))
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Event Date <small>If this is an event.</small></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="sr-only">Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('event_at_date', (empty($article->revision->event_at) ? null : $article->revision->event_at->format('d/m/Y')), ['class' => 'form-control', 'placeholder' => 'Date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="sr-only">Time</label>
                                <div class="input-group time bootstrap-timepicker">
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    {!! Form::text('event_at_time', (empty($article->revision->event_at) ? null : $article->revision->event_at->format('h:i a')), ['class' => 'form-control', 'placeholder' => 'Time']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Published Date</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="sr-only">Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    {!! Form::text('created_at_date', (empty($article->created_at) ? null : $article->created_at->format('d/m/Y')), ['class' => 'form-control', 'placeholder' => 'Date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="sr-only">Time</label>
                                <div class="input-group time bootstrap-timepicker">
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    {!! Form::text('created_at_time', (empty($article->created_at) ? null : $article->created_at->format('h:i a')), ['class' => 'form-control', 'placeholder' => 'Time']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @include('admin.common.banners', ['entity' => $article])

                @include('admin.common.meta')

                @include('admin.common.social', ['entity' => $article])

                @include('admin.common.related_links', ['entity' => $article])

                @include('admin.common.form_online')

                @include('admin.common.schedule', ['entity' => $article])

                <div class="col-sm-12">
                    <div class="form-group">

                        {!! Form::button('Save and publish', array('class' => 'btn btn-primary cancels-navigation-message', 'name' => 'status', 'value' => 'current', 'type' => 'submit')) !!}

                        @if ($article->revision->status == 'current')
                            {!! Form::button('Save as draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @elseif ($article->revision->status == 'archive')
                            {!! Form::button('Update this revision', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'archive', 'type' => 'submit')) !!}
                            {!! Form::button('Save as draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @elseif ($article->revision->status == 'draft')
                            {!! Form::button('Update this draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @endif

                        {{-- I don't think this is needed --}}
                        {{-- {!! Form::button('Preview', array('class' => 'btn btn-success btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'preview', 'type' => 'submit')) !!} --}}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('actions')
    @if ($article->revision->status != 'current')
        <a href="{{ route('admin.articles.show', [$article->id, 'revision' => $article->revision->id]) }}" class="btn btn-primary btn-small btn-outline" target="_blank"><i class="fa fa-eye"></i> Preview Revision</a>
    @endif
    <a href="{{ route('admin.articles.revisions.index', $article->id) }}" class="btn btn-primary btn-small btn-outline"><i class="fa fa-edit"></i> Revisions</a>
    <a href="{{ route('frontend.articles.slug', $article->slug) }}" class="btn btn-success btn-small btn-outline"><i class="fa fa-globe"></i> View Published</a>
@endsection
