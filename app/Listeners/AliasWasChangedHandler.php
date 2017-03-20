<?php namespace App\Listeners;

use App\Models\Alias;
use App\Events\AliasWasChanged;
use App\Repositories\AliasRepository;

class AliasWasChangedHandler
{
    protected $alias;

    public function __construct(AliasRepository $alias)
    {
        $this->alias = $alias;
    }

    /**
     * Handle the event.
     *
     * This even has a quirk of firing twice on backwards references.
     * Its done this way so a URL can be changed from either an entity or from a link entity.
     *
     * @param  RegisterFilter $event
     * @return void
     */
    public function handle(AliasWasChanged $event)
    {
        // Construct an Alias.
        $alias                 = new Alias;
        $alias->aliasable_type = get_class($event->entity);
        $alias->aliasable_id   = $event->entity->id;
        $alias->alias          = $event->from;

        // Check if it exists.
        $query = $this->alias->query();
        $query->where('aliasable_type', '=', $alias->aliasable_type);
        $query->where('aliasable_id', '=', $alias->aliasable_id);
        $query->where('alias', '=', $event->from);

        $count = $query->count();

        // Remove duplicates.
        if (isset($event->entity->slug)) {
            Alias::where('alias', '=', $event->to)
                ->where('aliasable_type', '=', $alias->aliasable_type)
                ->delete();
        }

        // Doesnt exist? Create it.
        if (!$count) {
            $alias->save();
        }

        $this->alias->clearCache();
    }
}
