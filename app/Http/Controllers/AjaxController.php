<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Cities;
use App\models\FoodMaterial;
use App\models\LogModel;
use App\models\Place;
use App\models\State;
use App\models\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function testUploadPic(Request $request)
    {
        if($_FILES['pic'] &&  $_FILES['pic']['error'] == 0){
            $fileName = time().$_FILES['pic']['name'];
            $location = __DIR__ .'/../../../../assets/_images/testPics';
            if(!is_dir($location))
                mkdir($location);

            $location .= '/' . $fileName;

            if(move_uploaded_file($_FILES['pic']['tmp_name'], $location)){
                $url = \URL::asset('_images/testPics/' . $fileName);
                $size = filesize($location);
                $size = floor(($size/1000)) . ' KB';

                echo json_encode(['status' => 'ok', 'result' => ['url' => $url, 'size' => $size]]);
            }
            else
                echo json_encode(['status' => 'nok2']);
        }
        else
            echo json_encode(['status' => 'nok1']);

        return;
    }

    public function searchForMaterialFood(Request $request)
    {
        $value = $request->value;
        $material = FoodMaterial::where('name', 'LIKE', '%'.$value.'%')->pluck('name')->toArray();
        return response()->json($material);
    }

    public function searchPlacesAndCity()
    {
        $result = [];
        $text = $_GET['text'];
        $kindPlace = Place::whereNotNull('tableName')
                            ->where('mainSearch', 1)
                            ->get();

        $states = State::where('name','LIKE', '%'.$text.'%')->select(['id', 'name'])->get();
        foreach ($states as $item)
            array_push($result, ['id' => $item->id, 'name' => 'استان '.$item->name, 'kind' => 'state']);

        $cities = Cities::where('name','LIKE', '%'.$text.'%')->where('isVillage', 0)->select(['id', 'name'])->get();
        foreach ($cities as $item)
            array_push($result, ['id' => $item->id, 'name' => 'شهر '.$item->name, 'kind' => 'city']);

        foreach ($kindPlace as $kp){
            $pls = DB::table($kp->tableName)
                        ->where('name','LIKE', '%'.$text.'%')
                        ->select(['id', 'name'])
                        ->get();
            foreach ($pls as $item){
                $item->kind = $kp->id;
                array_push($result, $item);
            }
        }

        return response()->json(['status' => 'ok', 'result' => $result]);
    }

    public function searchUser()
    {
        $value = $_GET['username'];
        $users = User::where('username', 'LIKE', '%'.$value.'%')->select(['id', 'username', 'first_name', 'last_name'])->get();

        return response()->json(['status' => 'ok', 'result' => $users]);
    }

    public function searchTag()
    {
        $value = $_GET['value'];
        $tags = Tag::where('name', 'LIKE','%'.$value.'%')->get();
        return response()->json(['status' => 'ok', 'result' => $tags]);
    }
}
