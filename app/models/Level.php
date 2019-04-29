<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Level'
 *
 * @property integer $id
 * @property string $name
 * @property integer $floor
 * @method static \Illuminate\Database\Query\Builder|\App\models\Level whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Level whereFloor($value)
 */

class Level extends Model {

    protected $table = 'level';
    public $timestamps = false;

    public static function whereId($target) {
        return Level::find($target);
    }

}
