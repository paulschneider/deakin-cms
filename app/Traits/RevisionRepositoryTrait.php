<?php
namespace App\Traits;

use Auth;
use Laravel\Scout\ModelObserver;

trait RevisionRepositoryTrait
{
    protected $revisionRepository = null;

    /**
     * Get/Set the revision repository.
     * @param  string $repo        The repository to load, full path required.
     * @return Mixed  repository
     */
    public function getRevisionRepository()
    {
        if ($this->revisionRepository) {
            return $this->revisionRepository;
        }

        return app()->make($this->revisionRepository());
    }

    /**
     * Save a revision.
     *
     * @param  Request  $request
     * @return Entity
     */
    public function saveRevision(&$request, $entity_id = null, $options = [], $revision_id = null)
    {
        $tableName = $this->model->getTable();
        $status    = $request->get('status');

        // Temp disable the search engine and run at bottom of method.
        if (method_exists($this->model, 'searchable')) {
            ModelObserver::disableSyncingFor(get_class($this->model));
        }

        // Save the entity parent
        $entity = $this->save($request, $entity_id, $options);

        $oldRevision = false;

        if ($revision_id) {
            $oldRevision   = $entity->revisions()->where('id', '=', $revision_id)->first();
            $oldWithExtras = clone $oldRevision->entity->appendExtras();
        }

        // Set some info for the revision content.
        $request->merge([
            'entity_id' => $entity->id,
            'user_id'   => Auth::user()->id,
            'user_ip'   => $request->ip(),
        ]);

        // Force a new revision if status = current.
        if ($status == config('revision.status_current')) {
            $revision_id = null;
        }

        // Force a new revision if the status has changed.
        if ($revision_id && $oldRevision && $oldRevision->status != $status) {
            $revision_id = null;
        }

        // Save / Update the revision.
        $options['old_revision'] = $oldRevision;

        $newRevision = $this->getRevisionRepository()->save($request, $revision_id, $options);

        // Publish this revision.
        if (empty($entity->revision_id) || $status == config('revision.status_current')) {
            // Sneak this into the DB avoiding the ORM triggering a double save.
            \DB::table($tableName)->where('id', '=', $entity->id)->update(['revision_id' => $newRevision->id]);

            // Change old current to archive.
            \DB::table(str_singular($tableName) . '_revisions')
                ->where('entity_id', '=', $entity->id)
                ->where('id', '!=', $newRevision->id)
                ->where('status', '=', config('revision.status_current'))
                ->update(['status' => config('revision.status_archive')]);
        }

        // Set this revision for return redirect for controller update methods.
        $entity->revision_id = $newRevision->id;

        if ($oldRevision && $status == config('revision.status_current')) {
            $new = $newRevision->entity->appendExtras();
            $old = $oldWithExtras;

            $changed = $this->isRevisionChanged($old, $new);

            if (!$changed) {
                // If no change TODO
                $oldRevision->delete();
            }
        }

        // Dispatch the schedule trait.
        if ($entity instanceof \App\Contracts\ScheduleInterface) {
            $entity->revision ?: $entity->load('revision');
            $entity->saveEntitySchedule($entity);
        }

        // Dispatch to the search engine to update.
        if (method_exists($this->model, 'searchable')) {
            ModelObserver::enableSyncingFor(get_class($this->model));
            $entity->online ? $entity->searchable() : $entity->unsearchable();
        }

        return $entity;
    }

    /**
     * Grab all the items that have a draft or current revision.
     * @param  array   $options
     * @return query
     */
    public function paginateExceptPreview($perPage = 15, $options = [], $columns = ['*'])
    {
        $query = $this->query($options);

        $query->whereHas('revisions', function ($q) {
            $q->where('status', '!=', 'preview');
        });

        return $this->fetch($query, 'paginate', $options, ['perPage' => $perPage, 'columns' => $columns]);
    }

    /**
     * Compare if the revision has changed.
     * @param  object    $old A revision Entity
     * @param  object    $new A revision Entity
     * @return boolean
     */
    protected function isRevisionChanged($old, $new, $depth = 0)
    {
        ++$depth;

        if ($depth > 2) {
            // Possibly getting into a relationship of a relationship here.
            // This is unnecessary and will cause recursive aborting.
            // Log::debug('ABORTING DEPTH', ['old' => $old]);
            return null;
        }

        $relations = $old->getRelations();
        $fillables = $old->getFillable();

        $ignore = ['created_at', 'deleted_at', 'modified_at', 'revision_id'];

        if (isset($old->revisionIgnore)) {
            $ignore = array_merge($ignore, $old->revisionIgnore);
        }

        foreach ($fillables as $fillable) {
            if (in_array($fillable, $ignore)) {
                continue;
            }

            if ($old->getAttribute($fillable) != $new->getAttribute($fillable)) {
                return true;
            }
        }

        foreach ($relations as $method => $relation) {
            // Its a single item.
            if (method_exists($relation, 'getRelations') && method_exists($relation, 'getFillable')) {
                if ($this->isRevisionChanged($old->{$method}, $new->{$method}, $depth)) {
                    return true;
                }

                // Its a collection
            } elseif (get_class($old->{$method}) == 'Illuminate\Database\Eloquent\Collection') {
                // Check the old model for diff
                foreach ($old->{$method} as $delta => $value) {
                    if (!isset($new->{$method}[$delta]) || $this->isRevisionChanged($old->{$method}[$delta], $new->{$method}[$delta])) {
                        return true;
                    }
                }

                // Check the new model for diff
                foreach ($new->{$method} as $delta => $value) {
                    if (!isset($old->{$method}[$delta]) || $this->isRevisionChanged($old->{$method}[$delta], $new->{$method}[$delta])) {
                        return true;
                    }
                }

                // Its missing or changed type
            } elseif (get_class($old->{$method}) != get_class($new->{$method})) {
                return true;
            }
        }

        return false;
    }
}
