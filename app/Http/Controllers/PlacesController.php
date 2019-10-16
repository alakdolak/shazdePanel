<?php

namespace App\Http\Controllers;

use App\models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PlacesController extends Controller
{
    public function index()
    {
        $places = Place::all();

        return view('admin.places.index', compact(['places']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $place = Place::where('name', $request->name)->first();

        if($place == null){
            $place =  new Place();
            $place->name = $request->name;
            $place->visibility = 1;
            $place->save();
        }
        else{
            Session::flash('error', 'این مکان قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $place = Place::find($request->id);

        if($place != null){
            $checkPlace = Place::where('name', $request->name)->first();
            if($checkPlace == null) {
                $place->name = $request->name;
                $place->save();
            }
            else{
                Session::flash('error', 'مکانی با این نام موجود می باشد');
            }
        }
        else{
            Session::flash('error', 'مشکلی در ویرایش به وجود امد. لطفا دوباره تلاش کنید');
        }
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        Place::find($request->id)->delete();

        return redirect()->back();
    }
}
