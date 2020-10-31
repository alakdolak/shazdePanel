<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoCategories';
    public $timestamps = false;
}
