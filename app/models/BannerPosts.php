<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'BannerPosts'
 *
 * @property integer $id
 * @property integer $postId
 * @method static \Illuminate\Database\Query\Builder|\App\models\BannerPosts wherePostId($value)
 */

class BannerPosts extends Model {

    protected $table = 'bannerPosts';
    public $timestamps = false;

    public static function whereId($id) {
        return BannerPosts::find($id);
    }
}
