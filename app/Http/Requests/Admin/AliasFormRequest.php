<?php namespace App\Http\Requests\Admin;

use Validator;
use App\Http\Requests\ModifiedRequest as FormRequest;

class AliasFormRequest extends FormRequest
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
        $alias = $data['alias'];

        if (stristr($alias, 'http')) {
            $alias = parse_url($alias);
            $alias = $alias['path'];
        }

        $data['alias'] = trim($alias, '/');

        $redirects_to = $data['redirects_to'];

        if (stristr($redirects_to, 'http')) {
            $redirects_to = parse_url($redirects_to);
            $redirects_to = $redirects_to['path'];
        }

        $data['redirects_to'] = trim($redirects_to, '/');

        $repo = app()->make('App\Repositories\AliasRepository');

        if ($resolved = $repo->resolve($data['redirects_to'])) {
            $data['aliasable_id']   = $resolved->entity->id;
            $data['aliasable_type'] = get_class($resolved->entity);
        }

        $data['type'] = 'custom';

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Validator::extend('alias_not_resolved', function ($attribute, $value, $parameters) {
            $repo = app()->make('App\Repositories\AliasRepository');
            return empty($repo->resolve($value));
        });

        switch ($this->method()) {
            case 'POST':
                return [
                    'aliasable_id'   => 'required',
                    'aliasable_type' => 'required',
                    'alias'          => 'alias_not_resolved|required|unique:aliases,alias',
                    'redirects_to'   => 'required|different:alias',
                ];

            case 'PUT':
            case 'PATCH':
                return [
                    'aliasable_id'   => 'required',
                    'aliasable_type' => 'required',
                    'alias'          => 'alias_not_resolved|required|unique:aliases,alias,' . $this->alias,
                    'redirects_to'   => 'required|different:alias',
                ];

            default:
                break;
        }

        return [];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'alias_not_resolved' => 'The :attribute field contains an alias that already exists. This could result in a redirect loop.',
        ];
    }
}
