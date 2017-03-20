<?php GlobalClass::add('body', ['social', 'collapsibles']); ?>

@extends('frontend.layouts.page', ['title' => $article->revision->title])

@section('admin-links')
    @if (Entrust::can('admin.pages.post'))
        <a href="{{ route('admin.articles.edit', $article_id) }}" class="edit-link btn btn-xs btn-primary">
            <i class="fa fa-cog"></i> Edit
        </a>
    @endif
@endsection

@section('content')

    <article class="sections">
        {{-- Fake a section to match page styles --}}
        <div class="multiple-section one-column-wysiwyg">

            <div class="container">

                {{--
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        {!! Filter::filter($article->revision->summary, ['filter' =>['attachments', 'icons'], 'purifier' => 'full_html']) !!}
                    </div>
                </div>
                --}}

                @if ($article->revision->image_id)
                    <img src="{{ $article->revision->image->file->url('full') }}" class="img-responsive">
                @endif

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        {!! Filter::filter($article->revision->body, ['filter' => ['attachments', 'icons', 'youtube', 'figures'], 'purifier' => 'full_html']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        @if($article->author)
                            {{ $article->author }},
                        @endif
                        {{ $article->created_at->format("d M Y") }}
                    </div>
                </div>

                <div class="social-wrapper text-center">
                    <div class="share-header">Share this article</div>
                    <div class="social"></div>
                </div>
            </div>

            <div class="more-news-container grey">
                <a href="{{ Variable::get('site.news.page', '/') }}" class="more-news-btn">{{ Variable::get('site.loadmore.news', 'More news') }}<i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
    </article>

    @include('frontend.common.related-links')

@endsection
