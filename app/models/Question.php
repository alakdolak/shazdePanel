<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Question'
 *
 * @property integer $id
 * @property integer $kindPlaceId
 * @property integer $description
 * @method static \Illuminate\Database\Query\Builder|\App\models\Question whereKindPlaceId($value)
 */

class Question extends Model {

    protected $table = 'questions';
    public $timestamps = false;
}
