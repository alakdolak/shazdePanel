<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videos';
}
