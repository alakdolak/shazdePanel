<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'FavoritePost'
 *
 * @property integer $id
 * @property integer $postId
 * @method static \Illuminate\Database\Query\Builder|\App\models\FavoritePost wherePostId($value)
 */

class FavoritePost extends Model {

    protected $table = 'favoritePost';
    public $timestamps = false;

    public static function whereId($id) {
        return FavoritePost::find($id);
    }
}
