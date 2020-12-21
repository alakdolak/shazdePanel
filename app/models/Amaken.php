<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Amaken'
 *
 * @property integer $id
 * @property integer $cityId
 * @property string $name
 * @property string $address
 * @property string $site
 * @property string $phone
 * @property string $description
 *
 * @property boolean $markaz
 * @property boolean $hoome
 * @property boolean $shologh
 * @property boolean $khalvat
 * @property boolean $tabiat
 * @property boolean $kooh
 * @property boolean $darya
 * @property boolean $class
 *
 * @property string $file
 *
 * @property boolean $pic_1
 * @property boolean $pic_2
 * @property boolean $pic_3
 * @property boolean $pic_4
 * @property boolean $pic_5
 *
 * @property float $C
 * @property float $D
 *
 * @property string $meta
 * @property string $alt1
 * @property string $alt2
 * @property string $alt3
 * @property string $alt4
 * @property string $alt5
 *
 * @property string $tag1
 * @property string $tag2
 * @property string $tag3
 * @property string $tag4
 * @property string $tag5
 * @property string $tag6
 * @property string $tag7
 * @property string $tag8
 * @property string $tag9
 * @property string $tag10
 * @property string $tag11
 * @property string $tag12
 * @property string $tag13
 * @property string $tag14
 * @property string $tag15
 *
 * @property string $keyword
 * @property string $h1
 *
 * @property boolean $authorized
 * @property integer $author
 *
 * @method static \Illuminate\Database\Query\Builder|\App\models\Amaken whereCityId($value)
 */

class Amaken extends Model {

    protected $guarded = [];
    protected $table = 'amaken';

    public static function whereId($value) {
        return Amaken::find($value);
    }

    public static function fullDelete($id)
    {
        return;
        $kindPlaceId = 1;
        $kindPlace = Place::find($kindPlaceId);
        $place = Amaken::find($id);
        if($place != null) {
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
                deletePlacePicFiles($location, $place->picNumber);
            }

            $place->delete();
            return true;
        }
        return false;
    }
}
