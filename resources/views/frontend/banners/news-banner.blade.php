<?php GlobalClass::add('body', ['static-banner', 'searchable-banner']); ?>

<div class="banner news-banner inspired-passionate">

    <div class="details">

        @include('common.breadcrumbs', ['hide_home' => true])
        
        <h1>{{ $options['title'] }}</h1>
        
        <div class="bottom-bar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                        {!! Form::open(['url' => 'news', 'method' => 'GET']) !!}

                        {!! FormField::q([
                            'default' => request()->get('q'),
                            'type' => 'text',
                            'class' => 'input-lg text',
                            'placeholder' => 'Search news & resources',
                            'label' => 'Search news & resources',
                            'input-wrap-class' => 'input-wrap',
                            'label-class' => 'sr-only'
                        ]) !!}

                        <div class="mobile-hidden">
                            <a href="#" class="showhide"><i class="fa fa-chevron-down"></i> Show all categories</a>
                            <div class="expanse">
                                @foreach (Tax::terms('article_types') as $tax)
                                    <input type="checkbox" id="filter-{{$tax->stub}}" name="filter[]" {{ in_array($tax->stub, request()->get('filter', [])) ? 'checked="checked"' : null }} value="{{ $tax->stub }}">
                                    <label class="check" for="filter-{{$tax->stub}}">{{ $tax->name }}</label>
                                @endforeach
                            </div>
                        </div>
                        
                        {!! Form::button('Search', ['class' => 'btn btn-primary', 'name' => 'status', 'value' => 'current', 'type' => 'submit']) !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
