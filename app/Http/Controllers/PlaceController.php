<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Hotel;
use App\models\Majara;
use App\models\Place;
use App\models\Restaurant;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller {

    public function changeContent($city, $mode) {

        switch ($mode) {
            case getValueInfo("hotel"):
            default:
                return $this->changeHotelContent($city);
            case getValueInfo('amaken'):
                return $this->changeAmakenContent($city);
            case getValueInfo('restaurant'):
                return $this->changeRestaurantContent($city);
            case getValueInfo('majara'):
                return $this->changeMajaraContent($city);

        }
    }

    private function changeHotelContent($cityId) {

        $places = Hotel::whereCityId($cityId)->get();

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

        return view('content.changeHotel', ['places' => $places, 'kind_ids' => json_encode($kind_ids)]);
    }

    private function changeMajaraContent($cityId) {

        $places = Majara::whereCityId($cityId)->get();

        return view('content.changeMajara', ['places' => $places]);

    }

    private function changeRestaurantContent($cityId) {

        $places = Restaurant::whereCityId($cityId)->get();

        $kind_ids = [
            ['name' => 'رستوران', 'id' => getValueInfo('restaurantMode')],
            ['name' => 'فست فود', 'id' => getValueInfo('fastfood')]
        ];

        return view('content.changeRestaurant', ['places' => $places, 'kind_ids' => json_encode($kind_ids)]);
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

    private function changeAmakenContent($cityId) {

        $places = Amaken::whereCityId($cityId)->get();

        return view('content.changeAmaken', ['places' => $places]);
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

    public function chooseCity($mode) {

        $url = route('root');

        switch ($mode) {
            case "seo":
                $url = $url . "/changeSeo/";
                break;
            case "content":
                $url = $url . "/changeContent/";
                return view('content.chooseCity', ['url' => $url, 'mode' => 2, 'places' => Place::all()]);
        }

        return view('content.chooseCity', ['url' => $url, 'mode' => 1]);
    }


    public function searchForCity() {
        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT cities.id, cities.name as cityName, state.name as stateName FROM cities, state WHERE cities.stateId = state.id and  cities.name LIKE '%$key%' ");
        echo json_encode($cities);
    }

}
