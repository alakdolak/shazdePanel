<?php

namespace App\Http\Controllers;

use App\models\Age;
use App\models\ConfigModel;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AgeController extends Controller {

    public function ages() {
        return view("config.ages.index", array('ages' => Age::all(), 'mode' => 'see'));
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

        return view("config.ages.index", array('ages' => Age::all(), 'mode' => 'add', 'msg' => $msg));
    }

    public function opOnAge() {

        if(isset($_POST["ageId"])) {
            $ageId = makeValidInput($_POST["ageId"]);
            Age::destroy($ageId);
            return Redirect::route('ages');
        }

        return Redirect::route('ages');

    }

    public function ageSentences()
    {

        if (isset($_POST["saveChange"]) && isset($_POST["adultInner"])
            && isset($_POST["childInnerMax"]) && isset($_POST["childInnerMin"])
            && isset($_POST["infantInner"]) && isset($_POST["adultExternal"])
            && isset($_POST["childExternalMax"]) && isset($_POST["childExternalMin"])
            && isset($_POST["infantExternal"])) {

            $config = ConfigModel::first();
            $config->adultInner = makeValidInput($_POST["adultInner"]);
            $config->childInnerMax = makeValidInput($_POST["childInnerMax"]);
            $config->childInnerMin = makeValidInput($_POST["childInnerMin"]);
            $config->infantInner = makeValidInput($_POST["infantInner"]);
            $config->adultExternal = makeValidInput($_POST["adultExternal"]);
            $config->childExternalMax = makeValidInput($_POST["childExternalMax"]);
            $config->childExternalMin = makeValidInput($_POST["childExternalMin"]);
            $config->infantExternal = makeValidInput($_POST["infantExternal"]);
            $config->save();
        }

        $config = ConfigModel::first();

        return view('config.ages.ageSentences', array('adultInner' => $config->adultInner,
            'childInnerMax' => $config->childInnerMax,
            'childInnerMin' => $config->childInnerMin,
            'infantInner' => $config->infantInner,
            'adultExternal' => $config->adultExternal,
            'childExternalMax' => $config->childExternalMax,
            'childExternalMin' => $config->childExternalMin,
            'infantExternal' => $config->infantExternal,
            'user' => Auth::user()));

    }

}