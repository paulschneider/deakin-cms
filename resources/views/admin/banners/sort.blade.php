@extends('admin.layouts.master', ['title' => 'Sort a Banner'])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Sorting {{ $banner->title }}.</h5>
                </div>
                <div class="ibox-content">
                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            @include('admin.common.sort.tree', ['tree' => $tree, 'type' => 'image', 'label' => 'title'])
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('actions')
    <a href="{{ route('admin.banners.index') }}" class="btn btn-default">&lsaquo; Banners</a>
    <a href="#" class="btn btn-primary" id="submit-sort"><i class="fa fa-save"></i>  Save Changes</a>
@endsection



@section('js')
    @include('admin.common.sort.script', ['route' => route('admin.banners.sort', $banner->id)])
@endsection