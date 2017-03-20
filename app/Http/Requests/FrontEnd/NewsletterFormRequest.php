<?php
namespace App\Http\Requests\FrontEnd;

class NewsletterFormRequest extends SubmissionFormRequest
{
    /**
     * The rules array
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Construct
     */
    public function __construct()
    {
        // Get the defaults and the ones for this form
        $defaults = config('forms.default_rules');
        $contacts = config('forms.forms.newsletter.rules');

        $this->rules = array_merge($defaults, $contacts);
    }

    /**
     * Modify the input before validation
     *
     * @param  array   $data The data array
     * @return array
     */
    public function modifyInput(array $data)
    {
        $filtered = parent::modifyInput($data);

        return $filtered;
    }
}
