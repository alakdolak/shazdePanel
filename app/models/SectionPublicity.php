<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'SectionPublicity'
 *
 * @property integer $id
 * @property integer $publicityId
 * @property integer $sectionId
 * @property integer $part
 * @method static \Illuminate\Database\Query\Builder|\App\models\Publicity whereSectionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Publicity wherePublicityId($value)
 */

class SectionPublicity extends Model {

    protected $table = 'sectionPublicity';
    public $timestamps = false;

    public static function whereId($target) {
        return SectionPublicity::find($target);
    }

}