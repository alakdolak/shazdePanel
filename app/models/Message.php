<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Message'
 *
 * @property integer $id
 * @property integer $senderId
 * @property string $message
 * @property string $subject
 * @property string $date
 * @property integer $receiverId
 * @property boolean $seenSender
 * @property boolean $seenReceiver
 * @method static \Illuminate\Database\Query\Builder|\App\models\Message whereSeenSender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Message whereSeenReceiver($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Message whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\models\Message whereReceiverId($value)
 */

class Message extends Model {

    protected $table = 'messages';
    public $timestamps = false;

    public static function whereId($target) {
        return Message::find($target);
    }
}
