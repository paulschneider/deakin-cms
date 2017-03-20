@extends('admin.layouts.master', ['title' => 'Configurations'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $route => $label)
                        <tr><td>{!! link_to_route($route, $label) !!}</td></tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
