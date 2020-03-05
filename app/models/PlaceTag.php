<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PlaceTag extends Model
{
    protected $table = 'placeTags';
    public $timestamps = false;

    public static function getTags($kindPlaceId, $placeId) {
        return PlaceTag::where('kindPlaceId', $kindPlaceId)->where('placeId', $placeId)->pluck('tag')->toArray();
    }
}
