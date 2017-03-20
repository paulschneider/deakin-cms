<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    /**
     * The table name
     * @var string
     */
    protected $table = 'variables';

    /**
     * Which fields are fillable
     * @var array
     */
    protected $fillable = ['name', 'value'];
}
