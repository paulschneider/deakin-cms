<?php
namespace App\Models;

use App\Traits\MetaTrait;
use App\Traits\BannerTrait;
use App\Traits\FilterTrait;
use App\Traits\RelatedTrait;
use App\Traits\ScheduleTrait;
use App\Traits\SectionableTrait;
use Illuminate\Database\Eloquent\Model;

class CredentialRevision extends Model
{
    use MetaTrait;
    use ScheduleTrait;
    use RelatedTrait;
    use FilterTrait;
    use SectionableTrait;
    use BannerTrait;

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'body', 'summary', 'status', 'entity_id', 'user_id', 'user_ip', 'entity_color', 'logo_id'];

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
     * The meta that should by a boolean
     * @var array
     */
    protected $booleanMeta = ['meta_glossary'];

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
     * Relationship to Credential
     * This is called entity to keep it androgenous.
     * Used backwards in common revision views.
     *
     * @return Credential
     */
    public function entity()
    {
        return $this->belongsTo('App\Models\Credential');
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
     * @return (Attachment) The file this article image should use.
     */
    public function logo()
    {
        return $this->belongsTo('App\Models\Attachment', 'logo_id');
    }
}
