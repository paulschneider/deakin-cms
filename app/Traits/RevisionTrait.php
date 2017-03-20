<?php
namespace App\Traits;

trait RevisionTrait
{
    protected $preview_id = null;

    /**
     * Check if the revision last submitted is a preview.
     * @return boolean
     */
    public function getPreview()
    {
        $last = $this->last_revision();

        if ($last->status == 'preview') {
            return $last;
        }

        return null;
    }

    /**
     * Relationship to Revisions
     *
     * @return Revisions
     */
    public function revision()
    {
        $name = get_class($this) . 'Revision';

        return $this->belongsTo($name, 'revision_id');
    }

    /**
     * Relationship to Revisions
     *
     * @return Revisions
     */
    public function revisions()
    {
        $name = get_class($this) . 'Revision';

        return $this->hasMany($name, 'entity_id')->orderBy('id', 'desc');
    }

    /**
     * Relationship to Revisions
     *
     * @return Revisions
     */
    public function last_revision()
    {
        $name = get_class($this) . 'Revision';

        return $this->hasMany($name, 'entity_id')->orderBy('id', 'desc')->first();
    }

    /**
     * Helper function to check if it has revisions.
     * @return boolean
     */
    public function hasRevisions()
    {
        return (bool) $this->revision_id;
    }

    /**
     * Change the actove revision
     * @param int $revision_id
     */
    public function setRevision($revision_id = null)
    {
        if ($revision_id) {
            $this->revision_id = $revision_id;
        }

        return $this;
    }

    /**
     * Map revision attributes back to the parent entity.
     * Forms read direct into attributes array.
     * @return void
     */
    public function appendRevision()
    {
        $existing = $this->getAttributes();
        $attribs  = $this->revision->getAttributes();

        foreach ($attribs as $attr => $value) {
            if (!array_key_exists($attr, $existing)) {
                $this->setAttribute($attr, $value);
            }
        }
    }
}
