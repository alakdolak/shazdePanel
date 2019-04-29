<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Medal'
 *
 * @property integer $id
 * @property string $name
 * @property integer $activityId
 * @property integer $floor
 * @property string $pic_1
 * @property string $pic_2
 * @property integer $kindPlaceId
 * @method static \Illuminate\Database\Query\Builder|\App\models\Medal whereKindPlaceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Medal whereActivityId($value)
 */

class Medal extends Model {

    protected $table = 'medal';
    public $timestamps = false;

    public static function whereId($target) {
        return Medal::find($target);
    }

}
