<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'PostComment'
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $postId
 * @property integer $supervisorId
 * @property string $msg
 * @property boolean $status
 * @method static \Illuminate\Database\Query\Builder|\App\models\PostComment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\PostComment wherePostId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\PostComment whereStatus($value)
 */

class PostComment extends Model {
    
    protected $table = 'postComment';

    public static function whereId($value) {
        return PostComment::find($value);
    }
}
