<?php
namespace App\Http\Requests\Admin;

use App\Http\Requests\ModifiedRequest as FormRequest;

class SiteConfigurationFormRequest extends FormRequest
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
            'site__title',
            'site__copyright',
            'site__news__page',
            'site__loadmore__news',
            'site__email__footer',
            'site__email__autoresponder',
            'site__email__autoresponder__subject',
            'site__email__changing_jobs',
            'site__email__changing_jobs__subject',
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
            'site__title' => 'required',
        ];
    }
}
