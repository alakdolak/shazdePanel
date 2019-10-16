<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Opinion'
 *
 * @property integer $id
 * @property string $name
 * @property integer $kindPlaceId
 * @method static \Illuminate\Database\Query\Builder|\App\models\Opinion whereKindPlaceId($value)
 */

class Opinion extends Model {

    protected $table = 'opinion';
    public $timestamps = false;
}
