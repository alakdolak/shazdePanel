<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Backup'
 *
 * @property integer $id
 * @property string $url
 * @property string $username
 * @property string $password
 * @property integer $_interval_
 * @property boolean $mysql
 */

class Backup extends Model {

    protected $table = 'backup';
    public $timestamps = false;

    protected $connection = 'mysql2';

    public static function whereId($value) {
        return Backup::find($value);
    }
}
