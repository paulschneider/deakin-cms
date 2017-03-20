<?php namespace App\Models;

use App\Traits\EntityLinkBackwardsTrait;
use App\Traits\MetaTrait;
use Illuminate\Database\Eloquent\Model;

class MenuLink extends Model
{
    use MetaTrait;
    use EntityLinkBackwardsTrait;

    /**
     * Change the table to something nicer.
     * @var string
     */
    protected $table = 'menus_links';

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'route', 'online', 'menu_id', 'weight', 'parent_id'];

    /**
     * The allowed meta tags
     * @var array
     */
    protected $allowedMeta = ['meta_target', 'meta_rel', 'meta_class', 'meta_id', 'meta_icon', 'meta_description'];

    /**
     * Relationship to menu
     * @return (Menu) The menu that this link belongs to
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Menu');
    }
}
