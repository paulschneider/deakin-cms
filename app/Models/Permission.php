<?php namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];
}
