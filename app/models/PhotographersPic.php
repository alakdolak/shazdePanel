<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PhotographersPic extends Model
{
    protected $table = 'photographersPics';

    public static function deleteWithPic($id){
        $photo = PhotographersPic::find($id);
        if($photo != null) {
            PhotographersLog::where('picId', $id)->delete();
            $kindPlace = Place::find($photo->kindPlaceId);
            if($kindPlace != null ){
                $place = \DB::table($kindPlace->tableName)->find($photo->placeId);
                if($place != null) {
                    $location = __DIR__ . '/../../../assets/userPhoto/' . $kindPlace->fileName . '/' . $place->file;

                    $check = deletePlacePicFiles($location, $photo->pic);
                    if ($check) {
                        $photo->delete();
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
