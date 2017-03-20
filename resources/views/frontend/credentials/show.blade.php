<?php GlobalClass::add('body', ['has-sections', 'collapsibles', 'section-credentials']);?>

@extends('frontend.layouts.page', ['title' => $credential->revision->title])

@section('admin-links')
    @if (Entrust::can('admin.pages.post'))
        <a href="{{ route('admin.credentials.edit', $credential_id) }}" class="edit-link btn btn-xs btn-primary">
            <i class="fa fa-cog"></i> Edit
        </a>
    @endif
@endsection


@section('content')

    <article class="sections">

        <section class="credential-intro-section">
            <div class="container">
                <div class="section">
                    <div class="row section-intro {{ $credential->revision->entity_color or null }}">
                        <div class="col-sm-3 side">
                            <div class="inner">
                                @if ($credential->revision->logo_id)
                                    <div class="image-field">
                                        <img src="{{ $credential->revision->logo->file->url('medium') }}" class="img-responsive">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h1>{{ $credential->revision->title }}</h1>

                            @if ($credential->revision->summary)
                                <div class="summary">
                                    {!! $credential->revision->summary !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if (count($sections))

            <div class="section-multiple-fields">
                @foreach ($sections as $section)
                    {!! $section !!}
                @endforeach
            </div>

        @endif

        <section class="credential-footer-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <p><a href="/browse-credentials">View all Credentials and Degrees</a></p>
                    </div>
                </div>
            </div>
        </section>

    </article>

    @include('frontend.common.related-links')

@endsection
