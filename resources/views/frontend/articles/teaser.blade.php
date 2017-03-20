<a href="{{ route('frontend.articles.slug', $article->slug) }}">
    <article class="news-item col-sm-4">
        <header>
            <?php
                $term = $article->revision->article_types->first();
                $category = $article->revision->article_types;

                $class = '';

                if ($article->revision->thumbnail_id) {
                    $class = "background: url(".$article->revision->thumbnail->file->url('medium').") center no-repeat;background-size: cover;";
                }
            ?>

            {{-- <div class="meta">
                <strong>{{ $term->name }}</strong>
                <span class="date">{{ $article->created_at->format('j F Y') }}</span>
            </div> --}}

            <div class="thumbnail" style="{{ $class }}"></div>
            <div class="content">
                {{-- only show the content type if one of the filter options has been selected --}}
                @if(request()->has("filter"))
                    <p class="type">{{ $term->name }}</p>
                @endif

                @if($category->count() > 0)
                    <p class="category">{{ $category->first()->name }}</p>
                @endif
                <p class="title">{{ $article->revision->title }}</p>
                <i class="fa fa-long-arrow-right"></i>
            </div>
        </header>
        {{-- <div class="summary">
            {!! Filter::filter($article->revision->summary, ['purifier' => 'html_basic']) !!}
        </div> --}}
    </article>
</a>
