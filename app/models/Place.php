<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Place'
 *
 * @property integer $id
 * @property string $name
 * @property boolean $visibility
 * @method static \Illuminate\Database\Query\Builder|\App\models\Place whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Place whereVisibility($value)
 */

class Place extends Model {

    protected $table = 'place';
    public $timestamps = false;


    public static function whereId($value) {
        return Place::find($value);
    }
}
