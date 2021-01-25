<?php

namespace App\models\News;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = [];
    protected $table = 'news';

    public function getTags()
    {
        return $this->belongsToMany(NewsTags::class, 'newsTagsRelations', 'newsId', 'tagId');
    }
}
