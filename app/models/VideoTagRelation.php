<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoTagRelation extends Model
{
    protected $connection = 'koochitaTv';
    protected $table = 'videoTagRelations';
    public $timestamps = false;
}
