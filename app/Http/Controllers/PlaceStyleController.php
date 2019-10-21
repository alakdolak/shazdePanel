<?php

namespace App\Http\Controllers;

use App\models\Place;
use App\models\PlaceStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class PlaceStyleController extends Controller
{

    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('config.placeStyle', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $placeStyle = PlaceStyle::where('kindPlaceId', $kind->id)->get();
            return view('config.placeStyle', compact(['kind', 'placeStyle']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $placeStyle = PlaceStyle::where('name', $request->name)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($placeStyle == null){
            $placeStyle =  new PlaceStyle();
            $placeStyle->name = $request->name;
            $placeStyle->kindPlaceId = $request->kindPlaceId;
            $placeStyle->save();
        }
        else{
            Session::flash('error', 'این سبک مکان  قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $placeStyle = PlaceStyle::find($request->id);

        if($placeStyle != null){
            $checkPlace = PlaceStyle::where('name', $request->name)->first();
            if($checkPlace == null) {
                $placeStyle->name = $request->name;
                $placeStyle->save();
            }
            else{
                Session::flash('error', 'این سبک مکان موجود می باشد');
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

        PlaceStyle::find($request->id)->delete();

        return redirect()->back();
    }
}
