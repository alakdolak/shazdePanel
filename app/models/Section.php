<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Section'
 *
 * @property integer $id
 * @property string $name
 * @property integer $top_
 * @property integer $right_
 * @property integer $left_
 * @property integer $width
 * @property integer $height
 * @property integer $bottom_
 * @property integer $mobileTop
 * @property integer $mobileRight
 * @property integer $mobileLeft
 * @property integer $mobileBottom
 * @property boolean $mobileHidden
 * @property boolean $backgroundSize
 * @method static \Illuminate\Database\Query\Builder|\App\models\Section whereName($value)
 */

class Section extends Model {

    protected $table = 'section';
    public $timestamps = false;

    public static function whereId($value) {
        return Section::find($value);
    }
}
