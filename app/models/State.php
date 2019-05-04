<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'State'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\State whereName($value)
 */

class State extends Model {

    protected $table = 'state';
    public $timestamps = false;


    public static function whereId($value) {
        return State::find($value);
    }
}
