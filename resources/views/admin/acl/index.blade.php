@extends('admin.layouts.master', ['title' => 'ACL'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('method' => 'PATCH', 'route' => array('admin.acl.index'), 'class' => 'form-horizontal')) !!}

                <div class="col-sm-12">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="col-sm-3">&nbsp;</th>
                                @foreach ($roles as $role)
                                    <th class="col-sm-1"><center>{{{ $role->name }}}</center></th>
                                @endforeach
                                <th class="col-sm-2">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($permissions as $permission)
                            <tr>
                                <th>{{{ $permission->display_name }}}</th>
                                @foreach ($roles as $role)
                                    <td align="center">
                                        {!! Form::checkbox('role['.$role->id.']['.$permission->id.']', 1, in_array($role->id, $whoHas[$permission->id])) !!}
                                    </td>
                                @endforeach
                                <td>
                                {{ $permission->name }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save ACL', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection
