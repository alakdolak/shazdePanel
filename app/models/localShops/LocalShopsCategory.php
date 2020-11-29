<?php

namespace App\models\localShops;

use Illuminate\Database\Eloquent\Model;

class LocalShopsCategory extends Model
{
    protected $table = 'localShopsCategories';
    public $timestamps = false;

    public function getLocalShops()
    {
        return $this->hasMany(LocalShops::class, 'id', 'categoryId');
    }
}
