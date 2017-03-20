
@if(Entrust::Can('admin.users.roles'))

<div class="form-group">
    <label class="col-sm-2 control-label">Roles</label>

    {!! Form::hidden('assign_roles', 1) !!}

    <div class="col-sm-10">
    @foreach ($roles as $role)

        @if (Entrust::can('admin.users.assign_role.'.$role->id) || Entrust::can('admin.users.roles.any'))

            <div class="checkbox">
                <label>
                    {!! Form::checkbox('user_roles[]', $role->id, ( isset($user) ? $user->hasRole($role->name) : false) ); !!}
                    {{ $role->display_name }}
                </label>
            </div>

        @endif

    @endforeach
    </div>
</div>

@endif