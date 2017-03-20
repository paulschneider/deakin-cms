<?php
namespace App\Traits;

use App\Models\Meta;
use App\Jobs\SaveEntityLinkCommand;
use Illuminate\Support\Facades\Request;

trait EntityLinkTrait
{
    public static function bootEntityLinkTrait()
    {
        static::saving(function ($model) {
            $model->saveEntityLink($model);
        });
    }

    /**
     * The relationship to the link_id
     *
     * @return Eloquent\Relationship
     */
    public function link()
    {
        return $this->belongsTo('App\Models\MenuLink');
    }

    /**
     * Save the meta
     */
    protected function saveEntityLink($model)
    {
        $data = Request::only('menu');

        // Remove the menu form the input.
        Request::merge(['menu' => []]);

        dispatch(new SaveEntityLinkCommand($data['menu'], $model));
    }

    public function setShortSlug()
    {
        $slug = explode('/', $this->slug);
        $this->setAttribute('slug', end($slug));

        return $this;
    }

    public function findBySlug($slug, $options = [])
    {
        $fillable = $this->getFillable();

        if (isset($options['filter']) && $options['filter'] === false) {
            return $this->where('slug', '=', $slug)->first();
        }

        if (in_array('online', $fillable)) {
            return $this->where('online', '=', 1)->where('slug', '=', $slug)->first();
        }

        return $this->where('slug', '=', $slug)->first();
    }
}
