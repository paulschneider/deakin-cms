<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];
}
