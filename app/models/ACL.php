<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'ACL'
 *
 * @property integer $id
 * @property integer $userId
 * @property boolean $comment
 * @property boolean $post
 * @property boolean $seo
 * @property boolean $alt
 * @property boolean $content
 * @property boolean $config
 * @property boolean $offCode
 * @property boolean $publicity
 * @method static \Illuminate\Database\Query\Builder|\App\models\ACL whereUserId($value)
 */

class ACL extends Model {

    protected $table = 'acl';
    public $timestamps = false;

    protected $connection = 'mysql2';

    public static function whereId($value) {
        return ACL::find($value);
    }
}
