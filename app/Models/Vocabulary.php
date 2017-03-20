<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vocabulary extends Model
{
    use SoftDeletes;

    protected $table = 'vocabularies';

    protected $fillable = ['name', 'stub', 'desciption'];

    /**
     * Relationship to Term
     * @return (Term Collection) The terms that blong to this vocabulary
     */
    public function terms()
    {
        return $this->hasMany('App\Models\Term')->orderBy('parent_id', 'ASC')->orderBy('weight', 'ASC');
    }
}
