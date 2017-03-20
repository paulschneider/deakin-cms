@extends('admin.layouts.master', ['title' => 'Sort a Menu'])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Sorting {{ $menu->title }}.</h5>
                </div>
                <div class="ibox-content">
                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            @include('admin.common.sort.tree', ['tree' => $tree, 'type' => 'link', 'label' => 'title'])
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('actions')
    <a href="{{ route('admin.menus.index') }}" class="btn btn-default">&lsaquo; Menus</a>
    <a href="{{ route('admin.menus.links.index', $menu->id) }}" class="btn btn-default">&lsaquo; Links</a>
    <a href="#" class="btn btn-primary" id="submit-sort"><i class="fa fa-save"></i>  Save Changes</a>
@endsection



@section('js')
    @include('admin.common.sort.script', ['route' => route('admin.menus.sort', $menu->id)])
@endsection