@extends('admin.layouts.master', ['title' => 'Site settings'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.configurations.site.settings.save'), 'class' => 'form-horizontal', 'files' => true)) !!}

                {{-- Site Title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Site title <small>Shown as the browers title.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'site__title'}(['type' => 'text', 'default' => Variable::get('site.title', ''), 'class' => 'input-lg', 'placeholder' => 'Site Title...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Site copyright<small> Shown in the footer fine print.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'site__copyright'}(['type' => 'textarea', 'default' => Variable::get('site.copyright', ''), 'class' => 'wysiwyg basic', 'rows' => 4, 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Email disclaimer<small> Shown in the footer fine print of emails.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'site__email__footer'}(['type' => 'textarea', 'default' => Variable::get('site.email.footer', ''), 'class' => 'wysiwyg basic', 'rows' => 4, 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Email autoresponder subject <small>Subject of the emails sent.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'site__email__autoresponder__subject'}(['type' => 'text', 'default' => Variable::get('site.email.autoresponder.subject', ''), 'class' => 'input-lg', 'placeholder' => 'Site Title...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Email autoresponder<small> The autoresponder text.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'site__email__autoresponder'}(['type' => 'textarea', 'default' => Variable::get('site.email.autoresponder', ''), 'class' => 'wysiwyg basic', 'rows' => 4, 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>News &amp; Events <small>Links for the News &amp; Events page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'site__news__page'}(['type' => 'text', 'default' => Variable::get('site.news.page', ''), 'class' => 'input-lg', 'placeholder' => 'News url...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save site settings', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
