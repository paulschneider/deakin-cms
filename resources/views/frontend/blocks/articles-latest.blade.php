<section class="articles articles-latest">
    <div class="container">
        @if ( ! empty($block->col_one_title))
            <h3 class="text-center">{{ $block->col_one_title }}</h3>
        @endif

        @if ($articles->count())
            <div class="items row">
                @foreach($articles as $article)
                    @include('frontend.articles.teaser')
                @endforeach
            </div>

            <p class="text-center link"><a href="/news">More news <i class="fa fa-arrow-right"></i></a></p>
        @else
            <p class="text-center"><em>No articles found.</em></p>
        @endif
    </div>
</section>