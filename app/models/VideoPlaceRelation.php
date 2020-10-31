<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoPlaceRelation extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoPlaceRelations';
    public $timestamps = false;
}
