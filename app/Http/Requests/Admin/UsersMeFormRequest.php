<?php

namespace App\Http\Requests\Admin;

use Auth;
use App\Http\Requests\ModifiedRequest as FormRequest;

class UsersMeFormRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();

        return [
            'name'                  => 'required',
            'email'                 => 'required|unique:users,email,' . $user->id,
            'password'              => 'required|confirmed|' . config('auth.passwords.users.rule'),
            'password_confirmation' => 'required',
        ];
    }
}
