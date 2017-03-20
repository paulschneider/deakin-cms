<?php namespace App\Forms;

use Config;
use Form;
use Session;

class FormField
{
    /**
     * Instance
     *
     * @var App\Forms\FormField
     */
    protected static $instance;
    /**
     * Make the form field
     *
     * @param string $name
     * @param array  $args
     */
    public function make($name, array $args)
    {
        $wrapper = $this->createWrapper($name);
        $field   = $this->createField($name, $args);

        return str_replace('{{FIELD}}', $field, $wrapper);
    }

    /**
     * Prepare the wrapping container for
     * each field.
     */
    protected function createWrapper($name)
    {
        $wrapper      = Config::get('formfield.wrapper');
        $wrapperClass = Config::get('formfield.wrapperClass');

        $errors = Session::get('errors', new \Illuminate\Support\MessageBag);

        $messages = $errors->getMessages();

        if (array_key_exists($name, $messages)) {
            $wrapperClass .= ' has-error';
        }

        return '<' . $wrapper . ' class="' . $wrapperClass . '">{{FIELD}}</' . $wrapper . '>';
    }

    /**
     * Create the form field
     *
     * @param string $name
     * @param array  $args
     */
    protected function createField($name, $args)
    {
        // If the user specifies an input type, we'll just use that.
        // Otherwise, we'll take a best guess approach.
        $type = array_get($args, 'type') ?: $this->guessInputType($name);

        // We'll default to Bootstrap-friendly input class names
        $class = Config::get('formfield.inputClass');

        if (!empty($args['class'])) {
            $class .= ' ' . $args['class'];
            unset($args['class']);
        }

        $args  = array_merge(['class' => $class], $args);
        $field = $this->createLabel($args, $name);
        $field .= $this->createInput($type, $args, $name);

        return $field;
    }

    protected function createDescription($description)
    {
        $class = Config::get('formfield.descriptionClass');

        return '<span class="' . $class . '">' . $description . '</span>';
    }

    /**
     * Handle of creation of the label
     *
     * @param array  $args
     * @param string $name
     */
    protected function createLabel($args, $name)
    {
        $label = array_get($args, 'label');
        // If no label was provided, let's do our best to construct
        // a label from the method name.
        if (null === $label) {
            $label = $this->prettifyFieldName($name);
        }

        $class = Config::get('formfield.labelClass');

        if (!empty($args['label-class'])) {
            $class .= ' ' . $args['label-class'];
        }

        if (stristr($class, 'sr-only')) {
            $class = 'sr-only';
        }

        return $label ? Form::label($name, $label, ['class' => $class]) : '';
    }

    /**
     * Manage creation of input
     *
     * @param string $type
     * @param array  $args
     * @param string $name
     */
    protected function createInput($type, $args, $name)
    {
        $default = (isset($args['default']) ? $args['default'] : null);
        $class   = Config::get('formfield.inputWrapperSubclass');

        if (isset($args['label-class'])) {
            if (stristr('sr-only', $args['label-class'])) {
                $class = 'col-sm-12';
            }
        }

        if (isset($args['input-wrap-class'])) {
            $class = $args['input-wrap-class'];
        }

        unset($args['label']);
        unset($args['label-class']);
        unset($args['input-wrap-class']);

        switch ($type) {
            case 'password':
                $result = Form::password($name, $args);
                break;
            case 'select':
                $options = $args['options'];
                unset($args['options']); // Have to unset because will throw error on arrays.

                if (isset($args['empty'])) {
                    $options = array_merge(['' => $args['empty']], $options);
                }
                $result = Form::select($name, $options, $default, $args);
                break;
            default:
                $result = Form::$type($name, $default, $args);
                break;
        }

        // Get the description out if needed
        $description = null;

        if (!empty($args['field-description'])) {
            $description = $args['field-description'];
            unset($args['field-description']);
        }

        // Add the description
        if ($description) {
            $description = $this->createDescription($description);
        }

        return '<div class="' . $class . '">' . $result . $description . '</div>';
    }

    /**
     * Provide a best guess for what the
     * input type should be.
     *
     * @param string $name
     */
    protected function guessInputType($name)
    {
        return array_get(Config::get('formfield.commonInputsLookup'), $name) ?: 'text';
    }

    /**
     * Clean up the field name for the label
     *
     * @param string $name
     */
    protected function prettifyFieldName($name)
    {
        $name = str_replace('_', ' ', $name);
        return ucwords(preg_replace('/(?<=\w)(?=[A-Z])/', " $1", $name));
    }

    /**
     * Handle dynamic method calls
     *
     * @param string $name
     * @param array  $args
     */
    public static function __callStatic($name, $args)
    {
        $args     = empty($args) ? [] : $args[0];
        $instance = static::$instance;
        if (!$instance) {
            $instance = static::$instance = new static;
        }

        return $instance->make($name, $args);
    }
}
