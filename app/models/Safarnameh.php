<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Safarnameh extends Model
{
    protected $guarded = [];
    protected $table = 'safarnameh';


    public function getTags(){
        return $this->belongsToMany(SafarnamehTags::class, 'safarnamehTagRelations', 'safarnamehId', 'tagId');
    }
}
