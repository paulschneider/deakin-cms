<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'stub', 'online'];

    /**
     * Relationship to BannerImage
     * @return (BannerImage Collection) The links that blong to this vocabulary
     */
    public function images()
    {
        return $this->hasMany('App\Models\BannerImage')->orderBy('parent_id', 'ASC')->orderBy('weight', 'ASC');
    }
}
