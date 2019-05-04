<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;


/**
 * An Eloquent Model: 'PicItem'
 *
 * @property integer $id
 * @property string $name
 * @property integer $kindPlaceId
 * @method static \Illuminate\Database\Query\Builder|\App\models\PicItem whereKindPlaceId($value)
 */

class PicItem extends Model {

    protected $table = 'picItems';
    public $timestamps = false;

    public static function whereId($target) {
        return PicItem::find($target);
    }
}
