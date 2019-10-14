<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Comment'
 *
 * @property integer $id
 * @property integer $logId
 * @property integer $placeStyleId
 * @property string $src
 * @property integer $seasonTrip
 * @property boolean $status
 * @method static \Illuminate\Database\Query\Builder|\App\models\Comment whereLogId($value)
 */

class Comment extends Model {

    protected $table = 'comment';
    public $timestamps = false;
}
