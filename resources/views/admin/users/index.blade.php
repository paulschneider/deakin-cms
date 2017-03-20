@extends('admin.layouts.master', ['title' => 'Users'])

@section('content')

    <div class="row">
        <div class="col-sm-12">

            @if ($users->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="col-xs-1">Status</th>

                            @if (Entrust::can('admin.users.get.resetpassword') || Entrust::can('admin.users.get.resetactivation'))
                            <th class="col-xs-1">Reset&nbsp;Password</th>
                            @endif

                            @if(Entrust::can('admin.users.patch'))
                                <th class="col-xs-1">Edit</th>
                            @endif

                            @if(Entrust::can('admin.users.delete'))
                            <th class="col-xs-1">Delete</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role_names }}</td>
                                <td>{{ $user->active ? 'Active' : ($user->valid_activation ? 'Pending' : 'Inactive') }}</td>

                                @if (Entrust::can('admin.users.get.resetpassword') || Entrust::can('admin.users.get.resetactivation'))
                                <td>
                                    @if ($user->active)

                                        @if (Entrust::can('admin.users.get.resetpassword'))
                                            {!! link_to_route('admin.users.reset', 'Reset Password', $user->id, ['class' => 'btn btn-outline btn-primary btn-xs']) !!}
                                        @else
                                            Contact an admin
                                        @endif

                                    @else

                                        @if (Entrust::can('admin.users.get.resetactivation'))
                                           {!! link_to_route('admin.users.activate', ($user->valid_activation ? 'Resend Activation' : 'Activate Account'), $user->id, ['class' => 'btn btn-outline btn-primary btn-xs']) !!}
                                        @else
                                            Pending activation
                                        @endif
                                    @endif
                                </td>
                                @endif

                                @if(Entrust::can('admin.users.patch'))
                                    <td>{!! link_to_route('admin.users.edit', 'Edit', $user->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                @endif

                                @if(Entrust::can('admin.users.delete'))
                                    <td>{!! link_to_route('admin.users.delete', 'Delete', $user->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no users</p>
            @endif

            @include('common.pagination', ['paginator' => $users])

        </div>
    </div>
@endsection

@section('actions')
    @if(Entrust::can('admin.users.post'))
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add user</a>
    @endif
@endsection