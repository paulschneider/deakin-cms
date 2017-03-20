<?php

namespace App\Repositories;

class SectionsRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Set the cache tags to be flushed on updates and deleted
     *
     * @var array
     */
    protected $cache_tags = ['sections'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Section';
    }

    /**
     * Add rules to any form request that has sections
     *
     * @param  array   $data  The data array
     * @param  array   $rules The rules
     * @return array
     */
    public function addRules($data = [], $rules = [])
    {
        if (!empty($data['sections'])) {
            $sections = config('sections.sections');
            foreach ($data['sections'] as $key => $item) {
                // Get the rules for the template
                $section_rules = $sections[$item['template']]['fields'];
                foreach (array_keys($item) as $field) {
                    if (!empty($section_rules[$field])) {
                        $rule                              = str_replace('[[counter]]', $key, $section_rules[$field]);
                        $rules["sections.{$key}.{$field}"] = $rule;
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Add custom messages to the errors
     *
     * @param  array   $rules The rules set
     * @return array
     */
    public function addCustomMessages($rules = [])
    {
        $messages = [];

        foreach ($rules as $rule => $types) {
            if (preg_match('/sections\.(\d+)\./', $rule, $matches)) {
                $field = preg_replace('/sections\.\d+\./', '', $rule);
                $types = explode('|', $types);
                foreach ($types as $type) {
                    $message   = trans("validation.{$type}");
                    $attribute = preg_replace('/[-_]/', ' ', $field);
                    $number    = (int) $matches[1] + 1;
                    if (!empty($message)) {
                        $attribute = "{$attribute} in seciton {$number}";
                        $message   = str_replace(':attribute', $attribute, $message);
                    } else {
                        // Hopefully we don't get here
                        $message = "{$attribute} in seciton {$number} needs to be {$type}.";
                    }
                    $messages["{$rule}.{$type}"] = $message;
                }
            }
        }

        return $messages;
    }
}
