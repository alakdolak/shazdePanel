<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\ConfigModel;
use App\models\Place;
use App\models\SpecialAdvice;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller {

    public function home() {
        return view('home');
    }

    public function login() {
        if(Auth::check())
            return Redirect::route('home');

        return view('login', ['msg' => '']);
    }

    public function doLogin() {

        if(isset($_POST["username"]) && isset($_POST["password"])) {

            $username = makeValidInput($_POST["username"]);
            $password = makeValidInput($_POST["password"]);

            if(Auth::attempt(['username' => $username, 'password' => $password], true)) {

                if(!Auth::user()->status)
                    return view('login', ['msg' => 'حساب کاربری شما فعال نیست']);

                $level = Auth::user()->level;

                if($level != getValueInfo('adminLevel') && $level != getValueInfo('superAdminLevel')) {
                    Auth::logout();
                    Session::flush();
                    return view('login', ['msg' => 'شما اجازه دسترسی به پنل را ندارید']);
                }

                $tmp = new AdminLog();
                $tmp->uId = Auth::user()->id;
                $tmp->mode = getValueInfo('login');
                $tmp->save();

                return Redirect::route('home');
            }

            return view('login', ['msg' => 'نام کاربری یا رمز عبور را اشتباه وارد کرده اید']);
        }

        return view('login', ['msg' => '']);
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        return Redirect::route('login');
    }

    public function determineRadius() {

        if (isset($_POST["saveChange"]) && isset($_POST["radius"])) {
            $config = ConfigModel::first();
            $config->radius = makeValidInput($_POST["radius"]);
            $config->save();

            $tmp = new AdminLog();
            $tmp->mode = getValueInfo('determineRadius');
            $tmp->uId = Auth::user()->id;
            $tmp->save();

            return Redirect::route('determineRadius');
        }

        return view('config.radius', array('radius' => ConfigModel::first()->radius));
    }

    public function totalSearch() {

        if (isset($_POST["key"]) && isset($_POST["kindPlaceId"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);

            $key = makeValidInput($_POST["key"]);
            $key = str_replace(' ', '', $key);
            $key2 = (isset($_POST["key2"]) ? makeValidInput($_POST["key2"]) : '');
            $key2 = str_replace(' ', '', $key2);

            switch ($kindPlaceId) {
                case getValueInfo('amaken'):
                default:
                    if (!empty($key2))
                        $tmp = DB::select("SELECT amaken.id, amaken.name as targetName, cities.name as cityName, state.name as stateName from amaken, cities, state WHERE cityId = cities.id and state.id = cities.stateId and (replace(amaken.name, ' ', '') LIKE '%$key%' or replace(amaken.name, ' ', '') LIKE '%$key2%')");
                    else
                        $tmp = DB::select("SELECT amaken.id, amaken.name as targetName, cities.name as cityName, state.name as stateName from amaken, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(amaken.name, ' ', '') LIKE '%$key%'");
                    break;
                case getValueInfo('restaurant'):
                    if (!empty($key2))
                        $tmp = DB::select("SELECT restaurant.id, restaurant.name as targetName, cities.name as cityName, state.name as stateName from restaurant, cities, state WHERE cityId = cities.id and state.id = cities.stateId and (replace(restaurant.name, ' ', '') LIKE '%$key%' or replace(restaurant.name, ' ', '') LIKE '%$key2%')");
                    else
                        $tmp = DB::select("SELECT restaurant.id, restaurant.name as targetName, cities.name as cityName, state.name as stateName from restaurant, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(restaurant.name, ' ', '') LIKE '%$key%'");
                    break;
                case getValueInfo('hotel'):
                    if (!empty($key2))
                        $tmp = DB::select("SELECT hotels.id, hotels.name as targetName, cities.name as cityName, state.name as stateName from hotels, cities, state WHERE cityId = cities.id and state.id = cities.stateId and (replace(hotels.name, ' ', '') LIKE '%$key%' or replace(hotels.name, ' ', '') LIKE '%$key2%')");
                    else
                        $tmp = DB::select("SELECT hotels.id, hotels.name as targetName, cities.name as cityName, state.name as stateName from hotels, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(hotels.name, ' ', '') LIKE '%$key%'");
                    break;
                case getValueInfo('majara'):
                    if (!empty($key2))
                        $tmp = DB::select("SELECT majara.id, majara.name as targetName, cities.name as cityName, state.name as stateName from majara, cities, state WHERE cityId = cities.id and state.id = cities.stateId and (replace(majara.name, ' ', '') LIKE '%$key%' or replace(majara.name, ' ', '') LIKE '%$key2%')");
                    else
                        $tmp = DB::select("SELECT majara.id, majara.name as targetName, cities.name as cityName, state.name as stateName from majara, cities, state WHERE cityId = cities.id and state.id = cities.stateId and replace(majara.name, ' ', '') LIKE '%$key%'");
                    break;
                case getValueInfo('adab'):
                    if (!empty($key2))
                        $tmp = DB::select("SELECT adab.id, adab.name as targetName, state.name as stateName from adab, state WHERE state.id = stateId and (replace(adab.name, ' ', '') LIKE '%$key%' or replace(adab.name, ' ', '') LIKE '%$key2%')");
                    else
                        $tmp = DB::select("SELECT adab.id, adab.name as targetName, state.name as stateName from adab, state WHERE state.id = stateId and replace(adab.name, ' ', '') LIKE '%$key%'");
                    break;
            }

            echo json_encode($tmp);
        }
    }

    public function changePass() {
        return view('changePass');
    }

    public function doChangePass() {

        if(isset($_POST["newPass"]) && isset($_POST["oldPass"]) && isset($_POST["confirmPass"])) {

            $newPass = makeValidInput($_POST["newPass"]);
            $oldPass = makeValidInput($_POST["oldPass"]);
            $confirmPass = makeValidInput($_POST["confirmPass"]);

            if($newPass != $confirmPass) {
                echo "nok1";
                return;
            }

            $user = Auth::user();

            if(!Hash::check($oldPass, $user->password)) {
                echo "nok2";
                return;
            }

            $user->password = Hash::make($newPass);
            $user->save();
            $tmp = new AdminLog();
            $tmp->uId = Auth::user()->id;
            $tmp->mode = getValueInfo('selfChangePass');
            $tmp->save();

            echo "ok";
        }

    }

    public function specialAdvice() {
        return view('config.specialAdvice', array('kindPlaceIds' => Place::all()));
    }

    public function submitAdvice()
    {

        if (isset($_POST["placeId"]) && isset($_POST["kindPlaceId"]) && isset($_POST["mode"])) {

            $mode = makeValidInput($_POST["mode"]);
            if ($mode > 4 || $mode < 0)
                return "nok";

            $advice = SpecialAdvice::find($mode);
            if ($advice == null) {
                $advice = new SpecialAdvice();
                $advice->id = $mode;
            }

            $advice->placeId = makeValidInput($_POST["placeId"]);
            $advice->kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            try {
                $advice->save();
                return "ok";
            } catch (Exception $x) {
            }
        }

        return "nok";
    }

    public function findPlace() {

        if (isset($_POST["kindPlaceId"]) && isset($_POST["key"])) {
            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $key = makeValidInput($_POST["key"]);
            switch ($kindPlaceId) {
                case 1:
                default:
                    echo json_encode(DB::select("select id, name from amaken WHERE name LIKE '%$key%'"));
                    break;
                case 3:
                    echo json_encode(DB::select("select id, name from restaurant WHERE name LIKE '%$key%'"));
                    break;
                case 4:
                    echo json_encode(DB::select("select id, name from hotels WHERE name LIKE '%$key%'"));
                    break;
            }
        }
    }

}
