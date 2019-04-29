<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Restaurant'
 *
 * @property integer $id
 * @property string $name
 * @property integer $cityId
 * @method static \Illuminate\Database\Query\Builder|\App\models\Restaurant whereCityId($value)
 */

class Restaurant extends Model {

    protected $table = 'restaurant';
    public $timestamps = false;

    public static function whereId($value) {
        return Restaurant::find($value);
    }
}
