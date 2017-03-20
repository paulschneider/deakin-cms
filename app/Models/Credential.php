<?php
namespace App\Models;

use App\Traits\RevisionTrait;
use App\Traits\ScheduleTrait;
use App\Traits\AliasableTrait;
use App\Traits\EntityLinkTrait;
use App\Contracts\RevisionInterface;
use App\Contracts\ScheduleInterface;
use App\Contracts\EntityLinkInterface;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model implements EntityLinkInterface, RevisionInterface, ScheduleInterface
{
    use EntityLinkTrait;
    use ScheduleTrait;
    use RevisionTrait;
    use AliasableTrait;

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['online', 'slug', 'revision_id'];

    /**
     * Any required attachments to this entity or its revision.
     * Required for read.
     *
     * @param  array   $options Add options to the append methods
     * @return $this
     */
    public function appendExtras($options = [])
    {
        // Append Meta Attributes to Revision
        $this->revision->appendMeta($options);
        $this->revision->appendSectionsWithFields();

        // Append Revision Attributes to Article
        $this->appendRevision();

        // Related links.
        $this->revision->load('related_links');

        return $this;
    }
}
