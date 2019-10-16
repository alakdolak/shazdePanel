<?php

namespace App\Http\Controllers;

use App\models\Activity;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()
    {
        $activities = Activity::all();

        return view('activities.index', compact(['activities']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'newName' => 'required',
            'newRate' => 'required',
            'actualName' => 'required',
            'newPic' => 'required'
        ]);

        $err = "";

        if(isset($_FILES["newPic"]) && $_FILES['newPic']['error'] == 0) {

            $pic = __DIR__ . '/../../../../assets/activities/' . $_FILES["newPic"]["name"];

            $err = uploadCheck($pic, "newPic", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($pic, "newPic", "افزودن عکس جدید");
                if (!empty($err))
                    dd($err);
            }
            else {
                dd($err);
            }
        }
        else{
            return redirect()->back();
        }

        if(empty($err)) {
            $activity = new Activity();
            $activity->name = makeValidInput($request->newName);
            $activity->actualName = makeValidInput($request->actualName);
            $activity->pic = $_FILES["newPic"]["name"];
            $activity->rate = makeValidInput($request->newRate);

            $activity->save();
        }

        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'editId' => 'required',
            'editName' => 'required',
            'editRate' => 'required'
        ]);

        $activityId = makeValidInput($request->editId);
        $activity = Activity::whereId($activityId);
        $newfilename = null;
        $err = "";

        if(isset($_FILES["editPic"]) && $_FILES['editPic']['error'] == 0) {

            if($activity->pic != null){
                \File::delete(__DIR__ . '/../../../../assets/activities/' . $activity->pic);
            }

            $newfilename = $_FILES["editPic"]["name"];
            $pic = __DIR__ . '/../../../../assets/activities/' . $_FILES["editPic"]["name"];

            $err = uploadCheck($pic, "editPic", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($pic, "editPic", "افزودن عکس جدید");
                if (!empty($err))
                    dd($err);
            }
            else {
                dd($err);
            }
        }

        if(empty($err)) {

            $activity->name = makeValidInput($request->editName);
            $activity->actualName = makeValidInput($request->editActualName);
            if($newfilename != null)
                $activity->pic = $newfilename;
            $activity->rate = makeValidInput($request->editRate);

            $activity->save();

        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $activity = Activity::find($request->id);

        if($activity != null){

            if($activity->pic != null){
                \File::delete(__DIR__ . '/../../../../assets/activities/' . $activity->pic);
            }
            $activity->delete();

        }

        return redirect()->back();
    }
}
