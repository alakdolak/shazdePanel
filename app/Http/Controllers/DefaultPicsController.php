<?php

namespace App\Http\Controllers;

use App\models\DefaultPic;
use Illuminate\Http\Request;

class DefaultPicsController extends Controller
{
    public function index()
    {
        $defaultPics = DefaultPic::all();

        return view('config.defaultPics', compact(['defaultPics']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'newPic' => 'required'
        ]);
        $err = "";

        if(isset($_FILES["newPic"]) && $_FILES['newPic']['error'] == 0) {

            $pic = __DIR__ . '/../../../../assets/defaultPic/' . $_FILES["newPic"]["name"];

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
            $defaultPic = new DefaultPic();
            $defaultPic->name = $_FILES["newPic"]["name"];
            $defaultPic->save();
        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $defaultPic = DefaultPic::find($request->id);

        if($defaultPic != null){

            if($defaultPic->name != null){
                \File::delete(__DIR__ . '/../../../../assets/defaultPic/' . $defaultPic->name);
            }
            $defaultPic->delete();
        }

        return redirect()->back();
    }
}
