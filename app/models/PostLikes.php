<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'PostLikes'
 *
 * @property integer $id
 * @property integer $postId
 * @property boolean $dislike
 * @method static \Illuminate\Database\Query\Builder|\App\models\PostLikes wherePostId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\PostLikes whereDislike($value)
 */

class PostLikes extends Model {
    
    protected $table = 'postLike';
    public $timestamps = false;

    public static function whereId($value) {
        return PostLikes::find($value);
    }
}
