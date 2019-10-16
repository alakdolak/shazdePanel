<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Tag'
 *
 * @property integer $id
 * @property string $name
 * @property integer $kindPlaceId
 * @method static \Illuminate\Database\Query\Builder|\App\models\Tag whereKindPlaceId($value)
 */

class Tag extends Model {

    protected $table = 'tag';
    public $timestamps = false;
}
