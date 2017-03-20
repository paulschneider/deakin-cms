<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Related extends Model
{
    /**
     * Change the table to something nicer.
     * @var string
     */
    protected $table = 'related_links';

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['related_type', 'related_id', 'title', 'external_url', 'link_id', 'weight', 'class', 'icon_id'];

    /**
     * Which fields are ignored by the revision comparison
     * @var array
     */
    public $revisionIgnore = ['related_id'];

    /**
     * Relationship to link
     * @return (MenuLink) The menu that this link belongs to
     */
    public function link()
    {
        return $this->belongsTo('App\Models\MenuLink');
    }

    /**
     * Relationship to icons
     *
     * @return Icon The icon that this related link is associated to
     */
    public function icon()
    {
        return $this->belongsTo('App\Models\Icon');
    }

    /**
     * Get the url of the related link
     *
     * @return string
     */
    public function url()
    {
        if ($this->link) {
            return '/' . $this->link->route;
        }

        return $this->external_url;
    }
}
