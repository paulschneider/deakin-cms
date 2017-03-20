<?php namespace App\Traits;

trait AliasableTrait
{
    public static function bootRelatedTrait()
    {
        static::deleting(function ($model) {
            $model->aliases()->delete();
        });
    }

    /**
     * Relationship to aliases
     *
     * @return aliases
     */
    public function aliases()
    {
        return $this->morphMany('App\Models\Alias', 'aliasable');
    }
}
