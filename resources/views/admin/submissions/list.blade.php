@extends('admin.layouts.master', ['title' => 'Form Submissions'])

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
                    @foreach($routes as $route => $options)
                        <tr><td>{!! link_to_route($route, $options['title'], $options['type']) !!}</td></tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
