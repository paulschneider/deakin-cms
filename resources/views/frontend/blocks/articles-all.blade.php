<?php
    $btn_one = Attachment::getAttachment(config("attachments.defaults.THIRD_PARTY_TESTIMONY"));
    $btn_two = Attachment::getAttachment(config("attachments.defaults.CREDENTIAL_SUBMISSION_PLANNER"));
?>

<section class="articles articles-all">
    <div class="container">
        <div class="section">
            @if ( ! empty($block->col_one_title))
                <h3 class="text-center">{{ $block->col_one_title }}</h3>
            @endif

            @if ($articles->count())
                @if ($articles->currentPage() === 1 && $featured)
                    <div class="feature-item">
                        @include('frontend.articles.teaser-horizontal', ['article' => $featured])
                    </div>
                @endif

                <div class="items row">
                    @foreach($articles as $article)
                        @include('frontend.articles.teaser')
                    @endforeach
                </div>
            @else
                <p class="text-center"><em>No articles found.</em></p>
            @endif

            @if (hasMorePages($articles))
                <p class="read-more">More News</p>
            @endif

            <div class="row">
                @include('common.pagination', ['paginator' => $articles])
            </div>

            <div class="row news-btns">
                <div class="col-sm-4">
                    <a href="{{ $btn_one ? $btn_one->file->url() : '#' }}" class="btn btn-news">
                        <img src="{{ asset('assets/images/news-btn-01.svg') }}" class="btn-img">
                        Third party testimony form
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ $btn_two ? $btn_two->file->url() : '#' }}" class="btn btn-news">
                        <img src="{{ asset('assets/images/news-btn-02.svg') }}" class="btn-img">
                        Submission planner
                    </a>
                </div>
                <div class="col-sm-4">
                    <a href="{{ url('newsletter-signup') }}" class="newsletter-signup btn btn-news last-btn">
                        <img src="{{ asset('assets/images/news-btn-03.svg') }}" class="btn-img">
                        Sign up to the newsletter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
