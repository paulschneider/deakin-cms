<?php GlobalClass::add('body', 'icon-picker'); ?>
@extends('admin.layouts.iframe', ['title' => 'Icons'])

@section('content')

    <div class="row">
        <div class="col-sm-12">

        <h2>Custom Icons</h2>

        </div>
        <div class="col-sm-12 icon-picker">

            @foreach($icons as $icon)
                <label class="icon">
                    <div class="icon-selector">
                        <div class="background"></div>
                        <a href="#">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    </div>
                    <span class="svg">
                        {!! $icon->svg !!}
                        <input type="checkbox" value="{{ json_encode($icon) }}" />
                    </span>
                </label>
            @endforeach

        </div>

        <div class="col-sm-12">

        <h2>FontAwesome Icons</h2>

        </div>
        <div class="col-sm-12 icon-picker">

            @foreach(config('icons.fontawesome') as $icon)
                <label class="icon">
                    <div class="icon-selector">
                        <div class="background"></div>
                        <a href="#">
                            <i class="fa fa-check-circle"></i>
                        </a>
                    </div>
                    <span class="svg">
                        <i class="fa {{ $icon }}"></i>
                        <input type="checkbox" value="{{ $icon }}" />
                    </span>
                </label>
            @endforeach
        </div>
    </div>

@endsection
