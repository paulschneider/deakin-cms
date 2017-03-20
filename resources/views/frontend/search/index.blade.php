@extends('frontend.layouts.page', ['title' => 'Search'])

<?php
    GlobalClass::add('body', ['section-search', 'has-sections']);
?>

@section('content')
    <div class="sections">
        <div class="multiple-section one-column-wysiwyg sections-pad-bottom sections-pad-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text-center results-total">
                        <p>We've found <strong>{{ $results->total() }} results</strong> for <strong>{{ $query ? $query : 'Empty Search' }}</strong></p>
                    </div>
                </div>

                @foreach ($results as $result)
                    <?php $revision = data_get($result, '_source.revision'); ?>

                    <article class="search-article">
                        <div class="teaser row">                        
                            <div class="col-sm-3 hidden-xs">
                                @if ($img = data_get($result, '_source.image'))
                                    <img src="{{ $img }}" class="img-responsive sm-image">
                                @endif
                            </div>                        

                            <div class="col-xs-12 col-sm-9">
                                <header>                                    
                                    <h3>
                                        <a href="{{ data_get($result, '_source.url') }}">
                                            {{ data_get($result, '_source.title') }}
                                        </a>
                                    </h3>
                                </header>

                                <p>
                                    @if ($img = data_get($result, '_source.image'))
                                        <img src="{{ $img }}" class="img-responsive xs-image visible-xs pull-right">
                                    @endif

                                    @if ($highlight = data_get($result, 'highlight.body.0'))
                                        {!! Filter::filter($highlight, ['length' => 600, 'purifier' => 'basic_html', 'filter' => ['trim']]) !!}
                                    @else
                                        {!! Filter::filter(data_get($result, '_source.body'), ['length' => 600, 'purifier' => 'basic_html', 'filter' => ['trim']]) !!}
                                    @endif

                                </p>

                                <strong>
                                    @if(data_get($revision, 'author'))
                                        {{ $revision['author'] }},
                                    @endif
                                    {{ date("d M Y", data_get($result, '_source.created_at')) }}                                    
                                </strong>
                                <p>
                                    <small>
                                        <em>{{ data_get($result, '_source.url') }}</em>
                                    </small>
                                </p>                                
                            </div>                        
                        </div>                 
                    </article>
                @endforeach

                @include('common.pagination', ['paginator' => $results])

            </div>
        </div>
    </div>
@endsection
