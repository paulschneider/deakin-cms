<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /**
     * All the fillable attributes
     *
     * @var array
     */
    protected $fillable = ['template', 'content', 'rendered_content', 'weight'];

    /**
     * Relationship to the sectionable entity
     *
     * @return Relationship
     */
    public function sectionable()
    {
        return $this->morphTo();
    }
}
