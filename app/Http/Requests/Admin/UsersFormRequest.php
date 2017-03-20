<?php

namespace App\Http\Requests\Admin;

use Entrust;
use App\Models\User;
use App\Http\Requests\ModifiedRequest as FormRequest;

class UsersFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Modify the input before validation
     *
     * @param  array   $data The data array
     * @return array
     */
    public function modifyInput(array $data)
    {
        $built = [];

        if (!empty($data['user_roles'])) {
            foreach ($data['user_roles'] as $role) {
                if (Entrust::can('admin.users.assign_role.' . $role) || Entrust::can('admin.users.roles.any')) {
                    $built[$role] = $role;
                }
            }
        }

        if ($this->method() == 'PATCH' && !Entrust::can('admin.users.roles.any')) {
            $uid = $this->segment(3);
            if (is_numeric($uid)) {
                $account = User::findOrFail($uid);
                $roles   = $account->roles()->pluck('id')->all();

                foreach ($roles as $role) {
                    if (!Entrust::can('admin.users.assign_role.' . $role)) {
                        // Add it back in.
                        $built[$role] = $role;
                    }
                }
            }
        }

        $data['user_roles'] = array_values($built);

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name'  => 'required',
                    'email' => 'required|email|unique:users,email',
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'name'  => 'required',
                    'email' => 'required|unique:users,email,' . $this->user,
                ];

            default:
                break;
        }

        return [];
    }
}
