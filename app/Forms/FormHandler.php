<?php
namespace App\Forms;

use Request;
use App\Zendesk\Api as ZendeskApi;

class FormHandler
{
    /**
     * Get the fields for a form type
     *
     * @param  string  $type The form type
     * @return array
     */
    public function getFields($type, $submission)
    {
        $defaults = config('forms.default_rules');
        $fields   = config("forms.forms.{$type}.rules");
        if (!$fields) {
            throw new Exception("Form type: {$type} not defined", 1);
        }

        $fields = array_merge(array_keys($defaults), array_keys($fields));

        return $fields;
    }

    /**
     * Strip the empty fields from the form submission
     *
     * @param  array   $data The data
     * @return array
     */
    public function stripEmptyFields($data)
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            if (!empty($value) || is_numeric($value)) {
                $cleaned[$key] = $value;
            }
        }

        return $cleaned;
    }

    /**
     * Strip internal fields
     *
     * @param  array   &$data The data array
     * @param  boolean $key   Use the key or value
     * @return array
     */
    public function stripInternal(&$data, $key = true)
    {
        $internal = ['type', 'redirect', 'summary', 'subject', 'g-recaptcha-response', 'modal'];
        foreach ($internal as $field) {
            if ($key && array_key_exists($field, $data)) {
                unset($data[$field]);
            } elseif (!$key && in_array($field, $data)) {
                $i = array_search($field, $data);
                unset($data[$i]);
            }
        }
    }

    /**
     * Replace select keys with their option values
     *
     * @param array &$data   The data array
     * @param array $options The options array
     */
    public function replaceSelects(&$data, $options)
    {
        foreach ($data as $key => $value) {
            if (!empty($options[$key])) {
                $data[$key] = $options[$key][$value];
            }
            if (!empty($options[$key . 's'])) {
                $data[$key] = $options[$key . 's'][$value];
            }
        }
    }

    /**
     * Get the form
     *
     * @return array
     */
    public function getForm($name)
    {
        $path = Request::path();

        $redirect = config('forms.forms.' . $name . '.redirect', null);

        if (!empty($redirect)) {
            $path = $redirect;
        }

        return [
            'route'    => "frontend.{$name}.save",
            'options'  => config('forms.forms.' . $name . '.options', []),
            'redirect' => $path,
        ];
    }

    /**
     * Handle a generic form submission
     *
     * @param array  $values The values array
     * @param string $name   The name
     */
    public function formSubmission($values, $name)
    {
        $rule = config("forms.forms.{$name}.submission");
        if (!$rule) {
            throw new \Exception("Form definition for {$name} does not exit", 1);
        }

        $rule['title'] = $rule['subject'];

        $this->sendSubmissions($rule, $values);
    }

    /**
     * Send a submission to the intended users
     *
     * @param array $rule The rule
     * @param array $data The data
     */
    protected function sendSubmissions($rule, $data)
    {
        if (empty($rule['subject']) && !empty($data['subject'])) {
            $rule['subject'] = $rule['title'] . ' - ' . $data['subject'];
        }

        $this->stripInternal($data);

        $subject            = $rule['subject'];
        $data['submission'] = $data;

        foreach (['first_name', 'given_names', 'name'] as $key) {
            if (!empty($data[$key])) {
                $data['greeting_name'] = $data[$key];
            }
        }

        $auto_email    = $data['email'];
        $auto_greeting = $data['greeting_name'];

        if (!empty($data['surname'])) {
            $auto_greeting .= ' ' . $data['surname'];
        }

        // submit the enquiry to zendesk
        if (getenv('APP_ENV') == "production") {
            (new ZendeskApi())->createTicket($data, 'contact-form');
        } else {
            \Log::info('simulate:: zendesk ticket created');
        }
    }
}
