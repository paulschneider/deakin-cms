<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'metas';

    protected $fillable = ['metable_type', 'metable_id', 'key', 'value', 'attachment_id'];

    /**
     * Which fields are ignored by the revision comparison
     * @var array
     */
    public $revisionIgnore = ['metable_id'];

    /**
     * Relationship to meta
     *
     * @return Relationship
     */
    public function attachment()
    {
        return $this->belongsTo('App\Models\Attachment', 'attachment_id');
    }
}
