<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoLiveChats extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoLiveChats';
    public $timestamps = false;
}
