<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    use SoftDeletes;

    protected $fillable = ['vocabulary_id', 'name', 'stub', 'parent_id'];

    /**
     * Relationship to vocabulary
     * @return (Vocabulary) The vocabulary that this term belongs to
     */
    public function vocabulary()
    {
        return $this->belongsTo('App\Models\Vocabulary');
    }

    /**
     * Relationship to blocks
     *
     * @return Blocks
     */
    public function blocks()
    {
        return $this->belongsToMany('App\Models\Block');
    }

    /**
     * Relationship to blocks
     *
     * @return Blocks
     */
    public function articles()
    {
        return $this->belongsToMany('App\Models\ArticleRevision', 'article_revisions');
    }

    /**
     * Relationship to blocks
     *
     * @return Blocks
     */
    public function pages()
    {
        return $this->belongsToMany('App\Models\PageRevision', 'page_revisions');
    }
}
