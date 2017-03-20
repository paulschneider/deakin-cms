<?php

namespace App\Http\Session;

use App\Models\Attachment;
use Illuminate\Support\Facades\Request;

class SectionsSession
{
    /**
     * Get the old section session values
     *
     * @return mixed
     */
    public function get()
    {
        $sections = Request::old('sections');
        if (!empty($sections)) {
            // Create a fields object so that it matches the model
            foreach ($sections as &$section) {
                $info              = config("sections.sections.{$section['template']}");
                $section['fields'] = (object) $section;
                $section['info']   = $info;
                if (!empty($info['attachments'])) {
                    $section['attachments'] = [];
                    foreach ($info['attachments'] as $field) {
                        if (!empty($section[$field])) {
                            $section['attachments'][$section[$field]] = Attachment::find($section[$field]);
                        }
                    }
                }
            }

            return $sections;
        }
    }
}
