<?php
namespace App\Http\Requests\FrontEnd;

use App\Http\Requests\ModifiedRequest as FormRequest;

class SubmissionFormRequest extends FormRequest
{
    /**
     * The rules array
     *
     * @var array
     */
    protected $rules = [];

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
        // Add the url and the ip address
        $data = add_url_and_ip($data);

        // Make sure that any thing that is not in the rules is removed
        $filtered = [];
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->rules)) {
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
        return $this->rules;
    }
}
