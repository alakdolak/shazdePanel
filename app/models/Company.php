<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Company'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\Company whereName($value)
 */

class Company extends Model {

    protected $table = 'company';
    public $timestamps = false;

    public static function whereId($value) {
        return Company::find($value);
    }
}
