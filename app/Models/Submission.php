<?php
namespace App\Models;

use App\Traits\MetaTrait;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use MetaTrait;

    protected $table = 'submissions';

    protected $fillable = ['type', 'subject', 'uri', 'summary', 'data', 'email', 'ip_address', 'status'];

    /**
     * Get the allowed meta
     *
     * @return array
     */
    public function getAllowedMeta()
    {
        // Get the meta fields from the optional fields for the type of form
        $type  = \Request::get('type');
        $metas = config("forms.forms.{$type}.rules");
        $data  = \Request::only(array_keys($metas));

        return empty($data) ? [] : array_keys($data);
    }

    public function getField($field = null)
    {
        if (empty($this->{$field})) {
            $json = json_decode($this->data);

            if (isset($json->{$field})) {
                return $json->{$field};
            }
        }

        return $this->{$field};
    }
}
