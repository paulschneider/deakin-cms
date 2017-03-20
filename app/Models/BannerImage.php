<?php namespace App\Models;

use App\Traits\MetaTrait;
use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    use MetaTrait;

    /**
     * Change the table to something nicer.
     * @var string
     */
    protected $table = 'banners_images';

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'online', 'banner_id', 'weight', 'parent_id', 'attachment_id', 'method'];

    /**
     * The allowed meta tags
     * @var array
     */
    protected $allowedMeta = ['meta_target', 'meta_rel', 'meta_class', 'meta_id'];

    /**
     * Relationship to banner
     * @return (Banner) The banner that this link belongs to
     */
    public function banner()
    {
        return $this->belongsTo('App\Models\Banner');
    }

    /**
     * Relationship to attachment
     * @return (Attachment) The file this banner image should use.
     */
    public function attachment()
    {
        return $this->belongsTo('App\Models\Attachment');
    }
}
