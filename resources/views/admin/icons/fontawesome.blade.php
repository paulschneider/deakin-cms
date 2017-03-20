<?php GlobalClass::add('body', 'icon-picker'); ?>
@extends('admin.layouts.iframe', ['title' => 'FontAwesome'])

@section('content')

    <div class="row">
        <div class="col-sm-12 icon-picker fontawesome">
            @foreach($icons as $icon)
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
