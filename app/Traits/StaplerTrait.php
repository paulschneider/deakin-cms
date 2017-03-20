<?php namespace App\Traits;

/*
 * Used to fix some issues with L4 boot conditions.
 *
 */

trait StaplerTrait
{
    use \Codesleeve\Stapler\ORM\EloquentTrait;
    public static function bootStaplerTrait()
    {
        static::bootStapler();
    }
}
