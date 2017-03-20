<a href="{{ route('frontend.articles.slug', $article->slug) }}">
    <article class="news-item-large">

            <?php
                $term = $article->revision->article_types->first();

                $class = '';
                if ($article->revision->thumbnail_id) {
                    $class = "background: url(".$article->revision->thumbnail->file->url('large').") center no-repeat;background-size: cover;";
                }
            ?>

            {{-- <div class="meta">
                <strong>{{ $term->name }}</strong>
                <span class="date">{{ $article->created_at->format('j F Y') }}</span>
            </div> --}}

            <div class="row">
                <div class="thumbnail col-sm-6" style="{{ $class }}"></div>
                <div class="content  col-sm-6">
                    <p class="type">{{ $term->name }}</p>
                    <p class="title">{{ $article->revision->title }}</p>
                    <i class="fa fa-long-arrow-right"></i>
                </div>
            </div>

        {{-- <div class="summary">
            {!! Filter::filter($article->revision->summary, ['purifier' => 'html_basic']) !!}
        </div> --}}
    </article>
</a>
