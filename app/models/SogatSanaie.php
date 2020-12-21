<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class SogatSanaie extends Model
{
    protected $guarded = [];
    protected $table = 'sogatSanaies';

    public static function fullDelete($id)
    {
        return;
        $kindPlaceId = 10;
        $kindPlace = Place::find($kindPlaceId);
        $place = SogatSanaie::find($id);
        if($place != null && $kindPlace != null) {
            MainSuggestion::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->delete();
            $photos = PhotographersPic::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->get();
            foreach ($photos as $item)
                PhotographersPic::deleteWithPic($item->id);

            PlaceFeatureRelation::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->delete();
            PlaceTag::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->delete();
            PostPlaceRelation::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->delete();

            $logs = LogModel::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->get();
            foreach ($logs as $log)
                LogModel::deleteLog($log->id);

            $pics = PlacePic::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->get();
            foreach ($pics as $pic)
                PlacePic::deleteWithPic($pic->id);

            if ($kindPlace != null && $place != null) {
                $location = __DIR__ . '/../../../assets/_images/' . $kindPlace->fileName . '/' . $place->file;
                $check = deletePlacePicFiles($location, $place->picNumber);
            }

            $place->delete();
            return true;
        }
        return false;
    }

}
