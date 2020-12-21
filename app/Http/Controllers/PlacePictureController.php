<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Boomgardy;
use App\models\Hotel;
use App\models\localShops\LocalShopsPictures;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\PlacePic;
use App\models\Restaurant;
use App\models\SogatSanaie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlacePictureController extends Controller
{
    public function uploadImgPage($kindPlaceId, $id){
        if($kindPlaceId != 13) {
            $kindPlace = Place::find($kindPlaceId);
            $place = DB::table($kindPlace->tableName)->select(['id', 'name', 'file', 'picNumber', 'alt', 'cityId'])->find($id);

            $place->pics = PlacePic::where('placeId', $place->id)->where('kindPlaceId', $kindPlaceId)->get();
            $place->mainPicF = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/f-'.$place->picNumber);
            $place->mainPicL = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/l-'.$place->picNumber);

            foreach ($place->pics as $pic){
                $pic->picF = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/f-'.$pic->picNumber);
                $pic->picL = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/l-'.$pic->picNumber);
            }

            $backUrl = url('editContent/' . $kindPlaceId . '/' . $place->id);

            return view('content.newContent.uploadImg', compact(['kindPlaceId', 'place', 'backUrl']));
        }
        else if($kindPlaceId == 13)
            return \redirect(route('localShop.edit.pics', ['id' => $id]));
    }

    public function changeAltPic(Request $request){
        if($request->kindPlaceId != 13)
            $pic = PlacePic::find($request->id);
        else
            $pic = LocalShopsPictures::find($request->id);

        if($pic != null){
            $pic->alt = $request->alt;
            $pic->save();

            return response('ok');
        }
        else
            return response('notFound');
    }

    public function setMainPic(Request $request){

        if($request->kindPlaceId != 13) {
            $pic = PlacePic::find($request->id);
            $kindPlace = Place::find($pic->kindPlaceId);
            if($pic != null && $kindPlace != null){
                $place = \DB::table($kindPlace->tableName)->find($pic->placeId);
                if($place != null){
                    $mainPicNum = $pic->picNumber;
                    $sidePic = $place->picNumber;
                    \DB::table($kindPlace->tableName)->where('id', $place->id)->update(['picNumber' => $mainPicNum]);

                    if($sidePic != null)
                        $pic->update(['picNumber' => $sidePic]);
                    else
                        $pic->delete();

                    return response('ok');
                }
                return response('error2');
            }
            return response('error1');
        }
        else {
            $pic = LocalShopsPictures::find($request->id);
            $lastMainPic = LocalShopsPictures::where('isMain', 1)->where('localShopId', $pic->localShopId)->first();
            if($lastMainPic != null){
                $lastMainPic->isMain = 0;
                $lastMainPic->save();
            }
            $pic->isMain = 1;
            $pic->save();
            return response('ok');
        }
    }

    public function storeImg(Request $request)
    {
        if(isset($request->placeId) && isset($request->kindPlaceId) && isset($request->kind)){
            $kindPlaceId = $request->kindPlaceId;
            $placeId = $request->placeId;
            $kindPlace = Place::find($kindPlaceId);
            $place = \DB::table($kindPlace->tableName)->find($placeId);

            if($place != null){

                $location = __DIR__ . '/../../../../assets/_images';

                if(!is_dir($location . '/'. $kindPlace->fileName))
                    mkdir($location . '/'. $kindPlace->fileName);
                $location .= '/' . $kindPlace->fileName;

                if ($place->file == null || $place->file == 'none' || $place->file == '') {
                    $newFileName = rand(1000000, 9999999);
                    while (file_exists($location . '/' . $newFileName))
                        $newFileName = (int)($newFileName - 1);

                    $location .= '/' . $newFileName;

                    mkdir($location);
                    if(is_dir($location))
                        \DB::table($kindPlace->tableName)->where('id', $placeId)->update(['file' => $newFileName]);
                    else
                        return response()->json(['status' => 'nok2']);
                }
                elseif(!is_dir($location . '/' . $place->file)){
                    mkdir($location . '/' . $place->file);
                    $location .= '/' . $place->file;
                }
                else
                    $location .= '/' . $place->file;

                $picNumber = null;

                $image = $request->file('pic');
                $size = [
                    [
                        'width' => 350,
                        'height' => 250,
                        'name' => 'f-',
                        'destination' => $location
                    ],
                    [
                        'width' => 150,
                        'height' => 150,
                        'name' => 't-',
                        'destination' => $location
                    ],
                    [
                        'width' => 200,
                        'height' => 200,
                        'name' => 'l-',
                        'destination' => $location
                    ],
                    [
                        'width' => 600,
                        'height' => 400,
                        'name' => 's-',
                        'destination' => $location
                    ]
                ];

                if($request->kind == 'new') {
                    $fileName = resizeImage($image, $size);
                    move_uploaded_file($_FILES['pic']['tmp_name'], $location.'/'.$fileName);

                    if($kindPlaceId == 13){
                        $newPic = new LocalShopsPictures();
                        $newPic->pic = $fileName;
                        $newPic->localShopId = $place->id;
                        $newPic->isMain = 0;
                        $newPic->save();
                        $newPic->pic = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/'.$newPic->pic);
                    }
                    else{
                        $newPic = new PlacePic();
                        $newPic->picNumber = $fileName;
                        $newPic->placeId = $place->id;
                        $newPic->kindPlaceId = $kindPlaceId;
                        $newPic->save();

                        $newPic->pic = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/'.$newPic->picNumber);
                    }

                    return response()->json(['status' => 'ok', 'result' => $newPic]);
                }
                else{
                    $fileName = resizeImage($image, $size);
                    move_uploaded_file($_FILES['pic']['tmp_name'], $location.'/'.$fileName);

                    if($kindPlaceId == 13) {
                        $pic = LocalShopsPictures::find($request->id);
                        deletePlacePicFiles($location, $pic->pic);
                        $pic->pic = $fileName;
                        $pic->save();
                    }
                    else{
                        $pic = PlacePic::find($request->id);
                        deletePlacePicFiles($location, $pic->picNumber);
                        $pic->picNumber = $fileName;
                        $pic->save();
                    }

                    return response()->json(['status' => 'ok']);
                }
            }
            else
                return response()->json(['status' => 'nok1']);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function deletePlacePic(Request $request)
    {
        if($request->kindPlaceId == 13)
            $pic = LocalShopsPictures::find($request->id);
        else
            $pic = PlacePic::find($request->id);

        if($pic != null){
            $kindPlace = Place::find($request->kindPlaceId);
            if($request->kindPlaceId == 13)
                $place = \DB::table($kindPlace->tableName)->find($pic->localShopId);
            else
                $place = \DB::table($kindPlace->tableName)->find($pic->placeId);

            if($place != null) {
                $location = __DIR__ . "/../../../../assets/_images/$kindPlace->fileName/$place->file";
                if($request->kindPlaceId == 13)
                    deletePlacePicFiles($location, $pic->pic);
                else
                    deletePlacePicFiles($location, $pic->picNumber);
                $pic->delete();
                return response('ok');
            }
            else
                return response('nok1');
        }
        else
            return response('nok');
    }


    public function getCrop(Request $request)
    {
        if( isset($_FILES["l-1"]) && $_FILES["l-1"]['error'] == 0 &&
            isset($_FILES["t-1"]) && $_FILES["t-1"]['error'] == 0 &&
            isset($_FILES["s-1"]) && $_FILES["s-1"]['error'] == 0 &&
            isset($_FILES["f-1"]) && $_FILES["f-1"]['error'] == 0 &&
            isset($_FILES["mainPic"]) && $_FILES["mainPic"]['error'] == 0 &&
            isset($request->picNumber) && isset($request->placeId) && isset($request->kindPlaceId)) {

            $valid_ext = array('image/jpeg','image/png');
            if(in_array($_FILES['mainPic']['type'], $valid_ext) && in_array($_FILES['s-1']['type'], $valid_ext) &&
                in_array($_FILES['f-1']['type'], $valid_ext) && in_array($_FILES['l-1']['type'], $valid_ext) &&
                in_array($_FILES['t-1']['type'], $valid_ext)){

                $id = $request->placeId;
                $kindPlaceId = $request->kindPlaceId;
                $kindPlace = Place::find($kindPlaceId);
                $kindPlaceName = $kindPlace->fileName;
                $place = DB::table($kindPlace->tableName)->find($id);

                if(!file_exists(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName))
                    mkdir(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName);

                if($place != null) {

                    if ($place->file == null || $place->file == 'none' || $place->file == '' ||
                        !file_exists(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName . '/' . $place->file)) {

                        $newFileName = rand(1000000, 9999999);
                        while (is_dir(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName . '/' . $newFileName))
                            $newFileName = (int)($newFileName - 1);

                        $location = __DIR__ . '/../../../../assets/_images/' . $kindPlaceName . '/' . $newFileName;

                        mkdir($location);
                        if(file_exists($location)){
                            $place->file = $newFileName;
                            $place->save();
                        }
                        else {
                            echo 'error';
                            return;
                        }
                    }

                    $location = __DIR__ . '/../../../../assets/_images/' . $kindPlaceName . '/' . $place->file;
                    $picNumber = null;

                    if ($request->kind == 'edit'){
                        $picNumber = $request->picNumber;
                        if ($picNumber == 0)
                            $picNumber = $place->picNumber;
                        else
                            $picNumber .= '.jpg';

                        if ($picNumber != null) {
                            if (file_exists($location . '/f-' . $picNumber))
                                unlink($location . '/f-' . $picNumber);
                            if (file_exists($location . '/s-' . $picNumber))
                                unlink($location . '/s-' . $picNumber);
                            if (file_exists($location . '/t-' . $picNumber))
                                unlink($location . '/t-' . $picNumber);
                            if (file_exists($location . '/l-' . $picNumber))
                                unlink($location . '/l-' . $picNumber);
                            if (file_exists($location . '/' . $picNumber))
                                unlink($location . '/' . $picNumber);
                        }

                    }
                    else {
                        $allPicNumber = PlacePic::where('placeId', $place->id)->where('kindPlaceId', $kindPlaceId)->pluck('picNumber')->toArray();
                        array_push($allPicNumber, $place->picNumber);

                        for($i = 1; $i < 1000; $i++) {
                            $picNumber = $i . '.jpg';
                            if (!in_array($picNumber, $allPicNumber)) {
                                $check = true;
                                if (file_exists($location . '/f-' . $picNumber))
                                    $check = false;
                                if (file_exists($location . '/s-' . $picNumber))
                                    $check = false;
                                if (file_exists($location . '/t-' . $picNumber))
                                    $check = false;
                                if (file_exists($location . '/l-' . $picNumber))
                                    $check = false;
                                if (file_exists($location . '/' . $picNumber))
                                    $check = false;

                                if($check){
                                    if($request->picNumber == 0){
                                        $place->picNumber = $picNumber;
                                        $place->save();
                                    }
                                    else {
                                        $newPic = new PlacePic();
                                        $newPic->picNumber = $picNumber;
                                        $newPic->placeId = $place->id;
                                        $newPic->kindPlaceId = $kindPlaceId;
                                        $newPic->save();
                                    }

                                    break;
                                }
                            }
                        }
                    }

                    if($picNumber != null) {
                        $destinationT = $location . '/t-' . $picNumber;
                        $destinationF = $location . '/f-' . $picNumber;
                        $destinationS = $location . '/s-' . $picNumber;
                        $destinationL = $location . '/l-' . $picNumber;
                        $destinationMainPic = $location . '/' . $picNumber;

                        compressImage($_FILES['f-1']['tmp_name'], $destinationF, 80);
                        compressImage($_FILES['t-1']['tmp_name'], $destinationT, 80);
                        compressImage($_FILES['s-1']['tmp_name'], $destinationS, 80);
                        compressImage($_FILES['l-1']['tmp_name'], $destinationL, 80);
                        compressImage($_FILES['mainPic']['tmp_name'], $destinationMainPic, 80);

                        echo 'ok';
                    }
                    else
                        echo 'nok4';
                }
                else
                    echo 'nok3';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';
        return ;
    }

}
