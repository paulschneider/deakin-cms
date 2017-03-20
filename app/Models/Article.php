<?php
namespace App\Models;

use App\Contracts\RevisionInterface;
use App\Contracts\ScheduleInterface;
use App\Traits\AliasableTrait;
use App\Traits\RevisionTrait;
use App\Traits\ScheduleTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model implements RevisionInterface, ScheduleInterface
{
    use ScheduleTrait;
    use RevisionTrait;
    use AliasableTrait;
    use Searchable;

    protected $dates = ['event_at'];

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['online', 'slug', 'revision_id', 'import_id', 'created_at', 'is_featured'];

    /**
     * Any required attachments to this entity or its revision.
     * Required for read.
     *
     * @param  array   $options The options to pass though to the append methods
     * @return $this
     */
    public function appendExtras($options = [])
    {
        // Append Meta Attributes to Revision
        $this->revision->appendMeta($options);

        // Append Revision Attributes to Article
        $this->appendRevision();

        // Related links.
        $this->revision->load('related_links');
        $this->revision->load('image');
        $this->revision->load('thumbnail');

        return $this;
    }

    /**
     * is this a featured_article
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return is_null($this->is_featured) ? false : true;
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
        $array['model'] = 'Article';

        $array['url']                       = route('frontend.articles.slug', [$this->slug]);
        $array['is_featured']               = $this->is_featured;
        $array['revision']                  = $this->revision->toArray();
        $array['revision']['article_types'] = $this->revision->article_types->toArray();
        $types                              = $this->revision->article_types->pluck('name')->all();
        $array['type']                      = implode(', ', $types);
        $array['title']                     = $array['revision']['title'];
        $array['body']                      = strip_tags($array['revision']['body']);

        if ($this->revision->thumbnail_id) {
            $array['image'] = $this->revision->thumbnail->file->url('medium');
        }

        $array['updated_at'] = $this->updated_at->format('U');
        $array['created_at'] = $this->created_at->format('U');

        return $array;
    }
}
