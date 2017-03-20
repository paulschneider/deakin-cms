<?php namespace App\Traits;

use App\Jobs\SaveRelatedCommand;
use Illuminate\Support\Facades\Request;

trait RelatedTrait
{
    public static function bootRelatedTrait()
    {
        static::saved(function ($model) {
            $model->saveRelated($model);
        });
    }

    /**
     * Save the meta
     */
    protected function saveRelated($model)
    {
        $data = Request::all();

        dispatch(new SaveRelatedCommand($data, $model));
    }

    /**
     * Relationship to Related
     *
     * @return Related
     */
    public function related_links()
    {
        return $this->morphMany('App\Models\Related', 'related')->with('icon')->orderBy('weight', 'ASC');
    }
}
