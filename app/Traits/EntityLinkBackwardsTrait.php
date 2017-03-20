<?php namespace App\Traits;

use App\Models\Meta;
use App\Jobs\SaveEntityLinkBackwardsCommand;

trait EntityLinkBackwardsTrait
{
    public static function bootEntityLinkBackwardsTrait()
    {
        static::saved(function ($model) {
            $model->updateAttachedEntities($model);
        });
    }

    /**
     * Save the meta
     */
    protected function updateAttachedEntities($model)
    {
        dispatch(new SaveEntityLinkBackwardsCommand($model));
    }
}
