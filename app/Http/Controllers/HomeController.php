<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\AdminLog;
use App\models\Amaken;
use App\models\Boomgardy;
use App\models\Cities;
use App\models\ConfigModel;
use App\models\LogModel;
use App\models\PhotographersPic;
use App\models\PlaceFeatureRelation;
use App\models\PlaceFeatures;
use App\models\PostComment;
use App\models\State;
use App\models\VideoComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller {

    public function home() {
        $photographerNotAgree = PhotographersPic::where('status', 0)->get();

        $acitvity = Activity::where('name', 'نظر')->first();
        $newReviews = LogModel::where('activityId', $acitvity->id)->where('confirm', 0)->count();

        $activity = Activity::where('name', 'پاسخ')->first();
        $reviewComment = LogModel::where('confirm', 0)->where('activityId', $activity->id)->count();
        $postComment = PostComment::where('status', 0)->count();

        $newCommentCount = $reviewComment + $postComment;

        $newVideoComments = VideoComment::where('confirm', 0)->count();

        $reportsId = Activity::where('name', 'گزارش')->first();
        $newReports = LogModel::where('activityId', $reportsId->id)->where('confirm', 0)->count();

        $questionId = Activity::where('name', 'سوال')->first();
        $newQuestions = LogModel::where('activityId', $questionId->id)->where('confirm', 0)->count();

        $ansActivity = Activity::where('name', 'پاسخ')->first();
        $newA = LogModel::where('activityId', $ansActivity->id)->where('confirm', 0)->get();
        foreach ($newA as $item) {
            $relLog = LogModel::find($item->relatedTo);
            while ($relLog->activityId == $ansActivity->id)
                $relLog = LogModel::find($relLog->relatedTo);

            if($relLog->activityId == $questionId->id)
                $newQuestions++;
        }

        return view('home', compact(['photographerNotAgree', 'newReviews', 'newCommentCount', 'newVideoComments', 'newReports', 'newQuestions']));
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



    public function convertAmakenFeatures()
    {
        dd('don');

        $amaken = Amaken::select(['id', 'name', 'keyword'])
                            ->get();
        foreach ($amaken as $item){
            similar_text($item->name, $item->keyword, $percent);
            echo $item->id . ' => '. $percent;
            echo '<br>';
//            if($percent < 70){
//                $item->keyword = $item->name;
//                $item->save();
//            }
        }

        $amakenFeature = PlaceFeatures::where('kindPlaceId', 1)
                        ->where('parent', '!=', 0)
                        ->get();
        $amakenRelated = [];
        $amakenRel = [];

        foreach ($amakenFeature as $item)
            PlaceFeatureRelation::where('featureId', $item->id)->delete();

        foreach ($amakenFeature as $item){
            $amakenRel = [];
            if($item->name == 'تاریخی')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'tarikhi'];
            else if($item->name == 'تفریحی')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'tafrihi'];
            else if($item->name == 'طبیعی')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'tabiatgardi'];
            else if($item->name == 'مرکز شهر')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'markaz'];
            else if($item->name == 'حومه شهر')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'hoome'];
            else if($item->name == 'پر ازدحام')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'shologh'];
            else if($item->name == 'کم ازدحام')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'khalvat'];
            else if($item->name == 'کوهستان')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'kooh'];
            else if($item->name == 'دریا')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'darya'];
            else if($item->name == 'کویر')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'kavir'];
            else if($item->name == 'جنگل')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'jangal'];
            else if($item->name == 'شهری')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'shahri'];
            else if($item->name == 'مدرن')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'modern'];
            else if($item->name == 'تاریخی بنا')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'tarikhibana'];
            else if($item->name == 'بومی')
                $amakenRel = ['featureId' => $item->id, 'colName' => 'boomi'];
            else
                continue;
            array_push($amakenRelated, $amakenRel);
        }

        $insertQuery = 'INSERT INTO placeFeatureRelations (id, placeId, featureId, ans) VALUES';
        $values = '';
        foreach ($amaken as $item){
            foreach ($amakenRelated as $item2){
                if($item[$item2['colName']] == 1){
                    if($values != '')
                        $values .= ', ';
                    $values .= ' (null, ' . $item["id"] . ', ' . $item2["featureId"] . ', null)';
                }
            }
        }
        $insertQuery .= $values;
        DB::select($insertQuery);
        dd('done');
    }

    public function updateDatabase()
    {

    }
}
