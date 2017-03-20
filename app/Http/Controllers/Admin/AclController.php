<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AclFormRequest;
use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Flash;
use Illuminate\Http\Request;

class AclController extends Controller
{
    /**
     * The permission repository
     *
     * @var PermissionsRepository
     */
    protected $permission;

    /**
     * The role repository
     *
     * @var RolesRepository
     */
    protected $role;

    /**
     * Dependency Injection
     *
     * @param PermissionsRepository $permission The instance of PermissionsRepository
     * @param RolesRepository       $permission The instance of RolesRepository
     */
    public function __construct(PermissionsRepository $permission, RolesRepository $role)
    {
        $this->permission = $permission;
        $this->role       = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles       = $this->role->all();
        $permissions = $this->permission->all();

        $whoHas = [];
        foreach ($permissions as $permission) {
            $whoHas[$permission->id] = $permission->roles()->pluck('role_id')->all();
        }

        return view('admin.acl.index', compact('permissions', 'roles', 'whoHas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AclFormRequest $request
     * @return Response
     */
    public function update(AclFormRequest $request)
    {
        $roles       = $this->role->all();
        $permissions = $this->permission->all();

        $data = $request->all();

        foreach ($roles as $role) {
            $setting = [];
            foreach ($permissions as $permission) {
                if ($value = array_get($data, 'role.' . $role->id . '.' . $permission->id, false)) {
                    $setting[] = $permission->id;
                }
            }

            $role->savePermissions($setting);
        }

        Flash::success('Updated.');

        return redirect()->route('admin.acl.index');
    }
}
