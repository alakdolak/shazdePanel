<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Post'
 *
 * @property integer $id
 * @property integer $creator
 * @property string $pic
 * @property string $alt
 * @property string $title
 * @property string $color
 * @property string $categoryColor
 * @property string $backColor
 * @property string $category
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 * @property integer $placeId
 * @property integer $kindPlaceId
 * @property string $date
 * @property string $time
 * @property string $presentationFinish
 * @property string $meta
 * @property string $keyword
 * @property string $h1
 * @property string $tag1
 * @property string $tag2
 * @property string $tag3
 * @property string $tag4
 * @property string $tag5
 * @property string $tag6
 * @property string $tag7
 * @property string $tag9
 * @property string $tag10
 * @property string $tag11
 * @property string $tag12
 * @property string $tag13
 * @property string $tag14
 * @property string $tag15
 * @property string $C
 * @property string $D
 * @method static \Illuminate\Database\Query\Builder|\App\models\Post whereCreator($value)
 */

class Post extends Model {
    
    protected $table = 'post';

    public static function whereId($value) {
        return Post::find($value);
    }
}
