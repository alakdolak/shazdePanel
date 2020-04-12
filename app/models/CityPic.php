<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CityPic extends Model
{
    protected $table = 'cityPics';
    public $timestamps = false;

    public static function deleteWithPic($id){
        $pic = CityPic::find($id);
        if($pic == null)
            return false;
        else{
            try {
                $location = __DIR__ . '/../../../assets/_images/city/' . $pic->cityId . '/' . $pic->pic;
                if(is_file($location))
                    unlink($location);
                $pic->delete();
                return true;
            }
            catch (\Exception $exception){
                return false;
            }
        }

    }
}
