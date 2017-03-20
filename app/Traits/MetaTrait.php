<?php namespace App\Traits;

use Attachment;
use App\Models\Meta;
use App\Jobs\SaveMetaCommand;
use Illuminate\Support\Facades\Request;

trait MetaTrait
{
    /**
     * Instance of MetaRepository
     *
     * @var MetaRepository
     */
    protected $metaRepository;

    /**
     * Boot the MetaTrait and register events
     */
    public static function bootMetaTrait()
    {
        static::saved(function ($model) {
            $model->saveMeta($model);
        });

        static::deleting(function ($model) {
            $model->metaDelete($model);
        });
    }

    /**
     * Get the MetaRepository
     *
     * @return App\Repositories\MetaRepository
     */
    protected function getMetaRepository()
    {
        // Lazy instantiation
        if (empty($this->metaRepository)) {
            $this->metaRepository = app()->make('App\Repositories\MetaRepository');
        }

        return $this->metaRepository;
    }

    /**
     * The relationship to the meta
     *
     * @return Eloquent\Relationship
     */
    public function meta()
    {
        return $this->morphMany('App\Models\Meta', 'metable');
    }

    /**
     * Append the meta data to the model
     *
     * @param  array   $options The options array
     * @return array
     */
    public function appendMeta($options = [])
    {
        $meta             = $this->meta;
        $allowed          = $this->getAllowedMeta();
        $attachments      = $this->getAttachmentMeta();
        $checkAttachments = function ($item) use ($attachments, $options) {
            if (in_array($item->key, $attachments)) {
                // Get the attachment
                $attachment = Attachment::getAttachment($item->value);

                if (!empty($options['attachments']) && array_key_exists($item->key, $options['attachments'])) {
                    $attachment = $attachment->file->url($item->key);
                }

                return $attachment;
            }

            return $item->value;
        };

        foreach ($meta as $item) {
            if (($key = array_search($item->key, $allowed)) !== false) {
                unset($allowed[$key]);
            }

            if (is_serialized($item->value)) {
                $this->setAttribute($item->key, unserialize($item->value));
            } else {
                $value = $checkAttachments($item);
                $this->setAttribute($item->key, $value);
            }
        }
        // Set default to null
        array_map(function ($item) {
            return $this->setAttribute($item, null);
        }, $allowed);
        return $this;
    }

    /**
     * Get the meta as an array
     *
     * @return array
     */
    public function getMeta()
    {
        $meta         = $this->meta;
        $consolidated = [];

        foreach ($meta as $item) {
            $consolidated[$item->key] = $item;
        }

        return $consolidated;
    }

    /**
     * Get the allowed meta
     *
     * @return array
     */
    public function getAllowedMeta()
    {
        if (empty($this->allowedMeta)) {
            return [];
        }

        return $this->allowedMeta;
    }

    /**
     * Get the attachment meta
     *
     * @return array
     */
    public function getAttachmentMeta()
    {
        if (empty($this->attachmentMeta)) {
            return [];
        }

        return $this->attachmentMeta;
    }

    /**
     * The concrete class can set defaults if needed
     *
     * @return array
     */
    public function getDefaultMeta()
    {
        return [];
    }

    /**
     * Get the boolean meta to set to 0
     *
     * @return array
     */
    public function getBooleanMeta()
    {
        if (empty($this->booleanMeta)) {
            return [];
        }

        return $this->booleanMeta;
    }

    /**
     * Save the meta
     */
    public function saveMeta($model, $data = [])
    {
        if (empty($data)) {
            foreach ($this->getAllowedMeta() as $key) {
                if (Request::exists($key)) {
                    $data[$key] = Request::get($key);
                    Request::merge([$key => null]);
                }
            }
        }

        dispatch(new SaveMetaCommand($data, $model));
    }

    /**
     * Delete the meta entities
     *
     * @param mixed $model The entity being deleted
     */
    protected function metaDelete($model)
    {
        $repository = $this->getMetaRepository();
        $query      = $repository->query();

        $query->where('metable_type', '=', get_class($model))
              ->where('metable_id', '=', $model->id)
              ->delete();
    }
}
