<?php
namespace App\Models;

use App\Traits\BannerTrait;
use App\Traits\FilterTrait;
use App\Traits\MetaTrait;
use App\Traits\RelatedTrait;
use Illuminate\Database\Eloquent\Model;

class ArticleRevision extends Model
{
    use MetaTrait;
    use FilterTrait;
    use RelatedTrait;
    use BannerTrait;

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'body', 'summary', 'status', 'entity_id', 'user_id', 'user_ip', 'event_at', 'thumbnail_id', 'image_id', 'author'];

    /**
     * Which fields are ignored by the revision comparison
     * @var array
     */
    public $revisionIgnore = ['user_ip'];

    /**
     * The allowed meta tags
     * @var array
     */
    protected $allowedMeta = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_social_title',
        'meta_social_description',
        'meta_social_image',
        'meta_banner',
    ];

    /**
     * The meta that should link to attachements
     * @var array
     */
    protected $attachmentMeta = ['meta_social_image'];

    /**
     * Items to be converted to DateTime
     * @var array
     */
    protected $dates = ['event_at'];

    /**
     * Overriding getDefaultMeta to add default title
     *
     * @return array
     */
    public function getDefaultMeta()
    {
        $defaults['meta_title'] = $this->title;

        return $defaults;
    }

    /*
    |---------------------------------------------------------------------
    | Relationships
    |---------------------------------------------------------------------
    |
     */

    /**
     * Relationship to Article
     * This is called entity to keep it androgenous.
     * Used backwards in common revision views.
     *
     * @return Article
     */
    public function entity()
    {
        return $this->belongsTo('App\Models\Article');
    }

    /**
     * Relationship to Terms
     *
     * @return Terms
     */
    public function article_types()
    {
        return $this->belongsToMany('App\Models\Term')
                    ->withPivot('type')
                    ->wherePivot('type', '=', 'article_type');
    }

    /**
     * Relationship to Terms
     *
     * @return Terms
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Term')
                    ->withPivot('type')
                    ->wherePivot('type', '=', 'category');
    }

    /**
     * Relationship to Terms
     *
     * @return Terms
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Term')
                    ->withPivot('type')
                    ->wherePivot('type', '=', 'tag');
    }

    /**
     * Relationship to User
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Relationship to attachment
     * @return (Attachment) The file this article thumbnail should use.
     */
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Attachment', 'thumbnail_id');
    }

    /**
     * Relationship to attachment
     * @return (Attachment) The file this article image should use.
     */
    public function image()
    {
        return $this->belongsTo('App\Models\Attachment', 'image_id');
    }
}
