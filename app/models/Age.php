<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Age'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\Age whereName($value)
 */

class Age extends Model {

    protected $table = 'ages';
    public $timestamps = false;
}
