<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'imageBackUp'
 *
 * @property integer $id
 * @property integer $total
 * @property integer $done
 * @property boolean $flag
 */

class ImageBackUp extends Model {

    protected $table = 'imageBackUp';
    public $timestamps = false;

    protected $connection = 'mysql2';

    public static function whereId($value) {
        return ImageBackUp::find($value);
    }
}
