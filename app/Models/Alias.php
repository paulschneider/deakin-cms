<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
    protected $fillable = ['alias', 'aliasable_id', 'aliasable_type', 'type'];

    /**
     * Relationship to Entity
     *
     * @return Entity
     */
    public function entity()
    {
        return $this->morphTo('aliasable');
    }

    /**
     * Logic for link provisioning.
     * @return string
     */
    public function getRedirectAttribute()
    {
        switch ($this->aliasable_type) {
            case 'App\Models\Page':
            case 'App\Models\Credential':
                return route('frontend.dynamic.slug', $this->entity->slug);

            case 'App\Models\Article':
                return route('frontend.articles.slug', $this->entity->slug);
        }

        return '/';
    }
}
