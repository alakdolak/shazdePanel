<?php

namespace App\models\News;

use Illuminate\Database\Eloquent\Model;

class NewsTags extends Model
{
    protected $guarded = [];
    protected $table = 'newsTags';
    public $timestamps = false;
}
