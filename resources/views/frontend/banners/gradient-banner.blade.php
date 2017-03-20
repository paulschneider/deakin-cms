<?php GlobalClass::add('body', 'gradient-banner'); ?>

<?php
    $class = [];
    $class[] = empty($banner->meta_class) ? null : $banner->meta_class;
    $class[] = empty($options['class']) ? null : $options['class'];
?>

<div class="banner {{ implode(' ', $class) }}" >

    <div class="container details">

        @include('common.breadcrumbs', ['hide_home' => true])

        @if (empty($options['summary']))
            <h1 class="onlyTag">{{ $options['title'] }}</h1>
        @else
            <h1>{{ $options['title'] }}</h1>
            <p>{!! Filter::filter($options['summary'], ['purifier' => 'titles']) !!}</p>
        @endif


        @if($banner->attachment)
            <div class="gradient-banner-image">
                <img src="{{ $banner->attachment->file->url() }}">
            </div>
        @endif
    </div>

</div>
