<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * All the fillable attributes
     *
     * @var array
     */
    protected $fillable = ['name', 'type', 'region', 'content', 'includes', 'excludes', 'weight', 'online'];

    /**
     * Relationship to terms
     *
     * @return Terms
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Term');
    }

    /**
     * Relationship to pages
     *
     * @return Pages
     */
    public function pages()
    {
        return $this->belongsToMany('App\Models\PageRevision', 'page_revisions');
    }

    /**
     * Add the fields to a block
     */
    public function addBlockFields()
    {
        if (!empty($this->content)) {
            $content = unserialize($this->content);
            unset($this->content);

            foreach ($content as $field => $value) {
                $this->{$field} = $value;
            }
        }

        if (empty($this->class)) {
            $this->class = str_slug($this->name);
        }

        return $this;
    }
}
