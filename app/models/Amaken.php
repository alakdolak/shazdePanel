<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Amaken'
 *
 * @property integer $id
 * @property integer $cityId
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\Amaken whereCityId($value)
 */

class Amaken extends Model {

    protected $table = 'amaken';
    public $timestamps = false;

    public static function whereId($value) {
        return Amaken::find($value);
    }
}
