<?php namespace App\Http\Requests\Admin;

use App\Repositories\SectionsRepository;
use App\Http\Requests\ModifiedRequest as FormRequest;

class PageFormRequest extends FormRequest
{
    /**
     * Instance of the sections repository
     *
     * @var SectionsRepository
     */
    protected $section;

    /**
     * The constructor
     *
     * @param SectionsRepository $section The instance of section repository
     */
    public function __construct(SectionsRepository $section)
    {
        $this->section = $section;
    }

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
        // Create a slug
        if (empty($data['slug'])) {
            $data['slug'] = $data['title'];
        }
        $parts = explode('/', $data['slug']);
        // Make sure the passed in slug is a slug
        $data['slug'] = str_slug(last($parts));

        if (!empty($data['body']) && empty($data['summary'])) {
            $summary         = clean($data['body'], 'basic_html');
            $data['summary'] = truncate_html($summary, 150, $elipsis = '&hellip;');
        }

        return $data;
    }

    /**
     * Overrite custom error messages
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages = $this->section->addCustomMessages($this->rules());

        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title'   => 'required',
            // 'body'   => 'required',
            'slug'    => 'required',
            'menu.id' => 'required_if:menu.create,1',
        ];

        // Add the section rules
        $rules = $this->section->addRules($this->all(), $rules);

        return $rules;
    }
}
