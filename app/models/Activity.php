<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Activity'
 *
 * @property integer $id
 * @property string $name
 * @property string $pic
 * @property string $actualName
 * @property int $rate
 * @property boolean $visibility
 * @property boolean $controllerNeed
 * @method static \Illuminate\Database\Query\Builder|\App\models\Activity whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Activity whereVisibility($value)
 */

class Activity extends Model {

    protected $table = 'activity';
    public $timestamps = false;

    public static function whereId($value) {
        return Activity::find($value);
    }
}
