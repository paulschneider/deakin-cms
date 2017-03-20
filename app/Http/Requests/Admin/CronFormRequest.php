<?php

namespace App\Http\Requests\Admin;

use Artisan;
use Validator;
use App\Http\Requests\ModifiedRequest as FormRequest;

class CronFormRequest extends FormRequest
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
        Validator::extend('command_exists', function ($attribute, $value, $parameters) {
            $chunk       = explode(' ', $value);
            $first       = reset($chunk);
            $allCommands = Artisan::all();

            return array_key_exists($first, $allCommands);
        });

        return [
            'command'   => 'required|command_exists',
            'min'       => 'required',
            'hour'      => 'required',
            'day_month' => 'required',
            'month'     => 'required',
            'day_week'  => 'required',
            'year'      => 'required_if:once,1|numeric',
        ];
    }

    /**
     * Modify the input before validation
     *
     * @param  array   $data The data array
     * @return array
     */
    public function modifyInput(array $data)
    {
        if (empty($data['once'])) {
            $data['year'] = null;
        }

        return $data;
    }

    /**
     * Set a message for the custom validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'command_exists' => 'The :attribute value could not be resolved. Is it accessible via php artisan?',
        ];
    }
}
