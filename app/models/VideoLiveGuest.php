<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoLiveGuest extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoLiveGuests';
    public $timestamps = false;
}
