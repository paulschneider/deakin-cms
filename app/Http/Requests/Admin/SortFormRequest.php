<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\ModifiedRequest as FormRequest;

class SortFormRequest extends FormRequest
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
        $data['serial'] = json_decode($data['serial']);
        $data['serial'] = $this->sortNested($data['serial']);

        return $data;
    }

    /**
     * Deals with nested data.
     * Modifies into a useable format for us.
     *
     * @return array
     */
    public function sortNested($nestableData, $parent = null, $built = [])
    {
        $weight = 0;

        foreach ($nestableData as $data) {
            if (empty($data->id)) {
                continue;
            }

            $insert = [
                'id'     => $data->id,
                'weight' => $weight,
                'parent' => $parent,
            ];

            if (isset($data->delta)) {
                $insert['delta'] = $data->delta;
            }

            $built[] = $insert;

            ++$weight;

            if (!empty($data->children)) {
                $built = $this->sortNested($data->children, $data->id, $built);
            }
        }

        return $built;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'serial' => 'required',
        ];
    }
}
