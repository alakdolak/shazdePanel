<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'LogModel'
 *
 * @property integer $id
 * @property integer $activityId
 * @property integer $visitorId
 * @property integer $relatedTo
 * @property string $text
 * @property string $date
 * @property string $subject
 * @property string $alt
 * @property boolean $seen
 * @property boolean $confirm
 * @method static \Illuminate\Database\Query\Builder|\App\models\LogModel whereRelatedTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\LogModel whereActivityId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\LogModel whereVisitorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\LogModel whereSeen($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\LogModel whereConfirm($value)
 */

class LogModel extends Model {

    protected $table = 'log';
    public $timestamps = false;

    public static function whereId($value) {
        return LogModel::find($value);
    }

}