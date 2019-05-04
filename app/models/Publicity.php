<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Publicity'
 *
 * @property integer $id
 * @property integer $companyId
 * @property string $url
 * @property string $pic
 * @property string $from_
 * @property string $to_
 * @method static \Illuminate\Database\Query\Builder|\App\models\Publicity whereCompanyId($value)
 */

class Publicity extends Model {

    protected $table = 'publicity';
    public $timestamps = false;

    public static function whereId($target) {
        return Publicity::find($target);
    }

}