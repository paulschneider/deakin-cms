@extends('admin.layouts.master', ['title' => "Create a Link in {$menu->name}"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.menus.links.store', $menu->id), 'class' => 'form-horizontal')) !!}

                {!! Form::hidden('menu_id', $menu->id) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Link Details</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::title(['type' => 'text']) !!}
                        {!! FormField::route(['type' => 'text', 'label' => 'URL or Route:']) !!}
                        {!! FormField::parent_id(['type' => 'select', 'label' => 'Parent:', 'options' => $parents]) !!}
                    </div>
                </div>



                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Link Metadata</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::meta_description(['type' => 'text', 'label' => 'Description:']) !!}
                        {!! FormField::meta_target(['type' => 'select', 'label' => 'Target:', 'options' => config('links.targets')]) !!}
                        {!! FormField::meta_rel(['type' => 'text', 'label' => 'Rel attribute:']) !!}
                        {!! FormField::meta_class(['type' => 'text', 'label' => 'Class attribute:']) !!}
                        {!! FormField::meta_id(['type' => 'text', 'label' => 'ID attribute:']) !!}
                        {!! FormField::meta_icon(['type' => 'text', 'label' => 'Font-awesome icon:']) !!}
                    </div>
                </div>

                @include('admin.common.form_online')


                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save link', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
