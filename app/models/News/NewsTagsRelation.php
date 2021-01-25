<?php

namespace App\models\News;

use Illuminate\Database\Eloquent\Model;

class NewsTagsRelation extends Model
{
    protected $guarded = [];
    protected $table = 'newsTagsRelations';
    public $timestamps = false;
}
