<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class ModifiedRequest extends FormRequest
{
    /**
     * Overrite the all method
     *
     * @return array
     */
    public function all()
    {
        $data = parent::all();
        $data = $this->modifyInput($data);

        return $data;
    }

    /**
     * Function that can be overridden to manipulate the input data before anything
     * happens with it.
     *
     * @param  array $data The original data.
     * @return array The new modified data.
     */
    public function modifyInput(array $data)
    {
        return $data;
    }
}
