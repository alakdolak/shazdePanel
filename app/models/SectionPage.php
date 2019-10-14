<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'SectionPage'
 *
 * @property integer $id
 * @property integer $sectionId
 * @property integer $page
 * @method static \Illuminate\Database\Query\Builder|\App\models\Section whereSectionId($value)
 */

class SectionPage extends Model {

    protected $table = 'sectionPage';
    public $timestamps = false;

    public static function whereId($value) {
        return SectionPage::find($value);
    }
}
