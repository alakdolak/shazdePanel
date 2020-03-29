<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Majara'
 *
 * @property integer $id
 * @property string $name
 * @property integer $cityId
 * @method static \Illuminate\Database\Query\Builder|\App\models\Majara whereCityId($value)
 */

class Majara extends Model {

    protected $table = 'majara';
    public $timestamps = false;


    public static function whereId($value) {
        return Majara::find($value);
    }

    public static function fullDelete($id)
    {
        $kindPlaceId = 6;
        $kindPlace = Place::find($kindPlaceId);
        $place = Majara::find($id);
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
