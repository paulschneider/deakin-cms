<?php

namespace App\Repositories;

class AttachmentsRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Set the nullable fields
     *
     * @var array
     */
    protected $nullable = ['slug'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Attachment';
    }

    public function deleteAll()
    {
        $query = $this->model->newQuery();
        $query->delete();
    }

    public function getPathId($default = false)
    {
        if ($id = \Request::get('id', null)) {
            if (!preg_match('/^tree_([\d]+)$/', $id, $matches)) {
                $id = is_numeric($id) ? $id : null;
            } else {
                $id = $matches[1];
            }
        }

        if (empty($id) && $default) {
            $id = config('attachments.path.default');
        }

        return $id;
    }
}
