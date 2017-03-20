<?php
namespace App\Models;

use App\Traits\RevisionTrait;
use App\Traits\ScheduleTrait;
use Laravel\Scout\Searchable;
use App\Traits\AliasableTrait;
use App\Traits\EntityLinkTrait;
use App\Contracts\RevisionInterface;
use App\Contracts\ScheduleInterface;
use App\Contracts\EntityLinkInterface;
use Illuminate\Database\Eloquent\Model;

class Page extends Model implements EntityLinkInterface, RevisionInterface, ScheduleInterface
{
    use EntityLinkTrait;
    use ScheduleTrait;
    use RevisionTrait;
    use AliasableTrait;
    use Searchable;

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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        // Default behaviour
        $array = $this->toArray();
        // Add revision info to search on.
        $array['model']    = 'Page';
        $array['type']     = 'Content';
        $array['url']      = route('frontend.dynamic.slug', [$this->slug]);
        $array['revision'] = $this->revision->toArray();

        $array['title'] = $array['revision']['title'];

        $sections      = $this->revision->sectionableRenderSections();
        $array['body'] = implode(' ', $sections);
        $array['body'] = strip_tags($array['body']);

        $array['updated_at'] = $this->updated_at->format('U');
        $array['created_at'] = $this->created_at->format('U');

        return $array;
    }
}
