<?php

namespace App\Http\Controllers;

use App\models\Opinion;
use App\models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OpinionsController extends Controller
{
    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('admin.opinion.index', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $opinions = Opinion::where('kindPlaceId', $kind->id)->get();
            return view('admin.opinion.index', compact(['kind', 'opinions']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $opinion= Opinion::where('name', $request->name)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($opinion == null){
            $opinion =  new Opinion();
            $opinion->name = $request->name;
            $opinion->kindPlaceId = $request->kindPlaceId;
            $opinion->save();
        }
        else{
            Session::flash('error', 'این نظر قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $opinion = Opinion::find($request->id);

        if($opinion != null){
            $checkOpinion = Opinion::where('name', $request->name)->first();
            if($checkOpinion == null) {
                $opinion->name = $request->name;
                $opinion->save();
            }
            else{
                Session::flash('error', 'این نظر موجود می باشد');
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

        Opinion::find($request->id)->delete();

        return redirect()->back();
    }
}
