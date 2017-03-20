<?php
namespace App\Models;

use App\Traits\StaplerTrait;
use Illuminate\Database\Eloquent\Model;
use Codesleeve\Stapler\ORM\StaplerableInterface as StaplerableInterface;

class Attachment extends Model implements StaplerableInterface
{
    use StaplerTrait;

    protected $fillable = ['title', 'alt', 'file', 'term_id', 'slug'];

    /**
     * Which fields are ignored by the revision comparison
     * @var array
     */
    public $revisionIgnore = ['file'];

    /**
     * Fix an issue with L4 Traits.
     */
    protected static function boot()
    {
        static::bootTraits();
    }

    /**
     * Relationship to vocabulary
     * @return (Vocabulary) The vocabulary that this term belongs to
     */
    public function term()
    {
        return $this->belongsTo('App\Models\Term');
    }

    /**
     * Relationship to metas
     *
     * @return Relationship
     */
    public function metas()
    {
        return $this->hasMany('App\Models\Meta');
    }

    public function scopeFolder($query, $args)
    {
        if ($args['id']) {
            $query->where('term_id', '=', $args['id']);
        }
    }

    public function __construct(array $attributes = [])
    {
        $this->hasAttachedFile('file', [
            'styles' => config('attachments.styles.sizes'),
        ]);

        parent::__construct($attributes);
    }
}
