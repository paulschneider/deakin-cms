<?php

namespace App\Repositories;

class MetaRepository extends BasicRepository
{
    /**
     * Set the boolean fields
     *
     * @var array
     */
    protected $booleans = [];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Meta';
    }

    /**
     * Update an entity with its meta data
     *
     * @param array          $data    The data array
     * @param Eloquent\Model &$entity The entity
     */
    public function updateForEntity($data = [], &$entity)
    {
        $metable_type = get_class($entity);
        // Get the allowed meta data
        $data = array_only($data, $entity->getAllowedMeta());
        // Get the existing meta
        $metas = $entity->getMeta();
        // Get the defaults
        $defaults = $entity->getDefaultMeta();
        // Get the meta attachments
        $attachments = $entity->getAttachmentMeta();
        // Get the defaults
        $booleans = $entity->getBooleanMeta();

        // Inject booleans as empty if they dont exist in the data.
        foreach ($booleans as $bool) {
            if (!array_key_exists($bool, $data)) {
                $data[$bool] = 0;
            }
        }

        foreach ($data as $key => $value) {
            $existing = null;

            // See if it exists
            if (isset($metas[$key])) {
                $existing = $metas[$key]->id;
            }

            // Set the default if it exists
            if (empty($value) && !empty($defaults[$key])) {
                $value = $defaults[$key];
            }

            if (!empty($value)) {
                // Let's create or update it
                $attachment_id = null;
                if (in_array($key, $attachments)) {
                    $attachment_id = $value;
                }

                $item = [
                    'metable_type'  => $metable_type,
                    'metable_id'    => $entity->id,
                    'key'           => $key,
                    'value'         => (is_string($value) ? $value : serialize($value)),
                    'created_at'    => new \DateTime(),
                    'updated_at'    => new \DateTime(),
                    'attachment_id' => $attachment_id,
                ];
                // Save the item
                $this->saveWithData($item, $existing);
            } elseif ($existing) {
                // It's empty, so let's remove it
                $this->delete($existing);
            }
        }
    }
}
