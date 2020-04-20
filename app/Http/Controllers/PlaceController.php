<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\Amaken;
use App\models\Cities;
use App\models\CityPic;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\PlaceFeatureRelation;
use App\models\PlaceFeatures;
use App\models\PlacePic;
use App\models\PlaceTag;
use App\models\PostCityRelation;
use App\models\QuestionSection;
use App\models\Restaurant;
use App\models\SogatSanaie;
use App\models\SpecialAdvice;
use App\models\State;
use App\models\TopInCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PHPExcel_IOFactory;
use PHPExcel_RichText;
use ZipArchive;
use Illuminate\Support\Facades\Session;
use function GuzzleHttp\Promise\all;


class PlaceController extends Controller {

    public function uploadImgPage($kindPlaceId, $id)
    {
        $kindPlace = Place::find($kindPlaceId);
        $place = DB::table($kindPlace->tableName)->select(['id', 'name', 'file', 'picNumber', 'alt', 'cityId'])->find($id);
        $kindPlaceName = $kindPlace->fileName;

        $city = Cities::find($place->cityId);
        $state = State::find($city->stateId);

        $place->pics = PlacePic::where('placeId', $place->id)->where('kindPlaceId', $kindPlaceId)->get();

        foreach ($place->pics as $item)
            $item->number = explode('.', $item->picNumber)[0];

        return view('content.newContent.uploadImg', compact(['kindPlaceId', 'place', 'kindPlaceName', 'state']));
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

                switch ($kindPlaceId){
                    case 1:
                        $place = Amaken::find($id);
                        $kindPlaceName = 'amaken';
                        break;
                    case 3:
                        $place = Restaurant::find($id);
                        $kindPlaceName = 'restaurant';
                        break;
                    case 4:
                        $place = Hotel::find($id);
                        $kindPlaceName = 'hotels';
                        break;
                    case 6:
                        $place = Majara::find($id);
                        $kindPlaceName = 'majara';
                        break;
                    case 10:
                        $place = SogatSanaie::find($id);
                        $kindPlaceName = 'sogatsanaie';
                        break;
                    case 11:
                        $place = MahaliFood::find($id);
                        $kindPlaceName = 'mahalifood';
                        break;
                }

                if(!file_exists(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName))
                    mkdir(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName);

                if($place != null) {

                    if ($place->file == null || $place->file == 'none' || $place->file == '' ||
                        !file_exists(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName . '/' . $place->file)) {

                        $newFileName = rand(1000000, 9999999);
                        while (file_exists(__DIR__ . '/../../../../assets/_images/' . $kindPlaceName . '/' . $newFileName))
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

    public function deletePlacePic(Request $request)
    {
        $pic = PlacePic::find($request->id);
        if($pic != null){
            switch ($pic->kindPlaceId){
                case 1:
                    $place = Amaken::find($pic->placeId);
                    $folderName = 'amaken';
                    break;
                case 3:
                    $place = Restaurant::find($pic->placeId);
                    $folderName = 'restaurant';
                    break;
                case 4:
                    $place = Hotel::find($pic->placeId);
                    $folderName = 'hotels';
                    break;
                case 6:
                    $place = Majara::find($pic->placeId);
                    $folderName = 'majara';
                    break;
                case 10:
                    $place = SogatSanaie::find($pic->placeId);
                    $folderName = 'sogatsanaie';
                    break;
                case 11:
                    $place = MahaliFood::find($pic->placeId);
                    $folderName = 'mahalifood';
                    break;
                default:
                    echo 'nok';
                    return;
            }
            if($place != null) {
                $location = __DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file;

                $this->deletePlacePicFiles($location, $pic->picNumber);

                $pic->delete();
                echo 'ok';
            }
            else
                echo 'nok';
        }
        else
            echo 'nok';

        return;
    }

    public function changeAltPic(Request $request)
    {
        $pic = PlacePic::find($request->id);
        if($pic != null){
            $pic->alt = $request->alt;
            $pic->save();

            echo 'ok';
        }
        else
            echo 'nok';
        return;
    }

    public function setMainPic(Request $request)
    {
        $pic = PlacePic::find($request->id);

        if($pic != null){
            switch ($pic->kindPlaceId){
                case 1:
                    $place = Amaken::find($pic->placeId);
                    break;
                case 3:
                    $place = Restaurant::find($pic->placeId);
                    break;
                case 4:
                    $place = Hotel::find($pic->placeId);
                    break;
                case 6:
                    $place = Majara::find($pic->placeId);
                    break;
                case 10:
                    $place = SogatSanaie::find($pic->placeId);
                    break;
                case 11:
                    $place = MahaliFood::find($pic->placeId);
                    break;
                default:
                    echo 'nok';
                    return;
            }
            if($place != null){
                $mainPicNum = $pic->picNumber;
                $pic->picNumber = $place->picNumber;
                $pic->save();

                $place->picNumber = $mainPicNum;
                $place->save();

                echo 'ok';
            }
        }

        return;
    }

    public function deletePlace(Request $request)
    {
        $id = $request->id;
        $kindPlaceId  = $request->kindPlaceId;

        switch ($kindPlaceId){
            case 1:
                $place = Amaken::find($id);
                $folderKind = 'amaken';
                break;
            case 3:
                $place = Restaurant::find($id);
                $folderKind = 'restaurant';
                break;
            case 4:
                $place = Hotel::find($id);
                $folderKind = 'hotels';
                break;
            case 6:
                $place = Majara::find($id);
                $folderKind = 'majara';
                break;
            case 10:
                $place = SogatSanaie::find($id);
                $folderKind = 'sogatsanaie';
                break;
            case 11:
                $place = MahaliFood::find($id);
                $folderKind = 'mahalifood';
                break;
        }

        if($place != null){
            $place->pics = PlacePic::where('kindPlaceId', $kindPlaceId)->where('placeId', $id)->get();
            $isFile = false;
            if($place->file != null && $place->file != 'none' && $place->file != '') {
                $location = __DIR__ . '/../../../../assets/_images/' . $folderKind . '/' . $place->file;
                $isFile = true;
            }

            foreach ($place->pics as $item) {
                if($isFile)
                    $this->deletePlacePicFiles($location, $item->picNumber);
                $item->delete();
            }

            if($isFile)
                $this->deletePlacePicFiles($location, $place->picNumber);
            $place->delete();

            if($isFile)
                deleteDir($location);
        }

        return \redirect()->back();
    }

    private function deletePlacePicFiles($location, $picNumber){

        $locationF = $location . '/f-' . $picNumber;
        $locationS = $location .  '/s-' . $picNumber;
        $locationL = $location .  '/l-' . $picNumber;
        $locationT = $location .  '/t-' . $picNumber ;
        $locationMain = $location .  '/' . $picNumber ;

        if (file_exists($locationF))
            unlink($locationF);
        if (file_exists($locationS))
            unlink($locationS);
        if (file_exists($locationL))
            unlink($locationL);
        if (file_exists($locationT))
            unlink($locationT);
        if (file_exists($locationMain))
            unlink($locationMain);
    }

    public function newChangeContent($cityId, $mode, $cityMode){
        $kindPlace = Place::find($mode);
        $state = State::all();

        switch ($mode) {
            case getValueInfo("hotel"):
            default:
                $kind = 'hotels';
                break;
            case getValueInfo('amaken'):
                $kind = 'amaken';
                break;
            case getValueInfo('restaurant'):
                $kind = 'restaurant';
                break;
            case getValueInfo('majara'):
                $kind = 'majara';
                break;
            case 10:
                $kind = 'sogatSanaies';
                break;
            case 11:
                $kind = 'mahaliFood';
                break;
        }

        if($cityMode != 'country') {
            if ($kind == 'mahaliFood' || $kind == 'sogatSanaies') {
                if ($cityMode == 0) {
                    $state2 = State::find($cityId);
                    $name = $state2->name;
                    $id = $state2->id;
                    $stateId = $state2->id;

                    $places = DB::select('select h.name, h.cityId, h.id, h.picNumber from ' . $kind . ' h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
                } else {
                    $city = Cities::find($cityId);
                    $name = $city->name;
                    $id = $cityId;
                    $stateId = $city->stateId;

                    $places = DB::select('select name, cityId, id, picNumber FROM ' . $kind . ' WHERE cityId = ' . $id);
                }
                foreach ($places as $item) {
                    $item->pic = URL::asset('_images/nopic/blank.jpg');
                }
            } else {
                if ($cityMode == 0) {
                    $state2 = State::find($cityId);
                    $name = $state2->name;
                    $id = $state2->id;
                    $stateId = $state2->id;

                    $places = DB::select('select h.name, h.cityId, h.pic_1, h.file, h.id from ' . $kind . ' h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
                } else {
                    $city = Cities::find($cityId);
                    $name = $city->name;
                    $id = $cityId;
                    $stateId = $city->stateId;

                    $places = DB::select('SELECT name, cityId, pic_1, file, id FROM ' . $kind . ' WHERE cityId = ' . $cityId);
                }

                foreach ($places as $item) {
                    if ($item->pic_1 != 0 || ($item->file != '' && $item->file != null))
                        $item->pic = URL::asset('_images/' . $kind . '/' . $item->file . '/f-1.jpg');
                    else
                        $item->pic = URL::asset('_images/nopic/blank.jpg');
                }
            }
        }
        else{
            $places = DB::table($kindPlace->tableName)->select(['id', 'name', 'cityId'])->get();
            $stateId = 0;
            $id = 0;
            $name = 'کل کشور';
        }
        $city = Cities::where('stateId',$stateId)->get();

        $jsonPlace = json_encode($places);

        return view('content.newChangeContent', compact(['name', 'cityMode', 'places', 'mode', 'id', 'jsonPlace', 'kind', 'state', 'stateId', 'city']));
    }

    public function editContent($mode, $id)
    {
        $kind = 'edit';
        $allState = State::all();

        $kindFeatures = array();
        $features = PlaceFeatures::where('kindPlaceId', $mode)->where('parent', 0)->get();
        foreach ($features as $item) {
            $item->subFeat = PlaceFeatures::where('parent', $item->id)->get();
            $sf = PlaceFeatures::where('parent', $item->id)->pluck('id')->toArray();
            $kindFeatures = array_merge($kindFeatures, $sf);
        }
        $placeFeatures = PlaceFeatureRelation::where('placeId', $id)->whereIn('featureId', $kindFeatures)->pluck('featureId')->toArray();

        $kindPlace = Place::find($mode);
        $place = DB::table($kindPlace->tableName)->find($id);
        $city = Cities::find($place->cityId);
        $cities = Cities::where('stateId', $city->stateId)->get();
        $state = State::find($city->stateId);
        $place->city = $city->name;
        $place->stateId = $city->stateId;
        $place->tags = PlaceTag::getTags($kindPlace->id, $place->id);
        $place->description = trueShowForTextArea($place->description);
        $place->zoom = 15;
        if($kindPlace->tableName != 'sogatSanaies' && $kindPlace->tableName != 'mahaliFood') {
            if ($place->C == null && $place->D == null) {
                $place->C = 32.42056639964595;
                $place->D = 54.00537109375;
                $place->zoom = 5;
            }
        }

        switch ($mode){
            case 1:
                return view('content.editContent.editAmaken', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState', 'features', 'placeFeatures']));
                break;
            case 4:
                return view('content.editContent.editHotels', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState', 'features', 'placeFeatures']));
                break;
            case 3:
                return view('content.editContent.editRestaurant', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState', 'features', 'placeFeatures']));
                break;
            case 6:
                return view('content.editContent.editMajara', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState', 'features', 'placeFeatures']));
                break;
            case 10:
                return view('content.editContent.editSogatSanaie', compact(['place', 'kind', 'state', 'allState', 'mode', 'cities', 'city', 'features', 'placeFeatures']));
                break;
            case 11:
                $place->material = json_decode($place->material);
                $place->recipes = trueShowForTextArea($place->recipes);
                return view('content.editContent.editMahaliFood', compact(['place', 'kind', 'state', 'allState', 'mode', 'cities', 'city', 'features', 'placeFeatures']));
                break;
            default:
                return \redirect()->back();
        }
    }

    public function newContent($cityMode, $cityId, $mode)
    {
        $allState = State::all();
        $state = 0;
        $cities = 0;
        if($cityMode != 'country') {
            if ($cityMode == 1) {
                $city = Cities::find($cityId);
                $state = State::find($city->stateId);
                $cities = Cities::where('stateId', $state->id)->get();
            } else {
                $city = null;
                $state = State::find($cityId);
                $cities = Cities::where('stateId', $state->id)->get();
            }
        }
        else {
            $city = null;
            $cities = Cities::where('stateId', $allState[0]->id)->get();
        }

        switch ($mode){
            case 1:
                $titleName = 'مکان دیدنی جدید';
                $url = route('storeAmaken');
                $kind = 'amaken';
                break;
            case 3:
                $titleName = 'رستوران جدید';
                $url = route('storeRestaurant');
                $kind = 'restaurant';
                break;
            case 4:
                $titleName = 'هتل جدید';
                $url = route('storeHotel');
                $kind = 'hotels';
                break;
            case 6:
                $titleName = 'ماجرای جدید';
                $url = route('storeMajara');
                $kind = 'majara';
                break;
            case 10:
                $titleName = 'سوغات/صنایع جدید';
                $url = route('storeSogatSanaie');
                $kind = 'sogatsanaie';
                break;
            case 11:
                $titleName = 'غذای محلی جدید';
                $url = route('storeMahaliFood');
                $kind = 'mahalifood';
                break;
        }

        $features = PlaceFeatures::where('kindPlaceId', $mode)->where('parent', 0)->get();
        foreach ($features as $item)
            $item->subFeat = PlaceFeatures::where('parent', $item->id)->get();

        return view('content.newContent.newContentCore', compact(['city', 'state', 'allState', 'mode', 'cities', 'kind', 'url', 'titleName', 'features']));

    }

    public function storeAmaken(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'keyword' => 'required',
            'seoTitle' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $amaken = new Amaken();

            $amaken->file = 'none';

            $amaken->pic_1 = 0;
            $amaken->pic_2 = 0;
            $amaken->pic_3 = 0;
            $amaken->pic_4 = 0;
            $amaken->pic_5 = 0;

            $amaken->author = \auth()->user()->id;

        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $amaken = Amaken::find($request->id);

            if($amaken == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $amaken->name = $request->name;
        $amaken->cityId = $request->cityId;
        $amaken->C = $request->C;
        $amaken->D = $request->D;
        $amaken->meta = $request->meta;
        $amaken->keyword = $request->keyword;
        $amaken->description = nl2br($request->description);
        $amaken->seoTitle = $request->seoTitle;
        if($request->slug == null || $request->slug == '')
            $amaken->slug = makeSlug($request->keyword);
        else
            $amaken->slug = makeSlug($request->slug);

        if($request->address != null)
            $amaken->address = $request->address;
        else
            $amaken->address = '';

        if($request->phone != null)
            $amaken->phone = $request->phone;
        else
            $amaken->phone = '';

        if($request->site != null)
            $amaken->site = $request->site;
        else
            $amaken->site = '';

        $amaken->save();

        $this->storePlaceTags(1, $amaken->id, $request->tag);

        if(isset($request->features) && is_array($request->features) && count($request->features) > 0)
            $this->storePlaceFeatures(1, $amaken->id, $request->features);
        else
            PlaceFeatureRelation::where(['kindPlaceId' => 1, 'placeId' => $amaken->id])->delete();


        return \redirect(\url('uploadImgPage/1/' . $amaken->id));
    }

    public function storeHotel(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'keyword' => 'required',
            'seoTitle' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $hotel = new Hotel();

            $hotel->pic_1 = 0;
            $hotel->pic_2 = 0;
            $hotel->pic_3 = 0;
            $hotel->pic_4 = 0;
            $hotel->pic_5 = 0;

            $hotel->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $hotel = Hotel::find($request->id);

            if($hotel == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $hotel->name = $request->name;
        $hotel->cityId = $request->cityId;
        $hotel->C = $request->C;
        $hotel->D = $request->D;
        $hotel->meta = $request->meta;
        $hotel->seoTitle = $request->seoTitle;
        $hotel->description = nl2br($request->description);
        $hotel->keyword = $request->keyword;
        $hotel->kind_id = $request->kind;
        $hotel->rate_int = $request->rate_int;
        if($request->slug == null || $request->slug == '')
            $hotel->slug = makeSlug($request->keyword);
        else
            $hotel->slug = makeSlug($request->slug);
        if($request->rate_int > 0){
            switch ($request->rate_int){
                case 1:
                    $hotel->rate = 'یک ستاره';
                    break;
                case 2:
                    $hotel->rate = 'دو ستاره';
                    break;
                case 3:
                    $hotel->rate = 'سه ستاره';
                    break;
                case 4:
                    $hotel->rate = 'چهار ستاره';
                    break;
                case 5:
                    $hotel->rate = 'پنج ستاره';
                    break;
            }
        }
        $hotel->room_num = $request->room_num;

        if(isset($request->momtaz) && $request->momtaz == 'on')
            $hotel->momtaz = 1;
        else
            $hotel->momtaz = 0;

        if($request->address != null)
            $hotel->address = $request->address;
        else
            $hotel->address = '';

        if($request->phone != null)
            $hotel->phone = $request->phone;
        else
            $hotel->phone = '';

        if($request->site != null)
            $hotel->site = $request->site;
        else
            $hotel->site = '';

        if(isset($request->isVabastegi) && $request->isVabastegi == 'on')
            $hotel->vabastegi = $request->vabastegi;
        else
            $hotel->vabastegi = '';

        $hotel->save();

        $this->storePlaceTags(4, $hotel->id, $request->tag);

        if(isset($request->features) && is_array($request->features) && count($request->features) > 0)
            $this->storePlaceFeatures(4, $hotel->id, $request->features);
        else
            PlaceFeatureRelation::where(['kindPlaceId' => 4, 'placeId' => $hotel->id])->delete();

        return \redirect(\url('uploadImgPage/4/' . $hotel->id));
    }

    public function storeRestaurant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'address' => 'required',
            'keyword' => 'required',
            'seoTitle' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $restaurant = new Restaurant();

            $restaurant->markaz = 0;
            $restaurant->hoome = 0;
            $restaurant->class = 0;

            $restaurant->file = 'none';
            $restaurant->pic_1 = 0;
            $restaurant->pic_2 = 0;
            $restaurant->pic_3 = 0;
            $restaurant->pic_4 = 0;
            $restaurant->pic_5 = 0;

            $restaurant->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $restaurant = Restaurant::find($request->id);

            if($restaurant == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $restaurant->name = $request->name;
        $restaurant->cityId = $request->cityId;
        $restaurant->C = $request->C;
        $restaurant->D = $request->D;
        $restaurant->meta = $request->meta;
        $restaurant->seoTitle = $request->seoTitle;
        $restaurant->description = nl2br($request->description);
        $restaurant->keyword = $request->keyword;
        $restaurant->kind_id = $request->kind_id;
        if($request->slug == null || $request->slug == '')
            $restaurant->slug = makeSlug($request->keyword);
        else
            $restaurant->slug = makeSlug($request->slug);

        if($request->address != null)
            $restaurant->address = $request->address;
        else
            $restaurant->address = '';

        if($request->phone != null)
            $restaurant->phone = $request->phone;
        else
            $restaurant->phone = '';

        if($request->site != null)
            $restaurant->site = $request->site;
        else
            $restaurant->site = '';
        $restaurant->save();

        $this->storePlaceTags(3, $restaurant->id, $request->tag);

        if(isset($request->features) && is_array($request->features) && count($request->features) > 0)
            $this->storePlaceFeatures(3, $restaurant->id, $request->features);
        else
            PlaceFeatureRelation::where(['kindPlaceId' => 3, 'placeId' => $restaurant->id])->delete();

        return \redirect(\url('uploadImgPage/3/' . $restaurant->id));
    }

    public function storeMajara(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'keyword' => 'required',
            'seoTitle' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);


        if(isset($request->inputType) && $request->inputType == 'new'){
            $majara = new Majara();

//            $majara->manategh = 0;
//            $majara->class = 0;
            $majara->file = 'none';

            $majara->pic_1 = 0;
            $majara->pic_2 = 0;
            $majara->pic_3 = 0;
            $majara->pic_4 = 0;
            $majara->pic_5 = 0;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $majara = Majara::find($request->id);

            if($majara == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $majara->name = $request->name;
        $majara->cityId = $request->cityId;
        $majara->C = $request->C;
        $majara->D = $request->D;
        $majara->meta = $request->meta;
        $majara->seoTitle = $request->seoTitle;
        $majara->description = nl2br($request->description, false);
        $majara->keyword = $request->keyword;
        if($request->slug == null || $request->slug == '')
            $majara->slug = makeSlug($request->keyword);
        else
            $majara->slug = makeSlug($request->slug);

        if($request->dastresi != null)
            $majara->dastresi = $request->dastresi;
        else
            $majara->dastresi = '';

        if($request->nazdik != null)
            $majara->nazdik = $request->nazdik;
        else
            $majara->nazdik = '';

        $majara->save();

        $this->storePlaceTags(6, $majara->id, $request->tag);

        if(isset($request->features) && is_array($request->features) && count($request->features) > 0)
            $this->storePlaceFeatures(6, $majara->id, $request->features);
        else
            PlaceFeatureRelation::where(['kindPlaceId' => 6, 'placeId' => $majara->id])->delete();

        return \redirect(\url('uploadImgPage/6/' . $majara->id));
    }

    public function storeMahaliFood(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'kind' => 'required',
            'keyword' => 'required',
            'seoTitle' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'tag' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $newMahali = new MahaliFood();
            $newMahali->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $newMahali = MahaliFood::find($request->id);

            if($newMahali == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $newMahali->name = $request->name;
        $newMahali->cityId = $request->cityId;
        $newMahali->kind = $request->kind;
        $newMahali->keyword = $request->keyword;
        $newMahali->seoTitle = $request->seoTitle;
        $newMahali->meta = $request->meta;
        $newMahali->description = nl2br($request->description);
        $newMahali->alt = $request->keyword;
        if($request->slug == null || $request->slug == '')
            $newMahali->slug = makeSlug($request->keyword);
        else
            $newMahali->slug = makeSlug($request->slug);

        if(isset($request->diabet) && $request->diabet == 'on')
            $newMahali->diabet = 1;
        else
            $newMahali->diabet = 0;

        if(isset($request->vegan) && $request->vegan == 'on')
            $newMahali->vegan = 1;
        else
            $newMahali->vegan = 0;

        if(isset($request->vegetarian) && $request->vegetarian == 'on')
            $newMahali->vegetarian = 1;
        else
            $newMahali->vegetarian = 0;

        if(isset($request->hotOrCold))
            $newMahali->hotOrCold = $request->hotOrCold;

        if(isset($request->recipes) && $request->recipes != null)
            $newMahali->recipes = nl2br($request->recipes);
        else
            $newMahali->recipes = null;

        $material = array();

        if(isset($request->matName)) {
            for ($i = 0; $i < count($request->matName) && $i < count($request->matValue); $i++) {
                if (isset($request->matName[$i]) && $request->matName[$i] != null && isset($request->matValue[$i]) && $request->matValue[$i] != null) {
                    $mat = [$request->matName[$i], $request->matValue[$i]];
                    array_push($material, $mat);
                }
            }
        }
        $newMahali->material = json_encode($material);

        $newMahali->energy = $request->energy;
        $newMahali->volume = $request->volume;
        if($request->source == 1){
            $newMahali->gram = 0;
            $newMahali->spoon = 1;
        }
        else{
            $newMahali->gram = 1;
            $newMahali->spoon = 0;
        }

        if(isset($request->rice) && $request->rice == 'on')
            $newMahali->rice = 1;
        else
            $newMahali->rice = 0;

        if(isset($request->bread) && $request->bread == 'on')
            $newMahali->bread = 1;
        else
            $newMahali->bread = 0;

        $newMahali->save();

        $this->storePlaceTags(11, $newMahali->id, $request->tag);

        return \redirect(\url('uploadImgPage/11/' . $newMahali->id));
    }

    public function storeSogatSanaie(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'eatable' => 'required',
            'keyword' => 'required',
            'seoTitle' => 'required',
            'meta' => 'required',
            'description' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $newSogat = new SogatSanaie();
            $newSogat->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $newSogat = SogatSanaie::find($request->id);

            if($newSogat == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $newSogat->name = $request->name;
        $newSogat->cityId = $request->cityId;
        $newSogat->keyword = $request->keyword;
        $newSogat->seoTitle = $request->seoTitle;
        $newSogat->meta = $request->meta;
        $newSogat->description =  nl2br($request->description);
        $newSogat->alt = $request->keyword;
        if($request->slug == null || $request->slug == '')
            $newSogat->slug = makeSlug($request->keyword);
        else
            $newSogat->slug = makeSlug($request->slug);

        if(isset($request->weight) && $request->weight != null)
            $newSogat->weight = $request->weight;
        else
            $newSogat->weight = 1;

        if(isset($request->price) && $request->price != null)
            $newSogat->price = $request->price;
        else
            $newSogat->price = 1;

        if(isset($request->size) && $request->size != null)
            $newSogat->size = $request->size;
        else
            $newSogat->size = 1;

        $newSogat->eatable = $request->eatable;

        if (isset($request->jewelry) && $request->jewelry == 'on')
            $newSogat->jewelry = 1;
        else
            $newSogat->jewelry = 0;

        if (isset($request->cloth) && $request->cloth == 'on')
            $newSogat->cloth = 1;
        else
            $newSogat->cloth = 0;

        if (isset($request->decorative) && $request->decorative == 'on')
            $newSogat->decorative = 1;
        else
            $newSogat->decorative = 0;

        if (isset($request->applied) && $request->applied == 'on')
            $newSogat->applied = 1;
        else
            $newSogat->applied = 0;

        if (isset($request->fragile) && $request->fragile == '1')
            $newSogat->fragile = 1;
        else
            $newSogat->fragile = 0;

        if (isset($request->style))
            $newSogat->style = $request->style;


        if (isset($request->torsh) && $request->torsh == 'on')
            $newSogat->torsh = 1;
        else
            $newSogat->torsh = 0;

        if (isset($request->shirin) && $request->shirin == 'on')
            $newSogat->shirin = 1;
        else
            $newSogat->shirin = 0;

        if (isset($request->talkh) && $request->talkh == 'on')
            $newSogat->talkh = 1;
        else
            $newSogat->talkh = 0;

        if (isset($request->malas) && $request->malas == 'on')
            $newSogat->malas = 1;
        else
            $newSogat->malas = 0;

        if (isset($request->shor) && $request->shor == 'on')
            $newSogat->shor = 1;
        else
            $newSogat->shor = 0;

        if (isset($request->tond) && $request->tond == 'on')
            $newSogat->tond = 1;
        else
            $newSogat->tond = 0;

        if(isset($request->material) && $request->material != null)
            $newSogat->material = $request->material;

        $newSogat->author = \auth()->user()->id;
        $newSogat->save();

        $this->storePlaceTags(10, $newSogat->id, $request->tag);

        return \redirect(\url('uploadImgPage/10/' . $newSogat->id));
    }




    public function index()
    {
        $places = Place::all();

        return view('config.places', compact(['places']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $place = Place::where('name', $request->name)->first();

        if($place == null){
            $place =  new Place();
            $place->name = $request->name;
            $place->visibility = 1;
            $place->save();
        }
        else{
            Session::flash('error', 'این مکان قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $place = Place::find($request->id);

        if($place != null){
            $checkPlace = Place::where('name', $request->name)->first();
            if($checkPlace == null) {
                $place->name = $request->name;
                $place->save();
            }
            else{
                Session::flash('error', 'مکانی با این نام موجود می باشد');
            }
        }
        else{
            Session::flash('error', 'مشکلی در ویرایش به وجود امد. لطفا دوباره تلاش کنید');
        }
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        Place::find($request->id)->delete();

        return redirect()->back();
    }

    public function changeContent($city, $mode, $cityMode, $wantedKey = -1, $filter = -1) {

        switch ($mode) {
            case getValueInfo("hotel"):
            default:
                    return $this->changeHotelContent($city, $wantedKey, $cityMode);
            case getValueInfo('amaken'):
                    return $this->changeAmakenContent($city, $wantedKey, $cityMode);
            case getValueInfo('restaurant'):
                    return $this->changeRestaurantContent($city, $wantedKey, $cityMode);
            case getValueInfo('majara'):
                    return $this->changeMajaraContent($city, $wantedKey, $cityMode);
            case getValueInfo("adab"):
                    return $this->changeAdabContent($city, $wantedKey, $filter);
        }
    }

    private function changeAdabContent($stateId, $wantedKey, $filter) {

        if($filter == -1)
            $places = Adab::whereStateId($stateId)->get();
        else
            $places = Adab::whereStateId($stateId)->whereCategory($filter)->get();

        $categories = [
            ['name' => 'غذا محلی', 'id' => getValueInfo('ghaza')],
            ['name' => 'سوغات', 'id' => getValueInfo('soghat')],
            ['name' => 'صنایع دستی', 'id' => getValueInfo('sanaye')]
        ];

        return view('content.changeAdab', ['places' => $places, 'wantedKey' => $wantedKey,
            'categories' => json_encode($categories), 'selectedMode' => $filter, 'modes' => $categories,
            'pageURL' => route('changeContent', ['city' => $stateId, 'mode' => getValueInfo('adab'), 'cityMode' => 0, 'wantedKey' => $wantedKey])]);
    }

    private function changeHotelContent($cityId, $wantedKey, $mode) {

        if($mode) {
            $places = Hotel::whereCityId($cityId)->get();
        }
        else {
            $places = DB::select('select h.* from hotels h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
        }

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        $kind_ids = [
            ['name' => 'هتل', 'id' => getValueInfo('hotelMode')],
            ['name' => 'هتل آپارتمان', 'id' => getValueInfo('aparteman')],
            ['name' => 'مهمان سرا', 'id' => getValueInfo('mehmansara')],
            ['name' => 'ویلا', 'id' => getValueInfo('vila')],
            ['name' => 'متل', 'id' => getValueInfo('motel')],
            ['name' => 'مجتمع تفریحی', 'id' => getValueInfo('tafrihi')],
            ['name' => 'پانسیون', 'id' => getValueInfo('pansion')],
            ['name' => 'بوم گردی', 'id' => getValueInfo('bom')]
        ];

        return view('content.changeHotel', ['cityId' => $cityId,'places' => $places, 'kind_ids' => json_encode($kind_ids), 'mode' => $mode,
            'wantedKey' => $wantedKey, 'cities' => json_encode($citiesOut)
        ]);
    }

    private function changeMajaraContent($cityId, $wantedKey, $mode) {

        if($mode)
            $places = Majara::whereCityId($cityId)->get();
        else
            $places = DB::select('select m.* from majara m, cities c WHERE m.cityId = c.id and c.stateId = ' . $cityId);

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        return view('content.changeMajara', ['places' => $places, 'cities' => json_encode($citiesOut),
            'wantedKey' => $wantedKey]);
    }

    private function changeRestaurantContent($cityId, $wantedKey, $mode) {

        if($mode)
            $places = Restaurant::whereCityId($cityId)->get();
        else
            $places = DB::select('select h.* from restaurant h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);

        $kind_ids = [
            ['name' => 'رستوران', 'id' => getValueInfo('restaurantMode')],
            ['name' => 'فست فود', 'id' => getValueInfo('fastfood')]
        ];

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        return view('content.changeRestaurant', ['places' => $places, 'kind_ids' => json_encode($kind_ids),
            'wantedKey' => $wantedKey, 'cities' => json_encode($citiesOut)]);
    }

    private function changeAmakenContent($cityId, $wantedKey, $mode) {

        if($mode)
            $places = Amaken::whereCityId($cityId)->get();
        else {
            $places = DB::select('select h.* from amaken h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
        }

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        return view('content.changeAmaken', ['places' => $places, 'cities' => json_encode($citiesOut),
            'wantedKey' => $wantedKey]);
    }

    public function doChangePlace() {

        if(isset($_POST["id"]) && isset($_POST["kindPlaceId"]) && isset($_POST["val"]) &&
            isset($_POST["mode"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $id = makeValidInput($_POST["id"]);
            $mode = makeValidInput($_POST["mode"]);
            $val = makeValidInput($_POST["val"]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                default:
                    DB::update('update hotels set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('adab'):
                    DB::update('update adab set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('amaken'):
                    DB::update('update amaken set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('restaurant'):
                    DB::update('update restaurant set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('majara'):
                    DB::update('update majara set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
            }
        }
    }

    public function choosePlace($mode) {

        $url = route('root');

        switch ($mode) {
            case "alt":
                $url = $url . "/changeAlt/";
                break;
        }

        return view('content.choosePlace', ['url' => $url, 'places' => Place::whereVisibility(true)->get()]);
    }


    public function searchForCity() {
        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT cities.id, cities.name as cityName, state.name as stateName FROM cities, state WHERE cities.stateId = state.id and  cities.name LIKE '%$key%' ");
        echo json_encode($cities);
    }

    public function searchForCityAndState() {

        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT 'city' as mode, cities.id, cities.name as cityName, state.name as stateName FROM cities, state WHERE cities.stateId = state.id and  cities.name LIKE '%$key%' ");
        $states = DB::select("SELECT 'state' as mode, id, name FROM state WHERE name LIKE '%$key%' ");

        echo json_encode(array_merge($cities, $states));
    }

    public function searchForState() {
        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT id, name FROM state WHERE name LIKE '%$key%' ");
        echo json_encode($cities);
    }

    private function uploadHotel($contents) {

        $err = "";
        $counter = 1;

        foreach ($contents as $content) {
            $tmp = new Hotel();
            $j = 0;

            $tmp->name = $content[$j++];
            $tmp->address = $content[$j++];
            $tmp->phone = $content[$j++];
            $tmp->site = $content[$j++];
            $tmp->description = $content[$j++];
            $tmp->vabastegi = $content[$j++];
            $tmp->rate = $content[$j++];
            $tmp->room_num = $content[$j++];
            $tmp->kind_id = $content[$j++];
            $tmp->file = $content[$j];

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-1.jpg'))
                $tmp->pic_1 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-2.jpg'))
                $tmp->pic_2 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-3.jpg'))
                $tmp->pic_3 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-4.jpg'))
                $tmp->pic_4 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-5.jpg'))
                $tmp->pic_5 = 1;

            recurse_copy(__DIR__ . '/../../../public/tmpZip/' . $content[$j],
                __DIR__ . '/../../../../assets/_images/hotels/' . $content[$j]);

            deleteDir(__DIR__ . '/../../../public/tmpZip/' . $content[$j]);

            $j++;
            $tmp->meta = $content[$j++];
            $tmp->cityId = $content[$j++];
            $tmp->C = $content[$j++];
            $tmp->D = $content[$j++];
            $tmp->fasele = $content[$j++];
            $tmp->rate_int = $content[$j++];
            $tmp->keyword = $content[$j++];
            $tmp->h1 = $content[$j++];
            $tmp->alt1 = $content[$j++];
            $tmp->alt2 = $content[$j++];
            $tmp->alt3 = $content[$j++];
            $tmp->alt4 = $content[$j++];
            $tmp->alt5 = $content[$j++];
            $tmp->tag1 = $content[$j++];
            $tmp->tag2 = $content[$j++];
            $tmp->tag3 = $content[$j++];
            $tmp->tag4 = $content[$j++];
            $tmp->tag5 = $content[$j++];
            $tmp->tag6 = $content[$j++];
            $tmp->tag7 = $content[$j++];
            $tmp->tag8 = $content[$j++];
            $tmp->tag9 = $content[$j++];
            $tmp->tag10 = $content[$j++];
            $tmp->tag11 = $content[$j++];
            $tmp->tag12 = $content[$j++];
            $tmp->tag13 = $content[$j++];
            $tmp->tag14 = $content[$j++];
            $tmp->tag15 = $content[$j];

            $tmp->onlineReservation = 0;
            $tmp->authorized = 0;
            $tmp->author = Auth::user()->id;

            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                $err .= "خطا در خط " . ($counter) . "<br/>" . $x->getMessage() . "<br/>";
            }
            finally {
                $counter++;
            }
        }

        return $err;
    }

    private function uploadAmaken($contents) {

        $err = "";
        $counter = 1;

        foreach ($contents as $content) {

            $tmp = new Amaken();
            $j = 0;

            $tmp->name = $content[$j++];
            $tmp->address = $content[$j++];
            $tmp->phone = $content[$j++];
            $tmp->site = $content[$j++];
            $tmp->description = $content[$j++];

            $tmp->emkanat = $content[$j++];
            $tmp->tarikhi = $content[$j++];
            $tmp->mooze = $content[$j++];
            $tmp->tafrihi = $content[$j++];
            $tmp->tabiatgardi = $content[$j++];
            $tmp->markazkharid = $content[$j++];
            $tmp->baftetarikhi = $content[$j++];

            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];

            $tmp->modern = $content[$j++];
            $tmp->tarikhibana = $content[$j++];
            $tmp->mamooli = $content[$j++];

            $tmp->file = $content[$j];

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-1.jpg'))
                $tmp->pic_1 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-2.jpg'))
                $tmp->pic_2 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-3.jpg'))
                $tmp->pic_3 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-4.jpg'))
                $tmp->pic_4 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-5.jpg'))
                $tmp->pic_5 = 1;

            recurse_copy(__DIR__ . '/../../../public/tmpZip/' . $content[$j],
                __DIR__ . '/../../../../assets/_images/amaken/' . $content[$j]);

            deleteDir(__DIR__ . '/../../../public/tmpZip/' . $content[$j]);

            $j++;

            $tmp->meta = $content[$j++];
            $tmp->cityId = $content[$j++];
            $tmp->C = $content[$j++];
            $tmp->D = $content[$j++];

            $tmp->farhangi = $content[$j++];
            $tmp->ghadimi = $content[$j++];

            $tmp->keyword = $content[$j++];
            $tmp->h1 = $content[$j++];
            $tmp->alt1 = $content[$j++];
            $tmp->alt2 = $content[$j++];
            $tmp->alt3 = $content[$j++];
            $tmp->alt4 = $content[$j++];
            $tmp->alt5 = $content[$j++];
            $tmp->tag1 = $content[$j++];
            $tmp->tag2 = $content[$j++];
            $tmp->tag3 = $content[$j++];
            $tmp->tag4 = $content[$j++];
            $tmp->tag5 = $content[$j++];
            $tmp->tag6 = $content[$j++];
            $tmp->tag7 = $content[$j++];
            $tmp->tag8 = $content[$j++];
            $tmp->tag9 = $content[$j++];
            $tmp->tag10 = $content[$j++];
            $tmp->tag11 = $content[$j++];
            $tmp->tag12 = $content[$j++];
            $tmp->tag13 = $content[$j++];
            $tmp->tag14 = $content[$j++];
            $tmp->tag15 = $content[$j];

            $tmp->authorized = 0;
            $tmp->author = Auth::user()->id;
            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                $err .= "خطا در خط " . ($counter) . "<br/>" . $x->getMessage() . "<br/>";
            }
            finally {
                $counter++;
            }
        }

        return $err;
    }

    private function uploadRestaurant($contents) {

        $err = "";
        $counter = 1;

        foreach ($contents as $content) {

            $tmp = new Restaurant();
            $j = 0;

            $tmp->name = $content[$j++];
            $tmp->address = $content[$j++];
            $tmp->phone = $content[$j++];
            $tmp->site = $content[$j++];
            $tmp->description = $content[$j++];

            $tmp->food_irani = $content[$j++];
            $tmp->food_mahali = $content[$j++];
            $tmp->food_farangi = $content[$j++];
            $tmp->coffeeshop = $content[$j++];
            $tmp->tarikhi = $content[$j++];
            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];

            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];

            $tmp->modern = $content[$j++];
            $tmp->sonnati = $content[$j++];
            $tmp->ghadimi = $content[$j++];
            $tmp->mamooli = $content[$j++];
            $tmp->kind_id = $content[$j++];

            $tmp->file = $content[$j];

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-1.jpg'))
                $tmp->pic_1 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-2.jpg'))
                $tmp->pic_2 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-3.jpg'))
                $tmp->pic_3 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-4.jpg'))
                $tmp->pic_4 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-5.jpg'))
                $tmp->pic_5 = 1;

            recurse_copy(__DIR__ . '/../../../public/tmpZip/' . $content[$j],
                __DIR__ . '/../../../../assets/_images/restaurant/' . $content[$j]);

            deleteDir(__DIR__ . '/../../../public/tmpZip/' . $content[$j]);

            $j++;

            $tmp->meta = $content[$j++];
            $tmp->cityId = $content[$j++];
            $tmp->C = $content[$j++];
            $tmp->D = $content[$j++];

            $tmp->keyword = $content[$j++];
            $tmp->h1 = $content[$j++];
            $tmp->alt1 = $content[$j++];
            $tmp->alt2 = $content[$j++];
            $tmp->alt3 = $content[$j++];
            $tmp->alt4 = $content[$j++];
            $tmp->alt5 = $content[$j++];
            $tmp->tag1 = $content[$j++];
            $tmp->tag2 = $content[$j++];
            $tmp->tag3 = $content[$j++];
            $tmp->tag4 = $content[$j++];
            $tmp->tag5 = $content[$j++];
            $tmp->tag6 = $content[$j++];
            $tmp->tag7 = $content[$j++];
            $tmp->tag8 = $content[$j++];
            $tmp->tag9 = $content[$j++];
            $tmp->tag10 = $content[$j++];
            $tmp->tag11 = $content[$j++];
            $tmp->tag12 = $content[$j++];
            $tmp->tag13 = $content[$j++];
            $tmp->tag14 = $content[$j++];
            $tmp->tag15 = $content[$j];

            $tmp->authorized = 0;
            $tmp->author = Auth::user()->id;
            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                $err .= "خطا در خط " . ($counter) . "<br/>" . $x->getMessage() . "<br/>";
            }
            finally {
                $counter++;
            }
        }

        return $err;
    }

    private function preprocess($neededCol) {

        $excel_file = $_FILES["content"]["name"];

        if(!empty($excel_file)) {

            $path = __DIR__ . '/../../../public/tmp/' . $excel_file;
            $err = uploadCheck($path, "content", "اکسل افزودن دسته ای محتوا", 20000000, "xlsx", "xls");

            if (empty($err)) {

                upload($path, "content", "اکسل افزودن دسته ای محتوا");

                $excelReader = PHPExcel_IOFactory::createReaderForFile($path);
                $excelObj = $excelReader->load($path);
                $workSheet = $excelObj->getSheet(0);
                $contents = [];
                $lastRow = $workSheet->getHighestRow();
                $cols = $workSheet->getHighestColumn();

                $char = 'A';
                $charArr = [];

                for($i = 0; $i < $neededCol; $i++) {
                    $charArr[$i] = $char;
                    $char++;
                }
                if ($cols < $charArr[count($charArr) - 1]) {
                    unlink($path);
                    return ['status' => 'nok', 'msg' => "تعداد ستون های فایل شما معتبر نمی باشد"];
                }

                for ($row = 2; $row <= $lastRow; $row++) {
                    if($workSheet->getCell($charArr[0] . $row)->getValue() == null)
                        break;
                    for ($j = 0; $j < count($charArr); $j++) {
                        $tmp = $workSheet->getCell($charArr[$j] . $row)->getValue();
                        $contents[$row - 2][$j] = ($tmp == null) ? 0 : $tmp;
                    }
                }
                unlink($path);

                $file = $_FILES["photos"]["name"];

                if(!empty($file)) {
                    $path = __DIR__ . '/../../../public/tmp/' . $file;
                    $err = uploadCheck($path, "photos", "فایل زیپ تصاویر", 20000000, "zip");
                    if (empty($err)) {
                        upload($path, "photos", "فایل زیپ تصاویر");
                        $zip = new ZipArchive;
                        $res = $zip->open(__DIR__ . "/../../../public/tmp/" . $file);
                        if ($res) {
                            $zip->extractTo(__DIR__ . "/../../../public/tmpZip/");
                            $zip->close();
                            unlink(__DIR__ . "/../../../public/tmp/" . $file);
                        }
                        else
                            return ['status' => 'nok', 'msg' => "اشکالی در باز کردن فایل زیپ داده شده به وجود آمده است"];

                        return ['status' => 'ok', 'contents' => $contents];
                    }

                    return ['status' => 'nok', 'msg' => $err];
                }

                return ['status' => 'nok', 'msg' => "لطفا فایل تصاویر را آپلود نمایید"];
            }
            return ['status' => 'nok', 'msg' => $err];
        }

        return ["status" => 'nok', 'msg' => "لطفا فایل اکسل محتوا را آپلود نمایید"];
    }

    public function doUploadMainContent() {

        if(isset($_POST["kindPlaceId"]) && isset($_FILES["content"]) && isset($_FILES["photos"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $neededCol = -1;

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                    $neededCol = 74;
                    break;
                case getValueInfo('amaken'):
                    $neededCol = 52;
                    break;
                case getValueInfo('restaurant'):
                    $neededCol = 50;
                    break;
            }

            if($neededCol == -1)
                return Redirect::route('uploadMainContent');

            $msg = $this->preprocess($neededCol);
            if($msg["status"] == "nok")
                return view('config.uploadMainContent', ['msg' => $msg["msg"], 'kindPlaceId' => $kindPlaceId, 'places' => Place::all()]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                    $msg = $this->uploadHotel($msg["contents"]);
                    break;
                case getValueInfo("amaken"):
                    $msg = $this->uploadAmaken($msg["contents"]);
                    break;
                case getValueInfo("restaurant"):
                    $msg = $this->uploadRestaurant($msg["contents"]);
                    break;
            }

            if(!empty($msg)) {
                return view('config.uploadMainContent', ['msg' => $msg, 'kindPlaceId' => $kindPlaceId, 'places' => Place::all()]);
            }

        }

        return Redirect::route('uploadMainContent');
    }

    public function uploadMainContent() {
        return view('config.uploadMainContent', ['msg' => '', 'kindPlaceId' => getValueInfo('hotel'),
            'places' => Place::all()]);
    }


    public function topInCity($cityId = '')
    {
        if($cityId == ''){
            return view('content.city.topInCity.choose');
        }
        else{
            $city = Cities::find($cityId);

            if($city == null){
                return \redirect(url('topInCity'));
            }
            else{
                $specialAdvise = SpecialAdvice::where('cityId', $cityId)->get();
                $placeId = Place::where('name', 'اماکن')->OrWhere('name','رستوران')->OrWhere('name','هتل')->get();

                $amakenId = Place::where('name', 'اماکن')->first();
                $restaurantId = Place::where('name', 'رستوران')->first();
                $hotelId = Place::where('name', 'هتل')->first();

                foreach ($specialAdvise as $item){
                    if($item->kindPlaceId == $amakenId->id) {
                        $item->kindPlaceName = $amakenId->name;
                        $item->name = Amaken::find($item->placeId)->name;
                    }
                    elseif($item->kindPlaceId == $restaurantId->id) {
                        $item->kindPlaceName = $restaurantId->name;
                        $item->name = Restaurant::find($item->placeId)->name;
                    }
                    elseif($item->kindPlaceId == $hotelId->id) {
                        $item->kindPlaceName = $hotelId->name;
                        $item->name = Hotel::find($item->placeId)->name;
                    }
                }

                return view('content.city.topInCity.specialInCity', compact(['specialAdvise', 'city', 'placeId']));
            }
        }
    }

    public function findInCity(Request $request)
    {

        $amakenId = Place::where('name', 'اماکن')->first();
        $restaurantId = Place::where('name', 'رستوران')->first();
        $hotelId = Place::where('name', 'هتل')->first();

        if($request->kind == $amakenId->id){
            $find = DB::select('SELECT name, id FROM amaken WHERE name LIKE "%' . $request->name . '%" and cityId = ' . $request->cityId);
        }
        elseif($request->kind == $restaurantId->id){
            $find = DB::select('SELECT name, id FROM restaurant WHERE name LIKE "%' . $request->name . '%" and cityId = ' . $request->cityId);
        }
        elseif($request->kind == $hotelId->id){
            $find = DB::select('SELECT name, id FROM hotels WHERE name LIKE "%' . $request->name . '%" and cityId = ' . $request->cityId);
        }

        echo json_encode($find);
        return;
    }

    public function topInCityStore(Request $request)
    {
        if($request->replace == 0){
            $new = new SpecialAdvice();
            $new->cityId = $request->cityId;
            $new->placeId = $request->placeId;
            $new->kindPlaceId = $request->kindPlaceId;
            $new->save();
        }
        else{
            $change = SpecialAdvice::find($request->replace);

            if($change != null){
                $change->cityId = $request->cityId;
                $change->placeId = $request->placeId;
                $change->kindPlaceId = $request->kindPlaceId;
                $change->save();
            }

        }

        return \redirect()->back();
    }

    private function storePlaceTags($kindPlaceId, $placeId, $newTags){
        $placeTags = PlaceTag::where(['kindPlaceId' => $kindPlaceId, 'placeId' => $placeId])->get();
        $notInId = [];
        $inDataBaseTag = [];
        $newTag = [];
        foreach ($placeTags as $item){
            array_push($inDataBaseTag, $item->tag);
            if(!in_array($item->tag, $newTags))
                array_push($notInId, $item);
        }
        foreach ($newTags as $item){
            if(!in_array($item, $inDataBaseTag) && $item != null)
                array_push($newTag, $item);
        }

        $saveNewTagCount = 0;
        foreach ($notInId as $item){
            $store = false;
            for($i = 0; $i < count($newTag); $i++){
                if($newTag[$i] != null){
                    $item->tag = $newTag[$i];
                    $item->save();
                    $newTag[$i] = null;
                    $store = true;
                    $saveNewTagCount++;
                    break;
                }
            }
            if(!$store)
                $item->delete();
        }
        if(count($newTag) > $saveNewTagCount){
            foreach ($newTag as $tag){
                if($tag != null){
                    $newTagStore = new PlaceTag();
                    $newTagStore->kindPlaceId = $kindPlaceId;
                    $newTagStore->placeId = $placeId;
                    $newTagStore->tag = $tag;
                    $newTagStore->save();
                }
            }
        }
    }

    private function storePlaceFeatures($kindPlaceId, $placeId, $features){
        $existFeatures = PlaceFeatureRelation::where(['kindPlaceId' => $kindPlaceId, 'placeId' => $placeId])->get();
        $has = array();
        foreach ($existFeatures as $item) {
            if (in_array($item->featureId, $features))
                array_push($has, $item->featureId);
            else
                $item->delete();
        }

        foreach ($features as $item) {
            if (!in_array($item, $has)) {
                $newFeature = new PlaceFeatureRelation();
                $newFeature->placeId = $placeId;
                $newFeature->featureId = $item;
                $newFeature->kindPlaceId = $kindPlaceId;
                $newFeature->save();
            }
        }

    }


    public function insertTagsToDB($num1 = 2, $num2 = 2)
    {
        $location = __DIR__ . '/../../../public/tagExcel';
        if(is_dir($location)){
            $dirs = scandir($location);
            if(count($dirs) > $num1){
                $nLoc = $location . '/' . $dirs[$num1];
                $folder = $dirs[$num1];
                if(is_dir($nLoc)){
                    $inDirs = scandir($nLoc);
                    if(count($inDirs) > $num2){
                        switch ($folder){
                            case 'amaken':
                                $kindPlaceId = 1;
                                break;
                            case 'rest':
                                $kindPlaceId = 3;
                                break;
                            case 'hotels':
                                $kindPlaceId = 4;
                                break;
                            case 'majara':
                                $kindPlaceId = 6;
                                break;
                            case 'sogat':
                                $kindPlaceId = 10;
                                break;
                            case 'food':
                                $kindPlaceId = 11;
                                break;
                            default:
                                dd('error');
                                break;
                        }
                        $location = __DIR__ . '/../../../public/tagExcel/' . $folder . '/' . $inDirs[$num2];

                        $msg = $this->storeTagExcel($kindPlaceId, $location, $inDirs[$num2]);

                        $num2++;
                        $nextUrl = url('insertTagsToDB/' . $num1 . '/' . $num2);

                        return view('TagUploade/    showTagUpload', compact(['nextUrl', 'msg']));
                    }
                    else {
                        $num1++;
                        $num2 = 2;
                        return \redirect(\url('insertTagsToDB/' . $num1 . '/' . $num2));
                    }
                }
                else
                    dd('error in :' . $num1, $num2);
            }
            else
                dd('end');
        }
    }
    private function storeTagExcel($kindPlaceId, $inputFileName, $file){

        $kindPlace = Place::find($kindPlaceId);
        if($kindPlace == null){
            dd('error');
            return;
        }
        else
            $tableName = $kindPlace->tableName;

        $excelReader = PHPExcel_IOFactory::createReaderForFile($inputFileName);
        $excelObj = $excelReader->load($inputFileName);
        $workSheet = $excelObj->getSheet(0);
        $lastRow = $workSheet->getHighestRow();
        $cols = $workSheet->getHighestColumn();

        $rowCount = 0;
        $tagCount = 0;
        $contents = [];
        $char = 'A';
        $charArr = [];
        $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        $cols = [];
        for($i = 0; $i < count($alpha); $i++){
            if($i == 0){
                $cols = $alpha;
            }
            for($j = 0; $j < count($alpha); $j++)
                array_push($cols, $alpha[$i].''.$alpha[$j]);
        }
        for ($row = 2; $row <= $lastRow; $row++) {
            if($workSheet->getCell($cols[0] . $row)->getValue() == null)
                break;

            $contents[$row - 2] = [];
            for ($j = 0; $j < count($cols); $j++) {
                $tmp = $workSheet->getCell($cols[$j] . $row)->getValue();
                if($tmp != null && $j != 0) {
                    $contents[$row - 2][count($contents[$row - 2])] = $tmp;
                }
            }
        }

        $msg = 'in File : ' . $file;
        $msg .= '<br>KindPlaceId : ' . $kindPlaceId;

        $msg .= '<ul>';

        $sqlQuery = 'INSERT INTO `placeTags` (`id`, `kindPlaceId`, `placeId`, `tag`) VALUES ';
        $val = '';
        foreach ($contents as $item){
            $place = DB::table($tableName)->where('name', $item[0])->first();
            if($place != null){
                $rowCount++;
                PlaceTag::where(['placeId' => $place->id, 'kindPlaceId' => $kindPlaceId])->delete();
                for($i = 1; $i < count($item); $i++){
                    if($val != '')
                        $val .= ', ';
                    $val .= '(NULL, ' . $kindPlaceId . ', ' . $place->id . ', "' . $item[$i] . '")';
                    $tagCount++;
                }
            }
            else
                $msg .= '<li>  error in : ' . $item[0] . '</li>';
        }
        $msg .= '</ul>';

        $msg .= '<br>از  ' . count($contents) . ' مکان  :' . $rowCount . ' اضافه شد.';

        if($val != '')
            DB::select($sqlQuery . $val);

        return $msg;
    }

    public function insertVillageToDBPage()
    {
        return view('TagUploade.showVillageUpload');
    }
    public function insertVillageDB(Request $request){
        $number = $request->number;

        $location = __DIR__ . '/../../../public/tagExcel/village.xlsx';
        if(is_file($location)){

            $excelReader = PHPExcel_IOFactory::createReaderForFile($location);
            $excelObj = $excelReader->load($location);
            $workSheet = $excelObj->getSheet(0);
            $lastRow = $workSheet->getHighestRow();
            $cols = $workSheet->getHighestColumn();

            $rowCount = 0;
            $tagCount = 0;
            $contents = [];
            $cols = ['A', 'B', 'C'];
            for ($row = $number; $row <= $lastRow && $row < ($number + 500); $row++) {
                $contents[$row-1] = [];
                for ($j = 0; $j < count($cols); $j++) {
                    $tmp = $workSheet->getCell($cols[$j] . $row)->getValue();
                    $contents[$row-1][count($contents[$row-1])] = $tmp;
                }
            }
            $lastGetRow = $row;

            $stateError = $request->stateErr;
            $cityError = $request->cityErr;
            $dupError = $request->dupErr;

            if($stateError == null)
                $stateError = [];
            if($cityError == null)
                $cityError = [];
            if($dupError == null)
                $dupError = [];

            $sqlQuery = 'INSERT INTO `cities` (`id`, `name`, `x`, `y`, `stateId`, `description`, `image`, `isVillage`) VALUES ';
            $val = '';
            foreach ($contents as $item){
                $state = State::where('name', $item[1])->first();
                if($state != null){
                    $city = Cities::where('stateId', $state->id)->where('name', $item[2])->where('isVillage', 0)->first();
                    if($city != null){
                        $village = Cities::where('isVillage', $city->id)->where('name', $item[0])->first();
                        if($village == null){
                            $rowCount++;
                            if($val != '')
                                $val .= ', ';
                            $val .= '(NULL, "' . $item[0] . '", 0, 0, ' . $state->id . ', NULL, NULL, ' . $city->id . ')';
                        }
                        else{
                            if(!in_array($item[0], $dupError))
                                array_push($dupError, $item[0]);
                        }
                    }
                    else{
                        if(!in_array($item[2], $cityError))
                            array_push($cityError, $item[2]);
                    }
                }
                else {
                    if(!in_array($item[1], $stateError))
                        array_push($stateError, $item[1]);
                }
            }

            if($val != '')
                DB::select($sqlQuery . $val);

            echo json_encode(['status' => 'ok', 'stateErr' => $stateError, 'cityErr' => $cityError, 'dupErr' => $dupError, 'added' => $rowCount, 'lastGetRow' => $lastGetRow]);

            return;
        }
    }

    public function addNEwCityDB()
    {
        $input =[
            [
                'name' => 'چاراویماق',
                'stateName' => 'آذربایجان شرقی'
            ],
            [
                'name' => 'خداآفرین',
                'stateName' => 'آذربایجان شرقی'
            ],
            [
                'name' => 'میانه',
                'stateName' => 'آذربایجان شرقی'
            ],
            [
                'name' => 'چالدران',
                'stateName' => 'آذربایجان غربی'
            ],
            [
                'name' => 'مهاباد',
                'stateName' => 'آذربایجان غربی'
            ],
            [
                'name' => 'کوثر',
                'stateName' => 'اردبیل'
            ],
            [
                'name' => 'فریدن',
                'stateName' => 'اصفهان'
            ],
            [
                'name' => 'لنجان',
                'stateName' => 'اصفهان'
            ],
            [
                'name' => 'سمیرم سفلی',
                'stateName' => 'اصفهان'
            ],
            [
                'name' => 'چرداول',
                'stateName' => 'ایلام'
            ],
            [
                'name' => 'دشتی',
                'stateName' => 'بوشهر'
            ],
            [
                'name' => 'تنگستان',
                'stateName' => 'بوشهر'
            ],
            [
                'name' => 'برخوار و میمه',
                'stateName' => 'اصفهان'
            ],
            [
                'name' => 'دشتستان',
                'stateName' => 'بوشهر'
            ],
            [
                'name' => 'دهلران',
                'stateName' => 'ایلام'
            ],
            [
                'name' => 'کوهرنگ',
                'stateName' => 'چهارمحال و بختیاری'
            ],
            [
                'name' => 'زیرکوه',
                'stateName' => 'خراسان جنوبی'
            ],
            [
                'name' => 'درمیان',
                'stateName' => 'خراسان جنوبی'
            ],
            [
                'name' => 'طبس',
                'stateName' => 'خراسان جنوبی'
            ],
            [
                'name' => 'کیار',
                'stateName' => 'چهارمحال و بختیاری'
            ],
            [
                'name' => 'قائنات',
                'stateName' => 'خراسان جنوبی'
            ],
            [
                'name' => 'مهولات',
                'stateName' => 'خراسان رضوی'
            ],
            [
                'name' => 'مانه و سملقان',
                'stateName' => 'خراسان شمالی'
            ],
            [
                'name' => 'خدابنده',
                'stateName' => 'زنجان'
            ],
            [
                'name' => 'طارم',
                'stateName' => 'زنجان'
            ],
            [
                'name' => 'ایجرود',
                'stateName' => 'زنجان'
            ],
            [
                'name' => 'کارون',
                'stateName' => 'خوزستان'
            ],
            [
                'name' => 'اندیکا',
                'stateName' => 'خوزستان'
            ],
            [
                'name' => 'دشتیاری',
                'stateName' => 'سیستان و بلوچستان'
            ],
            [
                'name' => 'هیرمند',
                'stateName' => 'سیستان و بلوچستان'
            ],
            [
                'name' => 'نیمروز',
                'stateName' => 'سیستان و بلوچستان'
            ],
            [
                'name' => 'هامون',
                'stateName' => 'سیستان و بلوچستان'
            ],
            [
                'name' => 'دلگان',
                'stateName' => 'سیستان و بلوچستان'
            ],
            [
                'name' => 'تفتان',
                'stateName' => 'سیستان و بلوچستان'
            ],
            [
                'name' => 'کوه چنار',
                'stateName' => 'فارس'
            ],
            [
                'name' => 'سرچهان',
                'stateName' => 'فارس'
            ],
            [
                'name' => 'البرز',
                'stateName' => 'قزوین'
            ],
            [
                'name' => 'ریگان',
                'stateName' => 'کرمان'
            ],
            [
                'name' => 'کرمانشاه',
                'stateName' => 'کرمانشاه'
            ],
            [
                'name' => 'سردشت (بشاگرد)',
                'stateName' => 'هرمزگان'
            ],
            [
                'name' => 'حاجی‌آباد(هرمزگان)',
                'stateName' => 'هرمزگان'
            ]
        ];

        $sqlQuery = 'INSERT INTO `cities` (`id`, `name`, `x`, `y`, `stateId`, `description`, `image`, `isVillage`) VALUES ';
        $val = '';

        foreach ($input as $item){
            $state = State::where('name', $item['stateName'])->first();
            if($state != null) {
                $city = Cities::where('name', $item['name'])->where('isVillage', 0)->first();
                if($city == null){
                    if ($val != '')
                        $val .= ', ';
                    $val .= '(NULL, "' . $item["name"] . '", 0, 0, ' . $state->id . ', NULL, NULL, 0)';
                }
                else{
                    echo 'city: ' . $city->name . '<br>';
                }
            }
            else{
                echo 'not state: ' . $state->name . '<br>';
            }
        }

        DB::select($sqlQuery . $val);

    }
}
