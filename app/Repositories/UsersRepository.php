<?php

namespace App\Repositories;

use Mail;
use Carbon;

class UsersRepository extends BasicRepository
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
        return 'App\Models\User';
    }

    /**
     * Modify the input before validation
     *
     * @param  array   $data The data array
     * @return array
     */
    public function beforeSave(array $data, &$entity)
    {
        if (array_key_exists('deactivate', $data)) {
            if ($entity->active) {
                $entity->active = false;
            }
        }

        if (empty($entity->password)) {
            $data['password'] = bcrypt(str_random(20));
        }

        if (array_key_exists('password', $data)) {
            $data['password'] = bcrypt($data['password']);
        }

        return $data;
    }

    /**
     * Modify the input before validation
     *
     * @param  array   $data The data array
     * @return array
     */
    public function afterSave(array $data, $entity, $old)
    {
        if (array_key_exists('assign_roles', $data)) {
            if (!array_key_exists('user_roles', $data)) {
                $data['user_roles'] = [];
            }
            $entity->roles()->sync($data['user_roles']);
        }

        return $data;
    }

    /**
     * Set the activation flags on the user and send them an email.
     * @param User $user
     */
    public function setActivation($user)
    {
        $token = str_random(60);

        $user->activation_code    = $token;
        $user->activation_expires = Carbon::now()->addMinutes(config('auth.passwords.users.expire'));

        $user->save();

        Mail::send('emails.activate', ['token' => $token], function ($m) use ($user) {
            $m->subject('Account Activation');
            $m->to($user->email);
        });
    }
}
