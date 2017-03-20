@extends('admin.layouts.master', ['title' => 'Sort a Vocabulary'])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Sorting {{ $vocabulary->name }}.</h5>
                </div>
                <div class="ibox-content">
                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            @include('admin.common.sort.tree', ['tree' => $tree, 'type' => 'term', 'label' => 'name'])
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('actions')
    <a href="{{ route('admin.vocabularies.index') }}" class="btn btn-default">&lsaquo; Vocabularies</a>
    <a href="{{ route('admin.vocabularies.terms.index', $vocabulary->id) }}" class="btn btn-default">&lsaquo; Terms</a>
    <a href="#" class="btn btn-primary" id="submit-sort"><i class="fa fa-save"></i>  Save Changes</a>
@endsection


@section('js')
    @include('admin.common.sort.script', ['route' => route('admin.vocabularies.sort', $vocabulary->id)])
@endsection