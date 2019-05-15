<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Medal;
use App\models\Place;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class MedalController extends Controller {

    public function showMedals() {

        $medals = Medal::all();
        $counter = 0;
        $arr = array();

        foreach($medals as $medal) {
            if($medal->kindPlaceId != -1) {
                $arr[$counter++] = ['name' => $medal->name, 'id' => $medal->id,
                    'pic_1' => URL::asset('../../badges/' . $medal->pic_1),
                    'pic_2' => URL::asset('../../badges/' . $medal->pic_2),
                    "activity" => Activity::whereId($medal->activityId)->name,
                    'floor' => $medal->floor,
                    'kindPlaceId' => Place::whereId($medal->kindPlaceId)->name
                ];
            }
            else {
                $arr[$counter++] = ['name' => $medal->name, 'id' => $medal->id,
                    'pic_1' => URL::asset('../../badges/' . $medal->pic_1),
                    'pic_2' => URL::asset('../../badges/' . $medal->pic_2),
                    "activity" => Activity::whereId($medal->activityId)->name,
                    'floor' => $medal->floor,
                    'kindPlaceId' => "مهم نیست"
                ];
            }
        }

        return view("levels.medals", array('medals' => $arr, 'mode' => 'see'));
    }

    public function addMedal() {

        $err = "";

        if(isset($_POST["addMedal"]) && isset($_POST["medalName"]) && isset($_POST["kindPlaceId"]) &&
            isset($_FILES["beforeImg"]) && isset($_FILES["afterImg"])) {

            $file = $_FILES["beforeImg"];
            $targetFile = __DIR__ . "/../../../../badges/" . $file["name"];
            if(!file_exists($targetFile)) {
                $err = uploadCheck($targetFile, "beforeImg", "افزودن مدال جدید", 3000000, -1);
                if(empty($err)) {
                    $err = upload($targetFile, "beforeImg", "افزودن مدال جدید");
                    if(empty($err)) {
                        $file = $_FILES["afterImg"];
                        $targetFile = __DIR__ . "/../../../../badges/" . $file["name"];
                        if(!file_exists($targetFile)) {
                            $err = uploadCheck($targetFile, "afterImg", "افزودن مدال جدید", 3000000, -1);
                            if (empty($err)) {
                                $err = upload($targetFile, "afterImg", "افزودن مدال جدید");
                                if (empty($err)) {
                                    $medal = new Medal();
                                    $medal->name = makeValidInput($_POST["medalName"]);
                                    $medal->activityId = makeValidInput($_POST["activity"]);
                                    $medal->floor = makeValidInput($_POST["floor"]);
                                    $medal->pic_1 = $_FILES["beforeImg"]['name'];
                                    $medal->pic_2 = $_FILES["afterImg"]['name'];
                                    $medal->kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
                                    try {
                                        $medal->save();
                                        return Redirect::route('medals');
                                    }
                                    catch (Exception $e) {
                                        $err = "مدال مورد نظر در سامانه موجود است";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $file = $_FILES["beforeImg"];
            $targetFile = __DIR__ . "/../../../../badges/" . $file["name"];
            if(file_exists($targetFile))
                unlink($targetFile);

            $file = $_FILES["afterImg"];
            $targetFile = __DIR__ . "/../../../../badges/" . $file["name"];
            if(file_exists($targetFile))
                unlink($targetFile);

        }

        $medals = Medal::all();
        $counter = 0;
        $arr = [];
        foreach($medals as $medal) {
            if($medal->kindPlaceId != -1) {
                $arr[$counter++] = ['name' => $medal->name, 'id' => $medal->id,
                    'pic_1' => URL::asset('../../badges/' . $medal->pic_1),
                    'pic_2' => URL::asset('../../badges/' . $medal->pic_2),
                    "activity" => Activity::whereId($medal->activityId)->name,
                    'floor' => $medal->floor,
                    'kindPlaceId' => Place::whereId($medal->kindPlaceId)->name
                ];
            }
            else {
                $arr[$counter++] = ['name' => $medal->name, 'id' => $medal->id,
                    'pic_1' => URL::asset('../../badges/' . $medal->pic_1),
                    'pic_2' => URL::asset('../../badges/' . $medal->pic_2),
                    "activity" => Activity::whereId($medal->activityId)->name,
                    'floor' => $medal->floor,
                    'kindPlaceId' => "مهم نیست"
                ];
            }
        }
        $activities = Activity::all();
        $places = Place::all();

        if(empty($err) && isset($_POST["addMedal"]))
            $err = "فایل مورد نظر در سیستم موجود است";

        return view('levels.medals', array('medals' => $arr, 'mode' => 'add', 'msg' => $err, 'places' => $places,
            'activities' => $activities));
    }

    public function opOnMedal() {

        if(isset($_POST["deleteMedal"])) {
            $medalId = makeValidInput($_POST["deleteMedal"]);
            try {
                $medal = Medal::whereId($medalId);
                if($medal == null)
                    return Redirect::route('levels.medals');

                if(file_exists(__DIR__ . '/../../../../assets/badges/' . $medal->pic_1))
                    unlink(__DIR__ . '/../../../../assets/badges/' . $medal->pic_1);

                if(file_exists(__DIR__ . '/../../../../assets/badges/' . $medal->pic_2))
                    unlink(__DIR__ . '/../../../../assets/badges/' . $medal->pic_2);

                Medal::destroy($medalId);
            }
            catch (Exception $x) {}

            return Redirect::route('medals');
        }

        return Redirect::route('medals');

    }

}