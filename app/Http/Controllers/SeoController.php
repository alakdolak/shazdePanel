<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\Majara;
use App\models\Restaurant;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller {
    
    public function changeSeo($city) {

        $out = [];
        $counter = 0;

        $places = Hotel::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
            'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
            'tag13', 'tag14', 'tag15'
        )->get();

        foreach ($places as $place) {
            $place->kindPlaceId = getValueInfo('hotel');
            $place->kindPlaceName = 'هتل';
            $out[$counter++] = $place;
        }

        $places = Amaken::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
            'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
            'tag13', 'tag14', 'tag15'
        )->get();

        foreach ($places as $place) {
            $place->kindPlaceId = getValueInfo('amaken');
            $place->kindPlaceName = 'اماکن';
            $out[$counter++] = $place;
        }

        $places = Restaurant::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
            'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
            'tag13', 'tag14', 'tag15'
        )->get();

        foreach ($places as $place) {
            $place->kindPlaceId = getValueInfo('restaurant');
            $place->kindPlaceName = 'رستوران';
            $out[$counter++] = $place;
        }

        $places = Adab::whereStateId(Cities::whereId($city)->stateId)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
            'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
            'tag13', 'tag14', 'tag15'
        )->get();

        foreach ($places as $place) {
            $place->kindPlaceId = getValueInfo('adab');
            $place->kindPlaceName = 'آداب';
            $out[$counter++] = $place;
        }

        $places = Majara::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
            'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
            'tag13', 'tag14', 'tag15'
        )->get();

        foreach ($places as $place) {
            $place->kindPlaceId = getValueInfo('majara');
            $place->kindPlaceName = 'ماجرا';
            $out[$counter++] = $place;
        }

        return view('content.changeSeo', ['places' => $out]);
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
