<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoComments';
}
