<?php GlobalClass::add('body', 'static-banner'); ?>

<?php
    $style = 'background-image: url('.$banner->attachment->file->url().');';
    $class = empty($banner->meta_class) ? null : $banner->meta_class;
?>
<div class="banner {{ $class }}" style="{{ $style }}">

    <div class="container details">

        @include('common.breadcrumbs', ['hide_home' => true])

        @if (empty($options['summary']))
            <h1 class="onlyTag">{{ $options['title'] }}</h1>
        @else
            <h1>{{ $options['title'] }}</h1>
            <p>{!! Filter::filter($options['summary'], ['purifier' => 'titles']) !!}</p>
        @endif


        <!-- @if ( ! empty($options['title']))
            <h1>{{ $options['title'] }}</h1>

            @if ( ! empty($options['summary']))
                <p>{!! Filter::filter($options['summary'], ['purifier' => 'titles']) !!}</p>
            @endif

        @endif -->
    </div>

</div>
