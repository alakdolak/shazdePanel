<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Majara'
 *
 * @property integer $id
 * @property string $name
 * @property integer $cityId
 * @method static \Illuminate\Database\Query\Builder|\App\models\Majara whereCityId($value)
 */

class Majara extends Model {

    protected $table = 'majara';
    public $timestamps = false;


    public static function whereId($value) {
        return Majara::find($value);
    }
}
