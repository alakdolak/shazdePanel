<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoLive extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoLives';
}
