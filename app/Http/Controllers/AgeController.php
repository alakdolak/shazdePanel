<?php

namespace App\Http\Controllers;

use App\models\Age;
use Exception;
use Illuminate\Support\Facades\Redirect;

class AgeController extends Controller {

    public function ages() {
        return view("config.ages", array('ages' => Age::all(), 'mode' => 'see'));
    }

    public function addAge() {

        $msg = "";

        if(isset($_POST["addAge"]) && isset($_POST["ageName"])) {

            $age = new Age();
            $age->name = makeValidInput($_POST["ageName"]);

            try {
                $age->save();
                return Redirect::route('ages');
            }
            catch (Exception $e) {
                $msg = "سن مورد نظر در سامانه موجود است";
            }
        }

        return view("config.ages", array('ages' => Age::all(), 'mode' => 'add', 'msg' => $msg));
    }

    public function opOnAge() {

        if(isset($_POST["ageId"])) {
            $ageId = makeValidInput($_POST["ageId"]);
            Age::destroy($ageId);
            return Redirect::route('ages');
        }

        return Redirect::route('ages');

    }

}