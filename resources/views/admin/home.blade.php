@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="ibox">
                <div class="ibox-title"><h5>Administration Dashboard</h5></div>
                <div class="ibox-content">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="ibox">
                <div class="ibox-title"><h5>Current Users</h5></div>
                <div class="ibox-content">
                    <p>The following people have used the administration area within the last {{ config('ip.activity_watch') }} minutes:</p>

                        <table class="table table-bordered table-striped table-hover" width="100%">

                        @foreach($activity as $user)

                            <tr>
                                <td><strong>{{ $user['email'] or 'Unknown user' }}</strong></td>
                                <td><i class="fa fa-clock-o"></i> {{ $user['when']->diffForHumans() }}</td>
                                <td>{{ $user['ip'] }}</td>
                            </tr>

                        @endforeach

                        </table>
                </div>
            </div>
        </div>
    </div>

@endsection
