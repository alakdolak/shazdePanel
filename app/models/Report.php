<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Report'
 *
 * @property integer $id
 * @property integer $logId
 * @property integer $reportKind
 * @method static \Illuminate\Database\Query\Builder|\App\models\Report whereLogId($value)
 */

class Report extends Model {

    protected $table = 'reports';
    public $timestamps = false;

    public static function whereId($target) {
        return Report::find($target);
    }

}
