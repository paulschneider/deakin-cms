<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\ModifiedRequest as FormRequest;

class AdminConfigurationFormRequest extends FormRequest
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
     * Doing this all in one place
     *
     * @param  array   $data The data array
     * @return array
     */
    public function modifyInput(array $data)
    {
        $allowed = [
            'admin__title',
            'admin__title__short',
            'admin__welcome',
            'login__title',
            'login__help',
            'forgot__title',
            'forgot__help',
            'reset__title',
            'reset__help',
            'register__title',
            'register__help',
            'activate__title',
            'activate__help',
        ];

        $filtered = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'admin__title'        => 'required',
            'admin__title__short' => 'required',
            'admin__welcome'      => 'required',
            'login__title'        => 'required',
            'login__help'         => 'required',
            'forgot__title'       => 'required',
            'forgot__help'        => 'required',
            'reset__title'        => 'required',
            'reset__help'         => 'required',
            'register__title'     => 'required',
            'register__help'      => 'required',
            'activate__title'     => 'required',
            'activate__help'      => 'required',
        ];
    }
}
