<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'OffCode'
 *
 * @property integer $id
 * @property string $code
 * @property integer $amount
 * @property integer $creator
 * @property integer $uId
 * @property boolean $kind
 * @property boolean $used
 * @property string $expire
 * @method static \Illuminate\Database\Query\Builder|\App\models\OffCode whereUId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\OffCode whereCreator($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\OffCode whereCode($value)
 */

class OffCode extends Model {

    protected $table = 'offCode';

    public static function whereId($id) {
        return OffCode::find($id);
    }
}
