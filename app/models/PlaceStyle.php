<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'PlaceStyle'
 *
 * @property integer $id
 * @property string $name
 * @property integer $kindPlaceId
 * @method static \Illuminate\Database\Query\Builder|\App\models\PlaceStyle whereKindPlaceId($value)
 */

class PlaceStyle extends Model {

    protected $table = 'placeStyle';
    public $timestamps = false;

    public static function whereId($target) {
        return PlaceStyle::find($target);
    }

}
