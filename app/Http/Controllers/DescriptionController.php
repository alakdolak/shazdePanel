<?php

namespace App\Http\Controllers;

use App\models\Place;
use App\models\ReportsType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DescriptionController extends Controller
{
    public function index()
    {
        $descriptions = ReportsType::all();
        $places = Place::all();

        foreach ($descriptions as $item){
            foreach ($places as $place){
                if($place->id == $item->kindPlaceId){
                    $item->place = $place->name;
                }
            }
        }

        return view('description.index', compact(['descriptions', 'places']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'placeId' => 'required'
        ]);
        $report = ReportsType::where('kindPlaceId', $request->placeId)->where('description', $request->description)->first();

        if($report == null) {
            $report = new ReportsType();
            $report->description = $request->description;
            $report->kindPlaceId = $request->placeId;
            $report->save();
        }
        else{
            Session::flash('error', 'این متن برای نوع مکان موجود است');
        }

        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'placeId' => 'required',
            'description' => 'required'
        ]);

        $report = ReportsType::find($request->id);

        if($report != null){
            $report->description = $request->description;
            $report->kindPlaceId = $request->placeId;
            $report->save();
        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        ReportsType::find($request->id)->delete();

        return redirect()->back();
    }


}
