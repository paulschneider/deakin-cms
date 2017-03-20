<?php namespace App\Http\Requests\Admin;

use BlockManager;
use App\Http\Requests\ModifiedRequest as FormRequest;

class BlockFormRequest extends FormRequest
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
        // Get the type of block and it's registration
        $block = BlockManager::blockInfo($this->get('block_type'));
        $rules = ['name' => 'required', 'block_type' => 'required'];

        return array_merge($block['rules'], $rules);
    }
}
