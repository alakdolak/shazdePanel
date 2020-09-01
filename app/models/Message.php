<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Message'
 *
 * @property integer $id
 * @property integer $senderId
 * @property integer $receiverId
 * @property boolean $seen
 */

class Message extends Model {

    protected $table = 'messages';
    public $timestamps = false;

    public static function whereId($target) {
        return Message::find($target);
    }
}
