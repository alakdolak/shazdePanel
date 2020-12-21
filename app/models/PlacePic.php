<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PlacePic extends Model
{
    protected $guarded = [];
    protected $table = 'placePics';
    public $timestamps = false;

    public static function deleteWithPic($id){
        $pic = PlacePic::find($id);
        if($pic != null){
            $kindPlaceId = $pic->kindPlaceId;
            $placeId = $pic->placeId;

            $kindPlace = Place::find($kindPlaceId);
            if($kindPlace != null){
                $place = \DB::table($kindPlace->tableName)->find($placeId);
                if($place != null){
                    $location = __DIR__ . '/../../../assets/_images/' . $kindPlace->fileName . '/' . $place->file;

                    $check = deletePlacePicFiles($location, $pic->picNumber);
                    if($check) {
                        $pic->delete();
                        return true;
                    }

                }
            }

        }
        return false;
    }
}
