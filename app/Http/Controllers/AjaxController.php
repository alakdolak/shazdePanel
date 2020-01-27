<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Cities;
use App\models\LogModel;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getCityWithState(Request $request)
    {
        if(isset($request->id)){
            $city = Cities::where('stateId', $request->id)->get();
            echo json_encode($city);
        }
        else
            echo json_encode('nok');

        return ;
    }

    public function findPlace(Request $request)
    {
        if(isset($request->value) && isset($request->kindPlaceId)){
            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $value = $request->value;

            $result = array();

            switch ($kindPlaceId) {
                case 1:
                    $tmp =\DB::select("SELECT amaken.id, amaken.name as targetName, cities.name as cityName, state.name as stateName from amaken, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(amaken.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr){
                        $itr->mode = "amaken";
                        $itr->kindPlaceId = $kindPlaceId;
                    }
                    break;
                case 3:
                    $tmp =\DB::select("SELECT restaurant.id, restaurant.name as targetName, cities.name as cityName, state.name as stateName from restaurant, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(restaurant.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr){
                        $itr->kindPlaceId = $kindPlaceId;
                        $itr->mode = "restaurant";
                    }
                    break;
                case 4:
                    $tmp =\DB::select("SELECT hotels.id, hotels.name as targetName, cities.name as cityName, state.name as stateName from hotels, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(hotels.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr){
                        $itr->mode = "hotel";
                        $itr->kindPlaceId = $kindPlaceId;
                    }

                    break;
                case 6:
                    $tmp =\DB::select("SELECT majara.id, majara.name as targetName, cities.name as cityName, state.name as stateName from majara, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(majara.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr){
                        $itr->mode = "majara";
                        $itr->kindPlaceId = $kindPlaceId;
                    }
                    break;
                case 10:
                    $tmp =\DB::select("SELECT sogatSanaies.id, sogatSanaies.name as targetName, cities.name as cityName, state.name as stateName from sogatSanaies, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(sogatSanaies.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr){
                        $itr->mode = "sogatSanaies";
                        $itr->kindPlaceId = $kindPlaceId;
                    }
                    break;
                case 11:
                    $tmp =\DB::select("SELECT mahaliFood.id, mahaliFood.name as targetName, cities.name as cityName, state.name as stateName from mahaliFood, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(mahaliFood.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr){
                        $itr->mode = "mahaliFood";
                        $itr->kindPlaceId = $kindPlaceId;
                    }
                    break;
                case 0:
                default:
                    $acitivityId = Activity::where('name', 'مشاهده')->first();
                    $tmp =\DB::select("SELECT amaken.id, amaken.name as targetName, cities.name as cityName, state.name as stateName from amaken, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(amaken.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr) {
                        $condition = ['activityId' => $acitivityId->id, 'placeId' => $itr->id, 'kindPlaceId' => 1];
                        $itr->see = LogModel::where($condition)->count();
                        $itr->mode = "amaken";
                        $itr->kindPlaceId = 1;
                    }
                    $result = array_merge($result, $tmp);

                    $tmp =\DB::select("SELECT restaurant.id, restaurant.name as targetName, cities.name as cityName, state.name as stateName from restaurant, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(restaurant.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr) {
                        $condition = ['activityId' => $acitivityId->id, 'placeId' => $itr->id, 'kindPlaceId' => 3];
                        $itr->see = LogModel::where($condition)->count();
                        $itr->mode = "restaurant";
                        $itr->kindPlaceId = 3;
                    }
                    $result = array_merge($result, $tmp);

                    $tmp =\DB::select("SELECT hotels.id, hotels.name as targetName, cities.name as cityName, state.name as stateName from hotels, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(hotels.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr) {
                        $condition = ['activityId' => $acitivityId->id, 'placeId' => $itr->id, 'kindPlaceId' => 4];
                        $itr->see = LogModel::where($condition)->count();
                        $itr->mode = "hotel";
                        $itr->kindPlaceId = 4;
                    }
                    $result = array_merge($result, $tmp);

                    $tmp =\DB::select("SELECT majara.id, majara.name as targetName, cities.name as cityName, state.name as stateName from majara, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(majara.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr) {
                        $condition = ['activityId' => $acitivityId->id, 'placeId' => $itr->id, 'kindPlaceId' => 6];
                        $itr->see = LogModel::where($condition)->count();
                        $itr->mode = "majara";
                        $itr->kindPlaceId = 6;
                    }
                    $result = array_merge($result, $tmp);

                    $tmp =\DB::select("SELECT sogatSanaies.id, sogatSanaies.name as targetName, cities.name as cityName, state.name as stateName from sogatSanaies, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(sogatSanaies.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr) {
                        $condition = ['activityId' => $acitivityId->id, 'placeId' => $itr->id, 'kindPlaceId' => 10];
                        $itr->see = LogModel::where($condition)->count();
                        $itr->mode = "sogatSanaies";
                        $itr->kindPlaceId = 10;
                    }
                    $result = array_merge($result, $tmp);

                    $tmp =\DB::select("SELECT mahaliFood.id, mahaliFood.name as targetName, cities.name as cityName, state.name as stateName from mahaliFood, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(mahaliFood.name, ' ', '') LIKE '%$value%'");
                    foreach ($tmp as $itr) {
                        $condition = ['activityId' => $acitivityId->id, 'placeId' => $itr->id, 'kindPlaceId' => 11];
                        $itr->see = LogModel::where($condition)->count();
                        $itr->mode = "mahaliFood";
                        $itr->kindPlaceId = 11;
                    }
                    break;
            }

            $result = array_merge($result, $tmp);

            echo json_encode($result);
        }
    }
}
