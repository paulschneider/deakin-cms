<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\ModifiedRequest as FormRequest;

class MenuLinkFormRequest extends FormRequest
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
        return [
            'menu_id' => 'required',
            'title'   => 'required',
            'route'   => 'required',
        ];
    }
}
