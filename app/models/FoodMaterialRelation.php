<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodMaterialRelation extends Model
{
    protected $guarded = [];
    protected $table = 'foodMaterialRelations';
    public $timestamps = false;
}
