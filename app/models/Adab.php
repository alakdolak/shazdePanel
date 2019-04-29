<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Adab'
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $category
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\Adab whereStateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Adab whereCategory($value)
 */

class Adab extends Model {

    protected $table = 'adab';
    public $timestamps = false;
    
    public static function whereId($value) {
        return Adab::find($value);
    }
}
