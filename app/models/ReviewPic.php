<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ReviewPic extends Model
{
    protected $table = 'reviewPics';

    public static function deleteWithPic($id, $kindPlaceId, $placeId){
        $pic = ReviewPic::find($id);
        if($pic != null) {
            $kindPlace = Place::find($kindPlaceId);
            $place = \DB::table($kindPlace->tableName)->find($placeId);

            if ($kindPlace != null && $place != null) {
                $location = __DIR__ . '/../../../assets/userPhoto/' . $kindPlace->fileName . '/' . $place->file . '/' . $pic->pic;
                if (file_exists($location))
                    unlink($location);

                $pic->delete();
                return true;
            }
        }

        return false;
    }
}
