<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\Majara;
use App\models\Place;
use App\models\Restaurant;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller {
    
    public function changeSeo($city, $mode, $wantedKey = -1, $selectedMode = -1) {

        $out = [];
        $counter = 0;

        if($selectedMode == -1 || $selectedMode == getValueInfo('hotel')) {

            if($mode)
                $places = Hotel::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from hotels h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('hotel');
                $place->kindPlaceName = 'هتل';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('amaken')) {

            if($mode)
                $places = Amaken::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from amaken h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('amaken');
                $place->kindPlaceName = 'اماکن';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('restaurant')) {

            if($mode)
                $places = Restaurant::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from restaurant h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('restaurant');
                $place->kindPlaceName = 'رستوران';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('adab')) {

            $places = Adab::whereStateId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('adab');
                $place->kindPlaceName = 'آداب';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('majara')) {

            if($mode)
                $places = Majara::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from majara h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('majara');
                $place->kindPlaceName = 'ماجرا';
                $out[$counter++] = $place;
            }
        }

        return view('content.changeSeo', ['places' => $out, 'wantedKey' => $wantedKey,
            'selectedMode' => $selectedMode, 'modes' => Place::all(), 'showCity' => !$mode,
            'pageURL' => route('changeSeo', ['city' => $city, 'mode' => $mode, 'wantedKey' => $wantedKey])]);
    }

    public function doChangeSeo() {

        if(isset($_POST["id"]) && isset($_POST["kindPlaceId"]) && isset($_POST["val"]) &&
            isset($_POST["mode"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $id = makeValidInput($_POST["id"]);
            $mode = makeValidInput($_POST["mode"]);
            $val = makeValidInput($_POST["val"]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                default:
                    try {
                        DB::update('update hotels set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                    break;
                case getValueInfo('adab'):
                    break;
                case getValueInfo('amaken'):
                    break;
                case getValueInfo('restaurant'):
                    break;
                case getValueInfo('majara'):
                    break;
            }

        }

    }

}
