<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'ReportsType'
 *
 * @property integer $id
 * @property integer $kindPlaceId
 * @property string $description
 * @method static \Illuminate\Database\Query\Builder|\App\models\ReportsType whereKindPlaceId($value)
 */

class ReportsType extends Model {

    protected $table = 'reportsType';
    public $timestamps = false;

    public static function whereId($target) {
        return ReportsType::find($target);
    }

}
