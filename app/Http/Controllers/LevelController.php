<?php

namespace App\Http\Controllers;

use App\models\Level;
use Illuminate\Support\Facades\Redirect;

class LevelController extends Controller {

    public function showLevels() {

        $levels = Level::all();

        return view("levels.levels", array('levels' => $levels, 'mode' => 'see'));
    }

    public function addLevel() {

        $msg = "";

        if(isset($_POST["floor"]) && isset($_POST["levelName"])) {
            $level = new Level();
            $level->name = makeValidInput($_POST["levelName"]);
            $level->floor = makeValidInput($_POST["floor"]);

            try {
                $level->save();
                return Redirect::route('levels');
            }
            catch (\Exception $e) {
                $msg = "سطح مورد نظر در سامانه موجود است";
            }
        }

        $levels = Level::all();

        return view('levels.levels', array('levels' => $levels, 'mode' => 'add', 'msg' => $msg));
    }

    public function opOnLevel() {

        if(isset($_POST["deleteLevel"])) {
            $levelId = makeValidInput($_POST["deleteLevel"]);
            Level::destroy($levelId);
            return Redirect::route('levels');
        }

        if(isset($_POST["editLevel"])) {
            $levelId = makeValidInput($_POST["editLevel"]);
            $selectedLevel = Level::whereId($levelId);
            $levels = Level::all();
            return view('levels.levels', array('levels' => $levels, 'selectedLevel' => $selectedLevel,
                'mode' => 'edit', 'msg' => ''));
        }

        if(isset($_POST["levelId"]) && isset($_POST["levelName"]) && isset($_POST["floor"])) {
            $levelId = makeValidInput($_POST["levelId"]);
            $level = Level::whereId($levelId);
            $level->name = makeValidInput($_POST["levelName"]);
            $level->floor = makeValidInput($_POST["floor"]);
            try {
                $level->save();
                return Redirect::route('levels');
            }
            catch (\Exception $e) {
                $levels = Level::all();
                return view('levels.levels', array('levels' => $levels, 'selectedLevel' => $level,
                    'mode' => 'edit', 'msg' => 'سطح مورد نظر در سامانه موجود است'));
            }
        }

        return Redirect::route('levels');

    }

}
