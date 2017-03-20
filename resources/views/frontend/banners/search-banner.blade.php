<?php GlobalClass::add('body', ['static-banner', 'searchable-banner']); ?>

<div class="banner search-banner legacy-progressive">

    <div class="details">

        @include('common.breadcrumbs', ['hide_home' => true])

        {!! Form::open(['route' => 'search', 'class' => 'form-horizontal', 'method' => 'GET']) !!}

            <div class="container">

                <h1>{{ $title }}</h1>

                {!! FormField::q([
                    'default' => $query,
                    'type' => 'text',
                    'class' => 'input-lg text',
                    'placeholder' => 'Search site',
                    'label' => 'Search site',
                    'input-wrap-class' => 'col-sm-4 col-sm-offset-4 input-wrap',
                    'label-class' => 'sr-only'
                ]) !!}

            </div>

        <div class="bottom-bar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">

                    <div class="mobile-hidden">

                        <a href="#" class="showhide"><i class="fa fa-chevron-down"></i> Show all categories</a>

                        <div class="expanse">
                        @foreach (config('search.globals') as $filter)
                            <input type="checkbox" id="filter-{{$filter['key']}}" name="filter[]" {{ in_array($filter['key'], $active) ? 'checked="checked"' : null }} value="{{ $filter['key'] }}">
                            <label class="check" for="filter-{{$filter['key']}}">{{ $filter['label'] }}</label>
                        @endforeach
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>

</div>
