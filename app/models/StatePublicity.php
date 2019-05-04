<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'StatePublicity'
 *
 * @property integer $id
 * @property integer $stateId
 * @property integer $publicityId
 * @method static \Illuminate\Database\Query\Builder|\App\models\StatePublicity whereStateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\StatePublicity wherePublicityId($value)
 */

class StatePublicity extends Model {

    protected $table = 'statePublicity';
    public $timestamps = false;

    public static function whereId($target) {
        return StatePublicity::find($target);
    }

}
