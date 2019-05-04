<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Section'
 *
 * @property integer $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\models\Section whereName($value)
 */

class Section extends Model {

    protected $table = 'section';
    public $timestamps = false;

    public static function whereId($value) {
        return Section::find($value);
    }
}
