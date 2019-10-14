<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;


/**
 * An Eloquent Model: 'UserOpinion'
 *
 * @property integer $id
 * @property integer $logId
 * @property integer $rate
 * @property integer $opinionId
 */

class TripStyle extends Model {

    protected $table = 'tripStyle';
    public $timestamps = false;

    public static function whereId($target) {
        return TripStyle::find($target);
    }
}
