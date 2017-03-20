<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'svg', 'attachment_id'];

    /**
     * Append more attributes
     * @var array
     */
    protected $appends = ['class'];

    /**
     * Append a class string for the name
     *
     * @return string
     */
    public function getClassAttribute()
    {
        return str_slug($this->title);
    }

    /**
     * Relationship to attachments
     *
     * @return ElquentRelationship
     */
    public function image()
    {
        return $this->hasOne('App\Models\Attachment', 'id', 'attachment_id');
    }
}
