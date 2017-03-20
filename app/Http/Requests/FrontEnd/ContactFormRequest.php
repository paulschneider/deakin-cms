<?php
namespace App\Http\Requests\FrontEnd;

class ContactFormRequest extends SubmissionFormRequest
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
        $contacts = config('forms.forms.contact.rules');

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

        // Create the summary
        $this->summary($filtered);
        // Create the subject
        $this->subject($filtered);

        return $filtered;
    }

    /**
     * Create the summary from the data passed in
     *
     * @param array &$data The data array
     */
    public function summary(&$data)
    {
        $summary = [];
        $fields  = ['first_name', 'surname', 'company_name', 'contact'];
        foreach ($fields as $field) {
            if (!empty($data[$field])) {
                $summary[] = $data[$field];
            }
        }

        $data['summary'] = implode("\n", $summary);
    }

    /**
     * Create the subject of the form
     *
     * @param array &$data The data array
     */
    public function subject(&$data)
    {
        $data['subject'] = 'Contact Form';
    }
}
