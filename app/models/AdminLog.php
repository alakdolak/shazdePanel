<?php

namespace App\models;
;
use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'AdminLog'
 *
 * @property integer $id
 * @property integer $uId
 * @property integer $mode
 * @property integer $additional1
 * @property integer $additional2
 * @property string $comment
 * @method static \Illuminate\Database\Query\Builder|\App\models\AdminLog whereMode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\AdminLog whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\AdminLog whereAdditional1($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\AdminLog whereAdditional2($value)
 */

class AdminLog extends Model {

    protected $table = 'adminLog';
    protected $connection = 'mysql2';
    
    public static function whereId($value) {
        return AdminLog::find($value);
    }
}
