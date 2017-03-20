<?php

namespace App\Models;

use Carbon;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'activation_code', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'activation_code'];

    /**
     * The attributes that should return as datetime objects from eloquent.
     *
     * @var array
     */
    protected $dates = ['activation_expires'];

    protected function getRoleNamesAttribute()
    {
        $output = [];

        foreach ($this->roles as $role) {
            $output[] = ucwords($role->name);
        }

        return implode(', ', $output);
    }

    protected function getValidActivationAttribute()
    {
        $now = Carbon::now();
        $min = $now->subMinutes(config('auth.passwords.users.expire'));

        if ($this->activation_expires && $this->activation_expires->gt($now) && $this->activation_expires->gt($min)) {
            return true;
        }

        return false;
    }
}
