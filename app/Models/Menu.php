<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['title', 'stub', 'online'];

    /**
     * Relationship to MenuLink
     * @return (MenuLink Collection) The links that blong to this vocabulary
     */
    public function links()
    {
        return $this->hasMany('App\Models\MenuLink')->orderBy('parent_id', 'ASC')->orderBy('weight', 'ASC');
    }

    /**
     * Scope to stop editors getting into menus they shouldnt be able to.
     * @param  Model    $query
     * @return $query
     */
    public function scopeEditable($query)
    {
        $user  = \Auth::user();
        $roles = $user->roles()->get();

        $permission_ids = [];

        foreach ($roles as $role) {
            $ids            = $role->perms()->where('name', 'like', 'menu.%')->pluck('id')->all();
            $permission_ids = array_merge($permission_ids, $ids);
        }

        $query->leftJoin('permissions', 'permissions.name', '=', \DB::raw('CONCAT("menu.", menus.stub)'));
        $query->select('menus.*');

        $query->where(function ($q) use ($permission_ids) {
            $q->whereIn('permissions.id', $permission_ids);
            $q->orWhereNull('permissions.id');
        });
    }
}
