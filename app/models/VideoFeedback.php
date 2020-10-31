<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoFeedback extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoFeedbacks';
}
