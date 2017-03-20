<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ModifiedRequest as FormRequest;

class TermFormRequest extends FormRequest
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
        // Create a stub
        if (empty($data['stub'])) {
            $data['stub'] = $data['name'];
        }
        // Make sure the passed in slug is a slug
        $data['stub'] = str_slug($data['stub']);

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
                    'vocabulary_id' => 'required',
                    'name'          => 'required',
                    'stub'          => 'required|unique:terms,stub',
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'vocabulary_id' => 'required',
                    'name'          => 'required',
                    'stub'          => 'required|unique:terms,stub,' . $this->term,
                ];

            default:
                break;
        }

        return [];
    }
}
