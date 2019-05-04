<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Post'
 *
 * @property integer $id
 * @property integer $creator
 * @property boolean $pic_1
 * @property boolean $pic_2
 * @property boolean $pic_3
 * @property boolean $pic_4
 * @property boolean $pic_5
 * @property string $title
 * @property string $tag
 * @property string $color
 * @property string $tagColor
 * @property string $backColor
 * @method static \Illuminate\Database\Query\Builder|\App\models\Post whereCreator($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Activity whereVisibility($value)
 */

class Post extends Model {

    protected $table = 'post';

    public static function whereId($value) {
        return Post::find($value);
    }
}
